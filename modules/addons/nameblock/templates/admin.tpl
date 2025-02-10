<h2>NameBlock - Prevent DNS Abuse Data Overview</h2>

<!-- Registrants Section -->
<h3>Registrants</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Organization</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$registrants item=registrant}
        <tr>
            <td>{$registrant.registrant_id}</td>
            <td>{$registrant.name}</td>
            <td>{$registrant.email}</td>
            <td>{$registrant.organization}</td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- Blocks Section -->
<h3>Blocks</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Block ID</th>
            <th>Label</th>
            <th>Domain Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$blocks item=block}
        <tr>
            <td>{$block.block_id}</td>
            <td>{$block.block_label}</td>
            <td>{$block.domain_name}</td>
            <td>{$block.status}</td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- Products Section -->
<h3>Products</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$products item=product}
        <tr>
            <td>{$product.id}</td>
            <td>{$product.name}</td>
            <td>{$product.category}</td>
            <td>{$product.type}</td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- Orders Section -->
<h3>Orders</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Status</th>
            <th>Creation Date</th>
            <th>Registrant ID</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$orders item=order}
        <tr>
            <td>{$order.id}</td>
            <td>{$order.status}</td>
            <td>{$order.create_date}</td>
            <td>{$order.registrant_id}</td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- TLDs Section -->
<h3>TLDs</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>TLD ID</th>
            <th>TLD</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$tlds item=tld}
        <tr>
            <td>{$tld.id}</td>
            <td>{$tld.tld}</td>
            <td>{$tld.status}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
