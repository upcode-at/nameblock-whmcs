<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">List of Products</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center; margin-bottom: 20px;">Error: {$error}</div>
{else}
    {if $products|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Product ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Type</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Description</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Price (Create)</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Price (Renew)</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Price (Transfer)</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Discount</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$products item=product}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$product.id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$product.name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$product.type}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$product.description}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {if $product.price|@count > 0}
                            {$product.price[0].create}
                        {else}
                            N/A
                        {/if}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {if $product.price|@count > 0}
                            {$product.price[0].renew}
                        {else}
                            N/A
                        {/if}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {if $product.price|@count > 0}
                            {$product.price[0].transfer}
                        {else}
                            N/A
                        {/if}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {if $product.discount|@count > 0}
                            {$product.discount[0].type}: {$product.discount[0].create}%
                        {else}
                            None
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No products found.</p>
    {/if}
{/if}
