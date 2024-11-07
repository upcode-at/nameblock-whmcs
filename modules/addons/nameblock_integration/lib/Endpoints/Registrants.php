<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Registrants extends NameblockAPI {

    public function getAllRegistrants() {
        return $this->makeRequest('/registrants');
    }

    public function getRegistrantById($id) {
        return $this->makeRequest("/registrant/{$id}");
    }
}
