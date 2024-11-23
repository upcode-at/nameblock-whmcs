<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">List of TLDs</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center; margin-bottom: 20px;">Error: {$error}</div>
{else}
    {if $tlds|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">TLD</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Registry Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Registry Tag</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Registry Account Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Backend Name</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$tlds item=tld}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$tld.tld}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; color: {if $tld.status == 'active'}green{else}red{/if};">
                        {$tld.status}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$tld.registry_name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$tld.registry_tag}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$tld.registry_account_status}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$tld.backend_name}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No TLDs found.</p>
    {/if}
{/if}
