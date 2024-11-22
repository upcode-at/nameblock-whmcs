<?php

namespace WHMCS\Module\Addon\Nameblock\Admin;

use WHMCS\Database\Capsule;
use WHMCS\Smarty;


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
}

