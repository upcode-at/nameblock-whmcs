<h2 style="text-align: center; font-family: Arial, sans-serif;">Synchronize Nameblock Products</h2>

<form method="post" action="{$modulelink}&action=syncProducts" style="max-width: 1200px; margin: 20px auto; background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <!-- Success Message -->
    {if $success}
        <div style="color: green; font-weight: bold; text-align: center; margin-bottom: 20px;">{$success}</div>
    {/if}

    <!-- Error Message -->
    {if $error}
        <div style="color: red; font-weight: bold; text-align: center; margin-bottom: 20px;">{$error}</div>
    {/if}

    <h3>Product Group</h3>
    <label for="group_name">Group Name:</label>
    <input type="text" id="group_name" name="group_name" placeholder="NameBlock" value="NameBlock" style="width: 100%; padding: 10px; margin-bottom: 15px;">

    <label for="group_headline">Group Headline:</label>
    <input type="text" id="group_headline" name="group_headline" placeholder="Recommended Nameblock Products" value="Recommended Nameblock Products" style="width: 100%; padding: 10px; margin-bottom: 15px;">

    <label for="group_tagline">Group Tagline:</label>
    <input type="text" id="group_tagline" name="group_tagline" placeholder="Choose from our exclusive Nameblock services" value="Choose from our exclusive Nameblock services" style="width: 100%; padding: 10px; margin-bottom: 15px;">

    <h3>Products</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        {foreach from=$products item=product}
            <div style="flex: 1 1 calc(50% - 20px); border: 1px solid #ddd; padding: 15px; border-radius: 4px; background: #fff; box-sizing: border-box;">
                <label for="product_name_{$product.id}">Product Name:</label>
                <input type="text" id="product_name_{$product.id}" name="product_name_{$product.id}" value="{$product.name}" style="width: 100%; padding: 10px; margin-bottom: 10px;">

                <label for="product_description_{$product.id}">Description:</label>
                <textarea id="product_description_{$product.id}" name="product_description_{$product.id}" style="width: 100%; padding: 10px; margin-bottom: 10px;">{$product.description}</textarea>

                <label for="product_price_{$product.id}">Annual Price:</label>
                <input type="number" id="product_price_{$product.id}" name="product_price_{$product.id}" value="{$product.price.0.create}" style="width: 100%; padding: 10px;">
            </div>
        {/foreach}
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Synchronize Products</button>
    </div>
</form>
