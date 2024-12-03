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

require_once __DIR__ . '/lib/NameblockAPI.php';
require_once __DIR__ . '/lib/Endpoints/Blocks.php';
require_once __DIR__ . '/lib/Endpoints/Registrants.php';
require_once __DIR__ . '/lib/Endpoints/Orders.php';

add_hook('AfterShoppingCartCheckout', 1, function ($vars) {
    $orderID = $vars['OrderID'];
    $userID = $vars['ClientID'];

    $domains = Capsule::table('tbldomains')
        ->where('orderid', $orderID)
        ->get();

    if ($domains->isEmpty()) {
        return;
    }

    foreach ($domains as $domain) {
        // Insert order into custom table
        Capsule::table('mod_nameblock_pending_orders')->insert([
            'order_id' => $orderID,
            'domain' => $domain->domain,
            'user_id' => $userID,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
});

add_hook('AfterShoppingCartCheckout', 1, function($vars) {
    $orderID = $vars['OrderID'];
    $orderData = Capsule::table('tblorders')->where('id', $orderID)->first();

    if (!$orderData) {
        return;
    }

    $userID = $orderData->userid;

    $domains = Capsule::table('tbldomains')
        ->where('orderid', $orderID)
        ->get();

    if ($domains->isEmpty()) {
        return;
    }

    $apiToken = Capsule::table('tbladdonmodules')
        ->where('module', 'nameblock')
        ->where('setting', 'apiToken')
        ->value('value');

    if (!$apiToken) {
        logActivity("Nameblock: API token not configured.");
        return;
    }

    $controller = new \WHMCS\Module\Addon\Nameblock\Admin\Controller();

    foreach ($domains as $domain) {
        $orderPayload = [
            'promotion' => $orderData->promocode ?? null,
            'registrant_id' => $userID,
            'block_label' => $domain->domain,
            'domain_name' => $domain->domain,
            'tld' => substr(strrchr($domain->domain, "."), 1),
            'product_id' => 'as-01',
            'quantity' => 1,
            'term' => 1,
        ];

        try {
            $response = $controller->createOrder($orderPayload);

            logActivity("Nameblock Order Created for Domain: {$domain->domain}. Response: " . json_encode($response));
        } catch (\Exception $e) {
            logActivity("Nameblock Order Creation Failed for Domain: {$domain->domain}. Error: " . $e->getMessage());
        }
    }
});


add_hook('ClientAreaPage', 1, function ($vars) {
    if ($vars['filename'] === 'clientarea' && isset($_GET['action']) && $_GET['action'] === 'nameblock') {
        $apiToken = Capsule::table('tbladdonmodules')
            ->where('module', 'nameblock')
            ->where('setting', 'apiToken')
            ->value('value');

        $blocksAPI = new Blocks($apiToken);

        $blockedDomains = [];
        try {
            $response = $blocksAPI->getAllBlocks('blocked');
            if (isset($response['data'])) {
                $blockedDomains = $response['data'];
            }
        } catch (Exception $e) {
            logActivity('Nameblock API Error: ' . $e->getMessage());
        }

        return [
            'pagetitle' => 'Blocked Domains',
            'breadcrumb' => ['index.php?m=nameblock' => 'Blocked Domains'],
            'templatefile' => 'clientarea',
            'requirelogin' => true,
            'vars' => [
                'blockedDomains' => $blockedDomains,
            ],
        ];
    }
});
