<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Orders extends NameblockAPI {

    public function getAllOrders() {
        return $this->makeRequest('/orders');
    }

    public function getOrderById($id) {
        return $this->makeRequest("/order/{$id}");
    }

    public function createOrder($data) {
        return $this->makeRequest('/order', $data, 'POST');
    }
}
