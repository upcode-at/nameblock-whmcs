<h2 style="text-align: center;">My Block List</h2>

<table style="width: 80%; margin: 20px auto; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #007bff; color: #fff;">
            <th style="padding: 10px; border: 1px solid #ddd;">Variant ID</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Domain Name</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$output.variants item=variant}
        <tr style="background-color: {cycle values='#f9f9f9,#fff'};">
            <td style="padding: 10px; border: 1px solid #ddd;">{$variant.id}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{$variant.domain_name}</td>
        </tr>
        {/foreach}
    </tbody>
</table>