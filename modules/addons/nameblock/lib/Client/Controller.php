<?php

namespace WHMCS\Module\Addon\Nameblock\Client;

use WHMCS\Database\Capsule;
use Smarty;

require_once __DIR__ . '/../Endpoints/Products.php';
require_once __DIR__ . '/../Endpoints/TLDs.php';
require_once __DIR__ . '/../Endpoints/Blocks.php';
require_once __DIR__ . '/../Endpoints/Orders.php';
require_once __DIR__ . '/../Endpoints/Registrants.php';

/**
 * Client Controller for Nameblock Module
 * Handles client-side views and API data fetching.
 */
class Controller
{
    public function index($vars)
    {
        $modulelink = $vars['modulelink'];
        return <<<HTML
        <h2>Welcome to Nameblock Client Module</h2>
        <p><a href="{$modulelink}&action=getBlockList">View Block List</a></p>
        HTML;
    }

}
