<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">Get Block List - Variants</h2>

<form method="post" action="{$modulelink}&action=getBlockList" style="max-width: 800px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <input type="hidden" name="module" value="nameblock">
    <input type="hidden" name="action" value="getBlockList">
    <div style="display: flex; flex-wrap: wrap; gap: 15px;">
        <div style="flex: 1; min-width: 200px;">
            <label for="label" style="font-weight: bold; display: block; margin-bottom: 5px;">Label:</label>
            <input type="text" name="label" id="label" value="{$smarty.post.label|escape}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label for="tld" style="font-weight: bold; display: block; margin-bottom: 5px;">TLD:</label>
            <input type="text" name="tld" id="tld" value="{$smarty.post.tld|escape}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label for="product_id" style="font-weight: bold; display: block; margin-bottom: 5px;">Product ID:</label>
            <input type="text" name="product_id" id="product_id" value="{$smarty.post.product_id|escape}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Get Block List
        </button>
    </div>
</form>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center;">Error: {$error}</div>
{else}
    {if $variants|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Variant ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Domain Name</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$variants item=variant}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$variant.id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$variant.domain_name}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No variants found. Use filters to refine your search.</p>
    {/if}
{/if}
