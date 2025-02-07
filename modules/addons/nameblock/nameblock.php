<?php
/**
 * NameBlock - Prevent DNS Abuse Addon Module for WHMCS
 *
 * Provides admin and client functionalities for managing Nameblock products.
 */

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\Nameblock\Admin\AdminDispatcher;
use WHMCS\Module\Addon\Nameblock\Client\ClientDispatcher;

use WHMCS\Module\Server\CustomAction;
use WHMCS\Module\Server\CustomActionCollection;

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
                'Description' => 'I agree to the <a href="../modules/addons/nameblock/download.php" target="_blank">Terms of Service</a>',
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
            $table->integer('product_id');
            $table->string('product_name');
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
        echo '<p>You must agree to the <a href="../modules/addons/nameblock/download.php" target="_blank">Terms of Service</a> before using this module.</p>';
        return;
    }

    $apiToken = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'apiToken')
        ->value('value');

    if (!$apiToken) {
        echo '<style>
                form {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    background-color: #f9f9f9;
                }
                label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                }
                input[type="text"],
                input[type="email"] {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }
                input[type="submit"] {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                input[type="submit"]:hover {
                    background-color: #45a049;
                }
                h1 {
                    margin-top: 30px;
                    margin-bottom: 15px;
                    color: #333;
                    font-size: 1.5em;
                    border-bottom: 2px solid #ccc;
                    padding-bottom: 5px;
                }
              </style>';
        echo '<form method="post" action="save_api_token.php">
                <label for="email">E-Mail-Address</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="name">Reseller Business Name</label>
                <input type="text" id="name" name="name" required>
                <br>
                <h1>Primary/Default Contact Information</h1>
                <br>
                <label for="primary_email">Primary/Default Contact E-Mail</label>
                <input type="email" id="primary_email" name="primary_email" required>
                <br>
                <label for="primary_phone">Primary/Default Contact Phone</label>
                <input type="text" id="primary_phone" name="primary_phone" required>
                <br>
                <h1>Address Information</h1>
                <br>
                <label for="street">Street</label>
                <input type="text" id="street" name="street" required>
                <br>
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
                <br>
                <label for="postal_code">Postal Code</label>
                <input type="text" id="postal_code" name="postal_code" required>
                <br>
                <label for="state">State</label>
                <input type="text" id="state" name="state" required>
                <br>
                <label for="country">Country Code</label>
                <input type="text" id="country" name="country" required>
                <br>
                <h1>Technical Contact</h1>
                <br>
                <label for="tech_email">Technical Contact E-Mail</label>
                <input type="email" id="tech_email" name="tech_email" required>
                <br>
                <label for="tech_phone">Technical Contact Phone</label>
                <input type="text" id="tech_phone" name="tech_phone" required>
                <br>
                <h1>Administrative Contact</h1>
                <br>
                <label for="admin_email">Administrative Contact E-Mail</label>
                <input type="email" id="admin_email" name="admin_email" required>
                <br>
                <label for="admin_phone">Administrative Contact Phone</label>
                <input type="text" id="admin_phone" name="admin_phone" required>
                <br>
                <h1>Billing Contact</h1>
                <br>
                <label for="billing_email">Billing Contact E-Mail</label>
                <input type="email" id="billing_email" name="billing_email" required>
                <br>
                <label for="billing_phone">Billing Contact Phone</label>
                <input type="text" id="billing_phone" name="billing_phone" required>
                <br>
                <h1>Secondary Contact (in case Primary is unavailable)</h1>
                <br>
                <label for="secondary_name">Secondary Contact Name</label>
                <input type="text" id="secondary_name" name="secondary_name" required>
                <br>
                <label for="secondary_email">Secondary Contact E-Mail</label>
                <input type="email" id="secondary_email" name="secondary_email" required>
                <br>
                <input type="submit" value="Submit Onboarding Information">
              </form>';
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

            // Fetch product details to determine the product_id from the Nameblock API
            $productsAPI = new \Products($apiToken);
            $response = $productsAPI->getAllProducts('abuse_shield');

            $productId = null;
            if (isset($response['data']) && is_array($response['data'])) {
                foreach ($response['data'] as $product) {
                    if ($product['name'] === $order->product_name) {
                        $productId = $product['id'];
                        break;
                    }
                }
            }

            if (!$productId) {
                logActivity("Nameblock: Product ID not found for product name: {$order->product_name}");
                continue;
            }

            $payload = [
                'promotion' => null,
                'registrant_id' => $order->user_id,
                'block_label' => $order->domain,
                'domain_name' => $order->domain,
                'tld' => substr(strrchr($order->domain, "."), 1),
                'product_id' => $productId,
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

