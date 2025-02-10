<?php

class NameblockAPI {

    protected $apiToken;
    protected $apiUrl = 'https://api.nameblock.com';

    public function __construct($token) {
        $this->apiToken = $token;
    }

    protected function makeRequest($endpoint, $data = [], $method = 'GET', $query='') {
        $url = $this->apiUrl . $endpoint . '?api_key=' . $this->apiToken . $query;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
