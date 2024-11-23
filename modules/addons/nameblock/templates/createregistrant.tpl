<h2 style="text-align: center; color: #333; font-family: Arial, sans-serif;">Create New Registrant</h2>

{if $error}
    <div style="color: red; font-weight: bold; text-align: center; margin-bottom: 20px;">Error: {$error}</div>
{/if}

{if $success}
    <div style="color: green; font-weight: bold; text-align: center; margin-bottom: 20px;">{$success}</div>
{/if}

<form method="post" action="{$modulelink}" style="max-width: 600px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <div style="margin-bottom: 15px;">
        <label for="name" style="font-weight: bold; display: block; margin-bottom: 5px;">Name:</label>
        <input type="text" name="name" id="name" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="organization" style="font-weight: bold; display: block; margin-bottom: 5px;">Organization:</label>
        <input type="text" name="organization" id="organization" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="email" style="font-weight: bold; display: block; margin-bottom: 5px;">Email:</label>
        <input type="email" name="email" id="email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="phone" style="font-weight: bold; display: block; margin-bottom: 5px;">Phone:</label>
        <input type="text" name="phone" id="phone" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="street" style="font-weight: bold; display: block; margin-bottom: 5px;">Street:</label>
        <input type="text" name="street" id="street" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="city" style="font-weight: bold; display: block; margin-bottom: 5px;">City:</label>
        <input type="text" name="city" id="city" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="postal_code" style="font-weight: bold; display: block; margin-bottom: 5px;">Postal Code:</label>
        <input type="text" name="postal_code" id="postal_code" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="country_code" style="font-weight: bold; display: block; margin-bottom: 5px;">Country Code:</label>
        <input type="text" name="country_code" id="country_code" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
    </div>
    <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Create Registrant
    </button>
</form>
