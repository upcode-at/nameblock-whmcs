<?php

namespace WHMCS\Module\Addon\Nameblock\Admin;

use WHMCS\Database\Capsule;
use WHMCS\Smarty;
require_once __DIR__ . '/../Endpoints/Products.php';


/**
 * Admin Area Controller for Nameblock Integration
 */
class Controller {

    /**
     * Index action: Display summary of data.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function index($vars)
    {
        $modulelink = $vars['modulelink']; // Base URL for the module

    return <<<HTML
    <h2>Nameblock Integration</h2>
    <p>Welcome to the Nameblock Integration Module.</p>

    <ul>
        <li><a href="{$modulelink}&action=createOrder">Create New Order</a></li>
        <li><a href="{$modulelink}&action=listOrders">List Orders</a></li>
        <li><a href="{$modulelink}&action=listBlocks">List Blocks</a></li>
        <li><a href="{$modulelink}&action=listRegistrants">List Registrants</a></li>
        <li><a href="{$modulelink}&action=viewRegistrant">View Registrant</a></li>
        <li><a href="{$modulelink}&action=createRegistrant">Create Registrants</a></li>
        <li><a href="{$modulelink}&action=listProducts">List Products</a></li>
        <li><a href="{$modulelink}&action=viewLogs">View Logs</a></li>
    </ul>
    HTML;
    }

    /**
     * Show action: Display specific details.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function show($vars)
    {
        $modulelink = $vars['modulelink'];
        $version = $vars['version'];

        return <<<EOF

<h2>Show Action</h2>

<p>This is the <em>show</em> action of the Nameblock plugin.</p>

<p>The currently installed version is: <strong>{$version}</strong></p>

<p>
    <a href="{$modulelink}" class="btn btn-info">
        <i class="fa fa-arrow-left"></i>
        Back to Index
    </a>
</p>

EOF;
    }

    public function createOrder($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();

        $templateVars = [
            'modulelink' => $modulelink,
            'response' => null,
            'error' => null,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $apiToken = Capsule::table('tbladdonmodules')
                    ->where('module', 'nameblock_integration')
                    ->where('setting', 'apiToken')
                    ->value('value');

                $ordersAPI = new \Orders($apiToken);

                $promotion = $_POST['promotion'] ?? '';
                $registrantId = (int)$_POST['registrant_id'];
                $orderLines = [
                    [
                        'quantity' => (int)$_POST['quantity'],
                        'term' => (int)$_POST['term'],
                        'product_id' => $_POST['product_id'],
                        'payload' => [
                            'block_label' => $_POST['block_label'],
                            'domain_name' => $_POST['domain_name'],
                            'tld' => $_POST['tld'],
                        ],
                    ],
                ];

                $response = $ordersAPI->createOrder($promotion, 'create', $registrantId, $orderLines);

                $templateVars['response'] = $response;
            } catch (\Exception $e) {
                $templateVars['error'] = $e->getMessage();
            }
        }

        $templateVars['promotion'] = $_POST['promotion'] ?? '';
        $templateVars['registrant_id'] = $_POST['registrant_id'] ?? '';
        $templateVars['block_label'] = $_POST['block_label'] ?? '';
        $templateVars['domain_name'] = $_POST['domain_name'] ?? '';
        $templateVars['tld'] = $_POST['tld'] ?? '';
        $templateVars['product_id'] = $_POST['product_id'] ?? 'as-01';
        $templateVars['quantity'] = $_POST['quantity'] ?? 1;
        $templateVars['term'] = $_POST['term'] ?? 1;

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/order.tpl');
    }

    public function listOrders($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'orders' => [],
            'error' => null,
        ];
    
        try {
            $apiToken = Capsule::table('tbladdonmodules')
                ->where('module', 'nameblock')
                ->where('setting', 'apiToken')
                ->value('value');
    
            $ordersAPI = new \Orders($apiToken);
            $response = $ordersAPI->getAllOrders();
    
            if (isset($response['data']) && is_array($response['data'])) {
                $templateVars['orders'] = $response['data'];
            } else {
                $templateVars['error'] = "No orders found or invalid response format.";
            }
        } catch (\Exception $e) {
            $templateVars['error'] = $e->getMessage();
        }
        
        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }
    
        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listorder.tpl');
    }

    public function listBlocks($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'blocks' => [],
            'error' => null,
        ];
    
        $hasFilters = isset($_GET['status']) || isset($_GET['date_type']) || isset($_GET['date_to']);
    
        if ($hasFilters) {
            try {
                $apiToken = Capsule::table('tbladdonmodules')
                    ->where('module', 'nameblock')
                    ->where('setting', 'apiToken')
                    ->value('value');
    
                $blocksAPI = new \Blocks($apiToken);
    
                $status = $_GET['status'] ?? null;
                $dateType = $_GET['date_type'] ?? null;
                $dateTo = $_GET['date_to'] ?? null;
    
                $response = $blocksAPI->getAllBlocks($status, $dateType, $dateTo);
    
                if (isset($response['data']) && is_array($response['data'])) {
                    $templateVars['blocks'] = $response['data'];
                } else {
                    $templateVars['error'] = "No blocks found or invalid response format.";
                }
            } catch (\Exception $e) {
                $templateVars['error'] = $e->getMessage();
            }
        }
    
        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }
    
        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listblocks.tpl');
    }

    public function listRegistrants($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'registrants' => [],
            'error' => null,
        ];

        try {
            $apiToken = Capsule::table('tbladdonmodules')
                ->where('module', 'nameblock')
                ->where('setting', 'apiToken')
                ->value('value');

            $registrantsAPI = new \Registrants($apiToken);

            $response = $registrantsAPI->getAllRegistrants();

            if (isset($response['data']) && is_array($response['data'])) {
                $templateVars['registrants'] = $response['data'];
            } else {
                $templateVars['error'] = "No registrants found or invalid response format.";
            }
        } catch (\Exception $e) {
            $templateVars['error'] = $e->getMessage();
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listregistrants.tpl');
    }

    public function viewRegistrant($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'registrant' => null,
            'registrant_id' => null,
            'error' => null,
        ];

        if (!empty($_GET['registrant_id'])) {
            $templateVars['registrant_id'] = $_GET['registrant_id'];

            try {
                $apiToken = Capsule::table('tbladdonmodules')
                    ->where('module', 'nameblock')
                    ->where('setting', 'apiToken')
                    ->value('value');

                $registrantsAPI = new \Registrants($apiToken);

                $response = $registrantsAPI->getRegistrantById($templateVars['registrant_id']);

                if (isset($response['data']) && is_array($response['data'])) {
                    $templateVars['registrant'] = $response['data'];
                } else {
                    $templateVars['error'] = "Registrant not found or invalid response format.";
                }
            } catch (\Exception $e) {
                $templateVars['error'] = $e->getMessage();
            }
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/viewregistrant.tpl');
    }
    
    public function createRegistrant($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty(); // WHMCS-specific Smarty instance
        $templateVars = [
            'modulelink' => $modulelink,
            'error' => null,
            'success' => null,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $apiToken = Capsule::table('tbladdonmodules')
                    ->where('module', 'nameblock')
                    ->where('setting', 'apiToken')
                    ->value('value');

                $registrantsAPI = new \Registrants($apiToken);

                // Collect POST data
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'organization' => $_POST['organization'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'street' => $_POST['street'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'postal_code' => $_POST['postal_code'] ?? '',
                    'country_code' => $_POST['country_code'] ?? '',
                ];

                // Validate required fields
                foreach ($data as $key => $value) {
                    if (empty($value)) {
                        throw new \Exception("The field {$key} is required.");
                    }
                }

                $response = $registrantsAPI->createRegistrant($data);

                if (isset($response['status']) && $response['status'] === 200) {
                    $templateVars['success'] = "Registrant created successfully!";
                } else {
                    $templateVars['error'] = $response['message'] ?? "Failed to create registrant.";
                }
            } catch (\Exception $e) {
                $templateVars['error'] = $e->getMessage();
            }
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/createregistrant.tpl');
    }

    public function listProducts($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'products' => [],
            'error' => null,
        ];

        try {
            $apiToken = Capsule::table('tbladdonmodules')
                ->where('module', 'nameblock')
                ->where('setting', 'apiToken')
                ->value('value');

            $productsAPI = new \Products($apiToken);

            $responseAbuseShield = $productsAPI->getAllProducts('abuse_shield');
            $responseBrandLock = $productsAPI->getAllProducts('brand_lock');

            if (isset($responseAbuseShield['data']) && is_array($responseAbuseShield['data'])) {
                $templateVars['products'] = array_merge($templateVars['products'], $responseAbuseShield['data']);
            }

            if (isset($responseBrandLock['data']) && is_array($responseBrandLock['data'])) {
                $templateVars['products'] = array_merge($templateVars['products'], $responseBrandLock['data']);
            }

            if (empty($templateVars['products'])) {
                $templateVars['error'] = "No products found.";
            }
        } catch (\Exception $e) {
            $templateVars['error'] = $e->getMessage();
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listproducts.tpl');
    }
}

