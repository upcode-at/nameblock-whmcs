<?php

namespace WHMCS\Module\Addon\Nameblock\Admin;

use WHMCS\Database\Capsule;
use WHMCS\Smarty;
require_once __DIR__ . '/../Endpoints/Products.php';
require_once __DIR__ . '/../Endpoints/TLDs.php';


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
        $modulelink = $vars['modulelink'];

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
        <li><a href="{$modulelink}&action=listTLDs">List TLDs</a></li>
        <li><a href="{$modulelink}&action=viewLogs">View Logs</a></li>
        <li><a href="{$modulelink}&action=syncProducts">Sync Nameblock Products</a></li>
        <li><a href="{$modulelink}&action=dashboard">Dashboard</a></li>
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
            'success' => null,
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $apiToken = Capsule::table('tbladdonmodules')
                    ->where('module', 'nameblock')
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
    
                if (isset($response['status']) && $response['status'] === 'ok') {
                    $templateVars['success'] = "Order successfully created! Order ID: {$response['data']['id']}";
                } else {
                    $templateVars['error'] = $response['message'] ?? 'Failed to create the order.';
                }
    
                $templateVars['response'] = $response;
            } catch (\Exception $e) {
                $templateVars['error'] = "Error: " . $e->getMessage();
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

    public function listTLDs($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();
        $templateVars = [
            'modulelink' => $modulelink,
            'tlds' => [],
            'error' => null,
        ];

        try {
            $apiToken = Capsule::table('tbladdonmodules')
                ->where('module', 'nameblock')
                ->where('setting', 'apiToken')
                ->value('value');

            $tldsAPI = new \TLDs($apiToken);

            $response = $tldsAPI->getAllTLDs();

            if (isset($response['data']) && is_array($response['data'])) {
                $templateVars['tlds'] = $response['data'];
            } else {
                $templateVars['error'] = "No TLDs found or invalid response format.";
            }
        } catch (\Exception $e) {
            $templateVars['error'] = $e->getMessage();
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listtlds.tpl');
    }

    public function syncProducts($vars)
    {
        $modulelink = $vars['modulelink'];
    
        try {
            $productGroup = Capsule::table('tblproductgroups')
                ->where('name', 'NameBlock')
                ->first();
    
            $productGroupId = null;
    
            if (!$productGroup) {
                $productGroupId = Capsule::table('tblproductgroups')->insertGetId([
                    'name' => 'NameBlock',
                    'slug' => 'nameblock',
                    'headline' => 'Recommended Nameblock Products',
                    'tagline' => 'Choose from our exclusive Nameblock services',
                    'orderfrmtpl' => 'default',
                    'disabled' => 0,
                ]);
            } else {
                $productGroupId = $productGroup->id;
            }
    
            $apiToken = Capsule::table('tbladdonmodules')
                ->where('module', 'nameblock')
                ->where('setting', 'apiToken')
                ->value('value');
    
            $productsAPI = new \Products($apiToken);
            $response = $productsAPI->getAllProducts();
    
            if (!isset($response['data']) || !is_array($response['data'])) {
                return "Invalid response from the Nameblock API or no products found.";
            }
    
            foreach ($response['data'] as $product) {
                $productData = [
                    'type' => 'other',
                    'gid' => $productGroupId,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'hidden' => 0,
                    'tax' => 1,
                    'showdomainoptions' => 1,
                    'paytype' => 'recurring',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
    
                $existingProduct = Capsule::table('tblproducts')
                    ->where('name', $product['name'])
                    ->first();
    
                if ($existingProduct) {
                    Capsule::table('tblproducts')
                        ->where('id', $existingProduct->id)
                        ->update($productData);
    
                    $productId = $existingProduct->id;
                } else {
                    $productId = Capsule::table('tblproducts')->insertGetId($productData);
                }
    
                if (isset($product['price'])) {
                    foreach ($product['price'] as $price) {
                        $existingPricing = Capsule::table('tblpricing')
                            ->where('type', 'product')
                            ->where('relid', $productId)
                            ->where('currency', 1)
                            ->first();
    
                        $pricingData = [
                            'type' => 'product',
                            'relid' => $productId,
                            'currency' => 1,
                            'monthly' => 0,
                            'quarterly' => 0,
                            'semiannually' => 0,
                            'annually' => $price['create'],
                            'biennially' => $price['create'] * 2,
                            'triennially' => $price['create'] * 3,
                        ];
    
                        if ($existingPricing) {
                            Capsule::table('tblpricing')
                                ->where('id', $existingPricing->id)
                                ->update($pricingData);
                        } else {
                            Capsule::table('tblpricing')->insert($pricingData);
                        }
                    }
                }
            }
    
            return "Products synchronized successfully.";
        } catch (\Exception $e) {
            return "Error during synchronization: " . $e->getMessage();
        }
    }

    public function dashboard($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new \Smarty();

        $totalOrders = Capsule::table('mod_nameblock_pending_orders')->count();
        $pendingOrders = Capsule::table('mod_nameblock_pending_orders')->where('status', 'pending')->count();
        $completedOrders = Capsule::table('mod_nameblock_pending_orders')->where('status', 'completed')->count();
        $failedOrders = Capsule::table('mod_nameblock_pending_orders')->where('status', 'failed')->count();

        $smarty->assign('modulelink', $modulelink);
        $smarty->assign('totalOrders', $totalOrders);
        $smarty->assign('pendingOrders', $pendingOrders);
        $smarty->assign('completedOrders', $completedOrders);
        $smarty->assign('failedOrders', $failedOrders);

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/dashboard.tpl');
    }

    public function listPendingOrders($vars)
    {
        $modulelink = $vars['modulelink'];
        $smarty = new Smarty();

        $templateVars = [
            'modulelink' => $modulelink,
            'pendingOrders' => [],
            'error' => null,
        ];

        try {
            // Fetch pending orders from the database
            $pendingOrders = Capsule::table('mod_nameblock_pending_orders')
                ->where('status', 'pending')
                ->get();

            if ($pendingOrders->isEmpty()) {
                $templateVars['error'] = "No pending orders found.";
            } else {
                $templateVars['pendingOrders'] = $pendingOrders;
            }
        } catch (\Exception $e) {
            $templateVars['error'] = "Error fetching pending orders: " . $e->getMessage();
        }

        foreach ($templateVars as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch(ROOTDIR . '/modules/addons/nameblock/templates/listpendingorders.tpl');
    }
}

