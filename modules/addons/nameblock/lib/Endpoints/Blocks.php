<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Blocks extends NameblockAPI {

    /**
     * Fetch all blocks with optional query parameters.
     *
     * @param string|null $status The block status (e.g., "ok", "expired").
     * @param string|null $dateType The date type (e.g., "create_date").
     * @param string|null $dateTo The date value (YYYY-MM-DD).
     * @return array The API response containing the list of blocks.
     * @throws Exception If the API request fails.
     */
    public function getAllBlocks($status = null, $dateType = null, $dateTo = null)
    {
        $params = [];
        if ($status) {
            $params['status'] = $status;
        }
        if ($dateType) {
            $params['date_type'] = $dateType;
        }
        if ($dateTo) {
            $params['date_to'] = $dateTo;
        }

        $queryString = http_build_query($params);
        return $this->makeRequest('/blocks?' . $queryString);
    }

    public function checkDomainBlockStatus($domain) {
        return $this->makeRequest('/block?label=' . urlencode($domain));
    }
}
