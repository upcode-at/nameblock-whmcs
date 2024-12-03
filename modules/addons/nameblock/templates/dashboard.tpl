<h2 style="text-align: center; font-family: Arial, sans-serif; color: #333;">Nameblock Orders Dashboard</h2>

<div style="max-width: 800px; margin: 20px auto; font-family: Arial, sans-serif; background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h3 style="text-align: center; color: #007bff;">Overview</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #007bff; color: #fff;">
                <th style="padding: 10px; text-align: left;">Metric</th>
                <th style="padding: 10px; text-align: right;">Count</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd;">Total Orders</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #ddd;">{$totalOrders}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">Pending Orders</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #ddd; color: orange;">{$pendingOrders}</td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd;">Completed Orders</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #ddd; color: green;">{$completedOrders}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">Failed Orders</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #ddd; color: red;">{$failedOrders}</td>
            </tr>
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
        <a href="{$modulelink}&action=listPendingOrders" style="text-decoration: none; background: #007bff; color: #fff; padding: 10px 20px; border-radius: 5px;">View Pending Orders</a>
    </div>
</div>
