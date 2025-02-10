<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">List of Orders</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center; font-family: Arial, sans-serif; margin-bottom: 20px;">
        Error: {$error}
    </div>
{else}
    {if $orders|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Order ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Command</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Promotion</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Registrant ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Created At</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Updated At</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Order Line Details</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$orders item=order}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; color: {if $order.status == 'error'}red{else}green{/if};">
                        {$order.status}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.command}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.promotion|default:'N/A'}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.registrant_id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.create_date}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$order.update_date}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            {foreach from=$order.order_line item=line}
                            <li style="margin-bottom: 5px; padding: 5px; background: #f1f1f1; border: 1px solid #ddd; border-radius: 5px;">
                                <strong>Product ID:</strong> {$line.product_id}<br>
                                <strong>Quantity:</strong> {$line.quantity}<br>
                                <strong>Term:</strong> {$line.term}<br>
                                <strong>Price:</strong> {$line.price}<br>
                                <strong>Discount:</strong> {$line.discount}<br>
                                <strong>Domain:</strong> {$line.payload.domain_name}<br>
                                <strong>TLD:</strong> {$line.payload.tld}<br>
                                <strong>Block Label:</strong> {$line.payload.block_label}
                            </li>
                            {/foreach}
                        </ul>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555; font-family: Arial, sans-serif;">No orders found.</p>
    {/if}
{/if}
