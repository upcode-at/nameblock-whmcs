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
 
add_hook('DomainSearch', 1, function($vars) {
    try {
        $apiToken = Capsule::table('tbladdonmodules')
            ->where('module', 'nameblock')
            ->where('setting', 'apiToken')
            ->value('value');

        $blocksAPI = new Blocks($apiToken);

        $domain = $vars['sld'] . '.' . $vars['tld'];
        $response = $blocksAPI->checkDomainBlockStatus($domain);

        if (isset($response['status']) && $response['status'] === 'blocked') {
            return [
                'status' => 'error',
                'description' => 'This domain is blocked by Nameblock API and cannot be registered.',
            ];
        }
    } catch (Exception $e) {
        // Log errors for debugging
        logActivity('Nameblock API Error: ' . $e->getMessage());
        return [
            'status' => 'error',
            'description' => 'An error occurred while checking the domain status. Please try again later.',
        ];
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
