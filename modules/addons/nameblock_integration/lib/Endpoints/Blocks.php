<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Blocks extends NameblockAPI {

    public function getAllBlocks($status = null) {
        $params = [];
        if ($status) {
            $params['status'] = $status;
        }
        
        return $this->makeRequest('/blocks?' . http_build_query($params));
    }

    public function checkDomainBlockStatus($domain) {
        return $this->makeRequest('/block?label=' . urlencode($domain));
    }
}
