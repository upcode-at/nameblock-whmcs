<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Registrants extends NameblockAPI {

    /**
     * Fetch all registrants.
     *
     * @return array The API response containing the list of registrants.
     * @throws Exception If the API request fails.
     */
    public function getAllRegistrants()
    {
        return $this->makeRequest('/registrants');
    }

    /**
     * Fetch a specific registrant by ID.
     *
     * @param int $registrantId The ID of the registrant.
     * @return array The API response containing the registrant data.
     * @throws Exception If the API request fails.
     */
    public function getRegistrantById($registrantId)
    {
        return $this->makeRequest('/registrant/' . $registrantId);
    }

    /**
     * Create a new registrant.
     *
     * @param array $data The registrant data.
     * @return array The API response.
     * @throws Exception If the API request fails.
     */
    public function createRegistrant($data)
    {
        return $this->makeRequest('/registrant', $data, 'POST');
    }
}
