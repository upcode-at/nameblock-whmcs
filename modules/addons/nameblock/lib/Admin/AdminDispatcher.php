<?php

namespace WHMCS\Module\Addon\Nameblock\Admin;

/**
 * Admin Area Dispatch Handler
 */
class AdminDispatcher {

    /**
     * Dispatch request to the appropriate controller action.
     *
     * @param string $action The requested action
     * @param array $parameters Parameters for the action
     *
     * @return string
     */
    public function dispatch($action, $parameters)
    {
        if (!$action) {
            $action = 'index'; // Default action
        }

        $controller = new Controller();

        if (is_callable([$controller, $action])) {
            return $controller->$action($parameters);
        }

        return '<p>Invalid action requested. Please go back and try again.</p>';
    }
}
