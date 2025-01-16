<?php
/**
 * NameBlock - Prevent DNS Abuse Addon Module for WHMCS
 *
 * Provides admin and client functionalities for managing Nameblock products.
 */

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\Nameblock\Admin\AdminDispatcher;
use WHMCS\Module\Addon\Nameblock\Client\ClientDispatcher;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Addon module configuration.
 *
 * @return array
 */
function nameblock_config()
{
    return [
        'name' => 'NameBlock - Prevent DNS Abuse',
        'description' => 'This module provides NameBlock - Prevent DNS Abuse',
        'author' => 'Nameblock',
        'language' => 'english',
        'version' => '1.1',
        'fields' => [
            'apiToken' => [
                'FriendlyName' => 'API Token',
                'Type' => 'password',
                'Size' => '50',
                'Description' => 'Enter your Nameblock API Token here',
                'Default' => '',
            ],
            'agreement' => [
                'FriendlyName' => 'Agreement',
                'Type' => 'yesno',
                'Description' => 'I agree to the <a href="https://nameblock.com/terms" target="_blank">Terms of Service</a>',
                'Default' => '',
            ],
        ]
    ];
}

/**
 * Activate module.
 */
function nameblock_activate()
{
    try {
        Capsule::schema()->create('mod_nameblock_logs', function ($table) {
            $table->increments('id');
            $table->string('action');
            $table->text('request_payload');
            $table->text('response_payload');
            $table->timestamp('created_at')->useCurrent();
        });

        Capsule::schema()->create('mod_nameblock_pending_orders', function ($table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('domain');
            $table->integer('user_id');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
        return ['status' => 'success', 'description' => 'Module activated successfully.'];
    } catch (\Exception $e) {
        return ['status' => 'error', 'description' => 'Activation Error: ' . $e->getMessage()];
    }
}

/**
 * Deactivate module.
 */
function nameblock_deactivate()
{
    try {
        Capsule::schema()->dropIfExists('mod_nameblock_logs');
        Capsule::schema()->dropIfExists('mod_nameblock_pending_orders');
        return ['status' => 'success', 'description' => 'Module deactivated successfully.'];
    } catch (\Exception $e) {
        return ['status' => 'error', 'description' => 'Deactivation Error: ' . $e->getMessage()];
    }
}

/**
 * Admin Area Output.
 */
function nameblock_output($vars)
{
    $agreementAccepted = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'agreement')
        ->value('value');

    if (!$agreementAccepted) {
        echo '<p>You must agree to the <a href="https://nameblock.com/terms" target="_blank">Terms of Service</a> before using this module.</p>';
        return;
    }

    $action = $_GET['action'] ?? '';
    $dispatcher = new AdminDispatcher();
    echo $dispatcher->dispatch($action, $vars);
}


/**
 * Cron Job to Process Pending Orders.
 */
function nameblock_cron()
{
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

    foreach ($pendingOrders as $order) {
        try {
            $controller = new \WHMCS\Module\Addon\Nameblock\Admin\Controller();

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
                    ->update(['status' => 'completed']);
                logActivity("Nameblock Order Processed: {$order->domain}");
            } else {
                logActivity("Nameblock Order Failed: {$order->domain}");
            }
        } catch (\Exception $e) {
            logActivity("Nameblock Cron Error: " . $e->getMessage());
        }
    }
}
