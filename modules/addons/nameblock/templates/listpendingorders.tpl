<h2 style="text-align: center; font-family: Arial, sans-serif; color: #333;">Pending Nameblock Orders</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center;">Error: {$error}</div>
{else}
    {if $pendingOrders|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Order ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Domain</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">User ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Created At</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$pendingOrders item=order}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.order_id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.domain}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.user_id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.created_at}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <form method="post" action="{$modulelink}&action=processPendingOrder">
                            <input type="hidden" name="order_id" value="{$order.id}">
                            <button type="submit" style="background-color: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">Retry</button>
                        </form>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No pending orders found.</p>
    {/if}
{/if}
