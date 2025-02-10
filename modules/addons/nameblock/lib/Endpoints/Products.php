<?php

require_once __DIR__ . '/../NameblockAPI.php';

class Products extends NameblockAPI {

    /**
     * Fetch all products with an optional type filter.
     *
     * @return array The API response containing the list of products.
     * @throws Exception If the API request fails.
     */
    public function getAllProducts()
    {
        return $this->makeRequest('/products');
    }

}
