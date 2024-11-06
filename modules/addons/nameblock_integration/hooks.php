<?php

use WHMCS\Database\Capsule;

require_once __DIR__ . '/lib/NameblockAPI.php';

add_hook('DomainSearch', 1, function($vars) {
    $apiToken = Capsule::table('tbladdonmodules')->where('module', 'nameblock_integration')->value('apiToken');
    $api = new NameblockAPI($apiToken);

    $domain = $vars['sld'] . '.' . $vars['tld'];
    $response = $api->checkDomainBlockStatus($domain);

    if ($response['status'] === 'blocked') {
        return [
            'status' => 'error',
            'description' => 'This domain is blocked by Nameblock API and cannot be registered.',
        ];
    }
});

add_hook('ClientAreaPage', 1, function($vars) {
    $apiToken = Capsule::table('tbladdonmodules')->where('module', 'nameblock_integration')->value('apiToken');
    $api = new NameblockAPI($apiToken);
    $status = $api->getAccountStatus();

    return [
        'nameblockStatus' => $status,
    ];
});
