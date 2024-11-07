<?php

function nameblock_integration_output($vars) {
    echo '<h2>Nameblock Integration</h2>';
    echo '<p>Manage your Nameblock API integration here.</p>';

    $apiToken = $vars['apiToken'];
    $registrantsAPI = new Registrants($apiToken);
    $registrants = $registrantsAPI->getAllRegistrants();

    echo '<h3>All Registrants</h3>';
    foreach ($registrants as $registrant) {
        echo "<p>Registrant ID: {$registrant['registrant_id']} - Name: {$registrant['name']}</p>";
    }
}
