<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Orders extends NameblockAPI {

    public function getAllOrders() {
        return $this->makeRequest('/orders');
    }

    public function getOrderById($id) {
        return $this->makeRequest("/order/{$id}");
    }

    /**
     * Create a new order.
     *
     * @param string $promotion The promotion code (optional).
     * @param string $command The command type, e.g., "create".
     * @param int $registrantId The ID of the registrant placing the order.
     * @param array $orderLines An array of order lines (product details).
     * @return array The API response.
     * @throws Exception If the API request fails.
     */
    public function createOrder($promotion, $command, $registrantId, $orderLines) {
        $endpoint = '/order';
        $requestBody = [
            'promotion' => $promotion,
            'command' => $command,
            'registrant_id' => $registrantId,
            'order_line' => $orderLines,
        ];

        return $this->makeRequest($endpoint, $requestBody, 'POST');
    }
}
