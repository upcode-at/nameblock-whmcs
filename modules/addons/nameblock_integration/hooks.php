<?php

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

add_hook('ClientAreaPage', 1, function($vars) {
    $apiToken = Capsule::table('tbladdonmodules')->where('module', 'nameblock_integration')->value('apiToken');
    $registrantsAPI = new Registrants($apiToken);

    $registrants = $registrantsAPI->getAllRegistrants();
    
    return [
        'registrants' => $registrants,
    ];
});
