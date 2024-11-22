<h2>Create New Order</h2>

<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        font-family: Arial, sans-serif;
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .form-container label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-container input:focus {
        border-color: #007bff;
        outline: none;
    }

    .form-container button {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #218838;
    }

    .form-container .note {
        font-size: 12px;
        color: #777;
        margin-top: -10px;
        margin-bottom: 15px;
    }
</style>

<div class="form-container">
    <h2>Create New Order</h2>
    <form method="post" action="addonmodules.php?module=nameblock&action=createOrder">
        <label for="promotion">Promotion Code (optional):</label>
        <input type="text" id="promotion" name="promotion" placeholder="Enter promotion code" value="SUMMER2024">

        <label for="registrant_id">Registrant ID:</label>
        <input type="number" id="registrant_id" name="registrant_id" placeholder="Enter registrant ID" required>

        <label for="block_label">Block Label:</label>
        <input type="text" id="block_label" name="block_label" placeholder="Enter block label" required>

        <label for="domain_name">Domain Name:</label>
        <input type="text" id="domain_name" name="domain_name" placeholder="Enter domain name" required>

        <label for="tld">TLD:</label>
        <input type="text" id="tld" name="tld" placeholder="Enter TLD (e.g., com, net)" required>

        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" value="as-01" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>

        <label for="term">Term:</label>
        <input type="number" id="term" name="term" value="1" min="1" required>

        <button type="submit" class="btn btn-success">Create Order</button>
    </form>
</div>
