<?php

require_once __DIR__ . '/../NameblockAPI.php';

class TLDs extends NameblockAPI {

    public function getAllTLDs() {
        return $this->makeRequest('/tlds');
    }

    public function getTLDInfo($tld) {
        return $this->makeRequest("/tld/{$tld}");
    }
}
