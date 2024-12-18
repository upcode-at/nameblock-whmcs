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

    // Loop through all items in the order
    foreach ($orderItems as $item) {
        // Fetch product details to determine if it's a Nameblock product
        $product = Capsule::table('tblproducts')
            ->where('id', $item->packageid)
            ->first();

        if ($product && strpos($product->name, 'Nameblock') !== false) {
            // Nameblock product found, mark the order for future processing
            Capsule::table('mod_nameblock_pending_orders')->insert([
                'order_id' => $orderId,
                'product_id' => $item->packageid,
                'user_id' => $item->userid,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'process_after' => date('Y-m-d H:i:s', strtotime('+2 hours')),
            ]);
        }
    }
});

add_hook('ClientAreaPrimaryNavbar', 1, function (MenuItem $primaryNavbar) {
    if (!is_null($_SESSION['uid'])) {
        $primaryNavbar->addChild('nameblock_blocklist', [
            'label' => 'Block List',
            'uri' => 'clientarea.php?action=productdetails&m=nameblock&action=getBlockList',
            'order' => 10,
        ]);
    }
});

add_hook('ClientAreaPage', 1, function($vars) {
    if ($_GET['m'] === 'nameblock' && $_GET['action'] === 'getBlockList') {
        $ca = new ClientArea();
        $ca->setPageTitle("My Block List");
        $ca->addToBreadCrumb('index.php', 'Home');
        $ca->addToBreadCrumb('clientarea.php', 'Client Area');
        $ca->addToBreadCrumb('clientarea.php?action=productdetails&m=nameblock&action=getBlockList', 'Block List');

        // Controller-Methode für Daten abrufen
        $controller = new \Nameblock\Client\Controller();
        $output = $controller->getBlockList($vars);

        $ca->setTemplate('clientblocklist'); // Lade das Template
        $ca->assign('output', $output);

        return $ca->output();
    }
});