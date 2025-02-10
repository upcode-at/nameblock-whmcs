<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">List of Registrants</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center;">Error: {$error}</div>
{else}
    {if $registrants|@count > 0}
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Registrant ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Organization</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Email</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Phone</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Address</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Created At</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$registrants item=registrant}
                <tr style="background-color: {cycle values="#f9f9f9,#fff"};">
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.registrant_id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.organization}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.email}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.phone}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {$registrant.street}, {$registrant.city}, {$registrant.postal_code}, {$registrant.country_code}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.create_date}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p style="text-align: center; color: #555;">No registrants found.</p>
    {/if}
{/if}
