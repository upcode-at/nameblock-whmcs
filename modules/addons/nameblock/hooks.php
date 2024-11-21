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
     $apiToken = Capsule::table('tbladdonmodules')->where('module', 'nameblock_integration')->value('apiToken');
     $blocksAPI = new Blocks($apiToken);
 
     $domain = $vars['sld'] . '.' . $vars['tld'];
     $response = $blocksAPI->checkDomainBlockStatus($domain);
 
     if (isset($response['status']) && $response['status'] === 'blocked') {
         return [
             'status' => 'error',
             'description' => 'This domain is blocked by Nameblock API and cannot be registered.',
         ];
     }
 });
