<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">List of Blocks</h2>

<form method="get" action="{$modulelink}&action=listBlocks" style="max-width: 800px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <input type="hidden" name="module" value="nameblock">
    <input type="hidden" name="action" value="listBlocks">
    <div style="display: flex; flex-wrap: wrap; gap: 15px;">
        <div style="flex: 1; min-width: 200px;">
            <label for="status" style="font-weight: bold; display: block; margin-bottom: 5px;">Status:</label>
            <select name="status" id="status" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="">All</option>
                <option value="ok" {if $smarty.get.status == 'ok'}selected{/if}>OK</option>
                <option value="expired" {if $smarty.get.status == 'expired'}selected{/if}>Expired</option>
                <option value="pending_delete" {if $smarty.get.status == 'pending_delete'}selected{/if}>Pending Delete</option>
                <option value="pending_purge" {if $smarty.get.status == 'pending_purge'}selected{/if}>Pending Purge</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label for="date_type" style="font-weight: bold; display: block; margin-bottom: 5px;">Date Type:</label>
            <select name="date_type" id="date_type" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="">All</option>
                <option value="create_date" {if $smarty.get.date_type == 'create_date'}selected{/if}>Create Date</option>
                <option value="update_date" {if $smarty.get.date_type == 'update_date'}selected{/if}>Update Date</option>
                <option value="expire_date" {if $smarty.get.date_type == 'expire_date'}selected{/if}>Expire Date</option>
                <option value="redemption_date" {if $smarty.get.date_type == 'redemption_date'}selected{/if}>Redemption Date</option>
                <option value="purge_date" {if $smarty.get.date_type == 'purge_date'}selected{/if}>Purge Date</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 200px;">
            <label for="date_to" style="font-weight: bold; display: block; margin-bottom: 5px;">Date To:</label>
            <input type="date" name="date_to" id="date_to" value="{$smarty.get.date_to|escape}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Apply Filters
        </button>
    </div>
</form>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center;">Error: {$error}</div>
{else}
    {if $blocks|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Block ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Label</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Domain</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">TLD</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Create Date</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Expire Date</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$blocks item=block}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.block_id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.block_label}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.domain_name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.tld}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; color: {if $block.status == 'ok'}green{elseif $block.status == 'expired'}red{else}orange{/if};">
                        {$block.status}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.create_date}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$block.expire_date|default:'N/A'}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No blocks found. Use filters to refine your search.</p>
    {/if}
{/if}
