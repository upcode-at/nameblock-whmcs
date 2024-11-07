<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Products extends NameblockAPI {

    public function getAllProducts($type = null) {
        $params = [];
        if ($type) {
            $params['type'] = $type;
        }

        return $this->makeRequest('/products?' . http_build_query($params));
    }

    public function getProductById($id) {
        return $this->makeRequest("/product/{$id}");
    }
}
