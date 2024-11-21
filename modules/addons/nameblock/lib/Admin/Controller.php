<?php

namespace WHMCS\Module\Addon\Nameblock\Admin;

use WHMCS\Database\Capsule;

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
        $version = $vars['version'];
        $LANG = $vars['_lang'];

        // Fetch data from Nameblock API
        $apiToken = Capsule::table('tbladdonmodules')->where('module', 'nameblock')->value('value');
        $registrantsAPI = new \Registrants($apiToken);
        $registrants = $registrantsAPI->getAllRegistrants();

        // Render output
        $output = '<h2>Nameblock Integration Admin</h2>';
        $output .= '<p>Currently installed version: <strong>' . $version . '</strong></p>';
        $output .= '<h3>Registrants</h3>';
        $output .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Organization</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($registrants as $registrant) {
            $output .= '<tr>
                            <td>' . $registrant['registrant_id'] . '</td>
                            <td>' . $registrant['name'] . '</td>
                            <td>' . $registrant['email'] . '</td>
                            <td>' . $registrant['organization'] . '</td>
                        </tr>';
        }
        $output .= '</tbody></table>';

        return $output;
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
}
