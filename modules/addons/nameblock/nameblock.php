<?php
/**
 * WHMCS SDK Sample Addon Module
 *
 * An addon module allows you to add additional functionality to WHMCS. It
 * can provide both client and admin facing user interfaces, as well as
 * utilise hook functionality within WHMCS.
 *
 * This sample file demonstrates how an addon module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Addon Modules are stored in the /modules/addons/ directory. The module
 * name you choose must be unique, and should be all lowercase, containing
 * only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "addonmodule" and therefore all functions
 * begin "addonmodule_".
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/addon-modules/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

/**
 * Require any libraries needed for the module to function.
 * require_once __DIR__ . '/path/to/library/loader.php';
 *
 * Also, perform any initialization required by the service's library.
 */

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\Nameblock\Admin\AdminDispatcher;


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define addon module configuration parameters.
 *
 * Includes a number of required system fields including name, description,
 * author, language and version.
 *
 * Also allows you to define any configuration parameters that should be
 * presented to the user when activating and configuring the module. These
 * values are then made available in all module function calls.
 *
 * Examples of each and their possible configuration parameters are provided in
 * the fields parameter below.
 *
 * @return array
 */
function nameblock_config()
{
    return [
        // Display name for your module
        'name' => 'Nameblock Integration',
        // Description displayed within the admin interface
        'description' => 'This module provides Nameblock Integration',
        // Module author name
        'author' => 'Nameblock',
        // Default language
        'language' => 'english',
        // Version number
        'version' => '1.0',
        'fields' => [
            'apiToken' => [
                'FriendlyName' => 'API Token',
                'Type' => 'password',
                'Size' => '50',
                'Description' => 'Enter your Nameblock API Token here',
                'Default' => '',
            ],
            'recommendedProduct' => [
                'FriendlyName' => 'Recommended Product ID',
                'Type' => 'text',
                'Size' => '10',
                'Description' => 'Enter the Product ID to recommend in the cart',
                'Default' => '',
            ],
        ]
    ];
}

/**
 * Activate.
 *
 * Called upon activation of the module for the first time.
 * Creates necessary database schema and configuration entries.
 *
 * @return array Optional success/failure message
 */
function nameblock_activate()
{
    try {
        Capsule::schema()
            ->create(
                'mod_nameblock_logs',
                function ($table) {
                    /** @var \Illuminate\Database\Schema\Blueprint $table */
                    $table->increments('id');
                    $table->string('action');
                    $table->text('request_payload');
                    $table->text('response_payload');
                    $table->timestamp('created_at')->useCurrent();
                }
            );

        Capsule::schema()->create('mod_nameblock_pending_orders', function ($table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('domain');
            $table->integer('user_id');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });

        Capsule::table('tbladdonmodules')->insert([
            [
                'module' => 'nameblock',
                'setting' => 'apiToken',
                'value' => '',
            ],
            [
                'module' => 'nameblock',
                'setting' => 'enableLogging',
                'value' => '0',
            ],
        ]);

        return [
            'status' => 'success',
            'description' => 'Nameblock Integration module activated successfully. '
                . 'Ensure you configure the API token before use.',
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'description' => 'Error during activation: ' . $e->getMessage(),
        ];
    }
}

/**
 * Deactivate.
 *
 * Called upon deactivation of the module.
 * Removes any database schema or settings created during activation.
 *
 * @return array Optional success/failure message
 */
function nameblock_deactivate()
{
    try {
        Capsule::schema()->dropIfExists('mod_nameblock_logs');
        Capsule::schema()->dropIfExists('mod_nameblock_pending_orders');

        Capsule::table('tbladdonmodules')
            ->where('module', 'nameblock')
            ->delete();

        return [
            'status' => 'success',
            'description' => 'Nameblock Integration module deactivated successfully.',
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'description' => 'Error during deactivation: ' . $e->getMessage(),
        ];
    }
}

/**
 * Admin Area Output.
 *
 * Called when the addon module is accessed via the admin area.
 * Should return HTML output for display to the admin user.
 *
 * This function is optional.
 *
 * @see AddonModule\Admin\Controller::index()
 *
 * @return string
 */
function nameblock_output($vars)
{
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $dispatcher = new AdminDispatcher();

    echo $dispatcher->dispatch($action, $vars);
}

function nameblock_cron() {
    $pendingOrders = Capsule::table('mod_nameblock_pending_orders')
        ->where('status', 'pending')
        ->get();

    if ($pendingOrders->isEmpty()) {
        return;
    }

    $apiToken = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'apiToken')
        ->value('value');

    if (!$apiToken) {
        logActivity("Nameblock: API token is not configured.");
        return;
    }

    $controller = new \WHMCS\Module\Addon\Nameblock\Admin\Controller();

    foreach ($pendingOrders as $order) {
        try {
            $payload = [
                'promotion' => null,
                'registrant_id' => $order->user_id,
                'block_label' => $order->domain,
                'domain_name' => $order->domain,
                'tld' => substr(strrchr($order->domain, "."), 1),
                'product_id' => 'as-01',
                'quantity' => 1,
                'term' => 1,
            ];

            $response = $controller->createOrder($payload);

            if (isset($response['status']) && $response['status'] === 'ok') {
                Capsule::table('mod_nameblock_pending_orders')
                    ->where('id', $order->id)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                logActivity("Nameblock Order Created Successfully for Domain: {$order->domain}");
            } else {
                logActivity("Nameblock Order Failed for Domain: {$order->domain}. Response: " . json_encode($response));
            }
        } catch (\Exception $e) {
            logActivity("Nameblock Order Processing Error for Domain: {$order->domain}. Error: " . $e->getMessage());
        }
    }
}

function nameblock_cronjob()
{
    return [
        [
            'name' => 'Process Nameblock Orders',
            'description' => 'Attempts to process pending Nameblock orders every hour.',
            'file' => 'cron.php',
        ],
    ];
}
