<?php
/**
 * WHMCS SDK Sample Addon Module Hooks File
 *
 * Hooks allow you to tie into events that occur within the WHMCS application.
 *
 * This allows you to execute your own code in addition to, or sometimes even
 * instead of that which WHMCS executes by default.
 *
 * @see https://developers.whmcs.com/hooks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

use WHMCS\Database\Capsule;
use WHMCS\View\Menu\Item as MenuItem;
use WHMCS\ClientArea;
use WHMCS\Smarty;


require_once __DIR__ . '/lib/NameblockAPI.php';
require_once __DIR__ . '/lib/Endpoints/Blocks.php';
require_once __DIR__ . '/lib/Endpoints/Registrants.php';
require_once __DIR__ . '/lib/Endpoints/Orders.php';


/**
 * Hook to mark Nameblock product orders after payment is completed.
 * The actual processing will be done with a delay.
 *
 * @param array $params WHMCS parameters, including Order ID and related details.
 */
add_hook('OrderPaid', 1, function($params) {
    // Order details
    $orderId = $params['orderid'];

    // Fetch order items
    $orderItems = Capsule::table('tblhosting')
        ->where('orderid', $orderId)
        ->get();

    // Fetch API token
    $apiToken = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'apiToken')
        ->value('value');

    if (!$apiToken) {
        logActivity("Nameblock: API token is not configured.");
        return;
    }

    // Loop through all items in the order
    foreach ($orderItems as $item) {
        // Fetch product details to determine if it's a Nameblock product
        $product = Capsule::table('tblproducts')
            ->where('id', $item->packageid)
            ->first();

        if ($product && strpos($product->name, 'Nameblock') !== false) {
            // Fetch product ID from Nameblock API
            $productsAPI = new \Products($apiToken);
            $response = $productsAPI->getAllProducts('abuse_shield');

            $productId = null;
            if (isset($response['data']) && is_array($response['data'])) {
                foreach ($response['data'] as $apiProduct) {
                    if ($apiProduct['name'] === $product->name) {
                        $productId = $apiProduct['id'];
                        break;
                    }
                }
            }

            if (!$productId) {
                logActivity("Nameblock: Product ID not found for product name: {$product->name}");
                continue;
            }

            // Nameblock product found, mark the order for future processing
            Capsule::table('mod_nameblock_pending_orders')->insert([
                'order_id' => $orderId,
                'product_id' => $productId,
                'product_name' => $product->name,
                'user_id' => $item->userid,
                'domain' => $item->domain,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'process_after' => date('Y-m-d H:i:s', strtotime('+2 hours')),
            ]);
        }
    }
});

/**
 * Hook to display a custom message on the client area dashboard.
 */
add_hook('ClientAreaHomepage', 1, function($vars) {
    $userId = $_SESSION['uid'];
    $pendingOrders = Capsule::table('mod_nameblock_pending_orders')
        ->where('user_id', $userId)
        ->get();

    $blocksData = [];
    $apiToken = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'apiToken')
        ->value('value');

    if ($apiToken) {
        $blocksAPI = new Blocks($apiToken);
        foreach ($pendingOrders as $order) {
            try {
                $parts = explode('.', $order->domain, 2);
                if (count($parts) == 2) {
                    $label = $parts[0];
                    $tld = $parts[1];
                    $response = $blocksAPI->getBlockList($label, $tld, 'as-01');

                    if (isset($response['data']['variants']) && is_array($response['data']['variants'])) {
                        $blocksData[$order->domain] = $response['data']['variants'];
                        
                    } else {
                        $blocksData[$order->domain] = [];
                    }
                } else {
                    $blocksData[$order->domain] = [];
                }
            } catch (Exception $e) {
                $blocksData[$order->domain] = [];
            }
        }
    }

    $smarty = new \WHMCS\Smarty();
    $smarty->assign('pendingOrders', $pendingOrders);
    $smarty->assign('blocksData', $blocksData);
    
    return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/dashboard.tpl');
});