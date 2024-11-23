<?php

require_once __DIR__ . '/../NameblockAPI.php';

class TLDs extends NameblockAPI {

    /**
     * Fetch all TLDs.
     *
     * @return array The API response containing the list of TLDs.
     * @throws Exception If the API request fails.
     */
    public function getAllTLDs()
    {
        return $this->makeRequest('/tlds');
    }
}
