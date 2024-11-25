<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">View Registrant</h2>

<form method="get" action="{$modulelink}&action=viewRegistrant" style="max-width: 400px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <input type="hidden" name="module" value="nameblock">
    <input type="hidden" name="action" value="viewRegistrant">
    <label for="registrant_id" style="font-weight: bold; display: block; margin-bottom: 5px;">Registrant ID:</label>
    <input type="text" name="registrant_id" id="registrant_id" value="{$registrant_id|default:''}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 15px;">
    <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Fetch Registrant
    </button>
</form>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center; margin-top: 20px;">Error: {$error}</div>
{elseif $registrant}
    <div style="max-width: 800px; margin: 20px auto; font-family: Arial, sans-serif;">
        <h3>Registrant Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Registrant ID</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.registrant_id}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Name</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.name}</td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Organization</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.organization}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Email</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.email}</td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Phone</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.phone}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Address</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.street}, {$registrant.city}, {$registrant.postal_code}, {$registrant.country_code}</td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Created At</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.create_date}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Updated At</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$registrant.update_date}</td>
            </tr>
        </table>
    </div>
{else}
    <p style="text-align: center; color: #555;">Enter a Registrant ID to view details.</p>
{/if}
