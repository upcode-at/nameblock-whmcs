<?php

namespace WHMCS\Module\Addon\Nameblock\Client;

use WHMCS\User\Client;

/**
 * Client Dispatcher for Nameblock Module
 * Handles routing to specific client controller actions.
 */
class ClientDispatcher
{
    protected $controller;

    public function __construct()
    {
        $this->controller = new Controller();
    }

    public function dispatch($action, $vars)
    {
        $controller = new Controller();
    
        switch ($action) {
            default:
                return "<h2>Welcome to Nameblock</h2>";
        }
    }
}
