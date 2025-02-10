<?php

use WHMCS\Database\Capsule;

require_once __DIR__ . '/../../../init.php';

$pendingOrders = Capsule::table('mod_nameblock_pending_orders')
    ->where('status', 'pending')
    ->where('process_after', '<=', date('Y-m-d H:i:s'))
    ->get();

foreach ($pendingOrders as $order) {
    try {
        $apiToken = Capsule::table('tbladdonmodules')
            ->where('module', 'nameblock')
            ->where('setting', 'apiToken')
            ->value('value');

        if (!$apiToken) {
            throw new \Exception("API Token for Nameblock is not set.");
        }

        $ordersAPI = new \Orders($apiToken);

        $orderData = [
            'product_id' => $order->product_id,
            'user_id' => $order->user_id,
        ];

        $response = $ordersAPI->createOrder($orderData);

        if ($response && $response['status'] === 'ok') {
            Capsule::table('mod_nameblock_pending_orders')
                ->where('id', $order->id)
                ->update(['status' => 'completed']);
        } else {
            throw new \Exception("Failed to create order via Nameblock API: " . ($response['message'] ?? 'Unknown error.'));
        }
    } catch (\Exception $e) {
         Capsule::table('mod_nameblock_pending_orders')
            ->where('id', $order->id)
            ->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
    }
}
