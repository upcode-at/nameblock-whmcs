<?php

function nameblock_integration_output($vars) {
    $apiToken = $vars['apiToken'];

    // Initialize API Endpoints
    $blocksAPI = new Blocks($apiToken);
    $registrantsAPI = new Registrants($apiToken);
    $productsAPI = new Products($apiToken);
    $ordersAPI = new Orders($apiToken);
    $tldsAPI = new TLDs($apiToken);

    // Fetch Data from Nameblock API
    $registrants = $registrantsAPI->getAllRegistrants();
    $blocks = $blocksAPI->getAllBlocks();
    $products = $productsAPI->getAllProducts();
    $orders = $ordersAPI->getAllOrders();
    $tlds = $tldsAPI->getAllTLDs();

    // Pass data to the template
    $templateVars = [
        'registrants' => $registrants,
        'blocks' => $blocks,
        'products' => $products,
        'orders' => $orders,
        'tlds' => $tlds,
    ];

    echo renderTemplate('modules/addons/nameblock_integration/templates/admin.tpl', $templateVars);
}
