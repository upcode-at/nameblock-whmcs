<?php

class NameblockAPI {

    private $apiToken;
    private $apiUrl = 'https://api.nameblock.com/v1';

    public function __construct($token) {
        $this->apiToken = $token;
    }

    // Check if a domain is blocked
    public function checkDomainBlockStatus($domain) {
        $endpoint = $this->apiUrl . "/domain/check";
        $data = ['domain' => $domain];
        
        $response = $this->makeRequest($endpoint, $data);
        
        return $response;
    }

    // Get account status
    public function getAccountStatus() {
        $endpoint = $this->apiUrl . "/account/status";
        
        return $this->makeRequest($endpoint);
    }

    private function makeRequest($url, $data = []) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
