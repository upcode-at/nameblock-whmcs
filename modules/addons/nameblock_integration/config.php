<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function nameblock_integration_config() {
    return [
        'name' => 'Nameblock Integration',
        'description' => 'Integrates Nameblock API for domain blocking and checks within WHMCS',
        'version' => '1.0',
        'author' => 'YourName',
        'fields' => [
            'apiToken' => [
                'FriendlyName' => 'API Token',
                'Type' => 'password',
                'Size' => '50',
                'Description' => 'Enter your Nameblock API Token here',
                'Default' => '',
            ],
        ],
    ];
}
