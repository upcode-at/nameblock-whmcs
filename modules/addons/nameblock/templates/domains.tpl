<h2 style="text-align: center;">My Domains</h2>

<table style="width: 80%; margin: 20px auto; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #007bff; color: white;">
            <th style="padding: 10px; border: 1px solid #ddd;">Domain</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Registrar</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Registration Date</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Expiry Date</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$domains item=domain}
        <tr style="background-color: {cycle values='#f9f9f9,#fff'};">
            <td style="padding: 10px; border: 1px solid #ddd;">{$domain.domain}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{$domain.status}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{$domain.registrar}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{$domain.registrationdate}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{$domain.expirydate}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
