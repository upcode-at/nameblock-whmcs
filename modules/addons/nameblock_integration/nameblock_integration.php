<?php

function nameblock_integration_output($vars) {
    echo '<h2>Nameblock Integration</h2>';
    echo '<p>Use this module to check domains against the Nameblock API.</p>';

    $apiToken = $vars['apiToken'];
    $api = new NameblockAPI($apiToken);
    $status = $api->getAccountStatus();

    echo '<p>Account Status: ' . $status['status'] . '</p>';
}
