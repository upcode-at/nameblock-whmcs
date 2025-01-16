<div style="max-width: 800px; margin: 20px auto; font-family: Arial, sans-serif; background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h3 style="text-align: center; color: #007bff;">NameBlock Orders</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #007bff; color: #fff;">
                <th style="padding: 10px; text-align: left;">Order ID</th>
                <th style="padding: 10px; text-align: left;">Domain</th>
                <th style="padding: 10px; text-align: left;">Status</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$pendingOrders item=order}
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; border: 1px solid #ddd;">{$order->order_id}</td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <a href="#" class="domain-link" data-domain="{$order->domain}" data-blocks='{$blocksData[$order->domain]|json_encode}'>{$order->domain}</a>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">{$order->status}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>

<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; max-height: 80%; overflow-y: auto;">
        <h3 style="text-align: center; color: #007bff;">Blocks for <span id="overlay-domain"></span></h3>
        <div id="overlay-content"></div>
        <button onclick="document.getElementById('overlay').style.display='none'" style="display: block; margin: 20px auto; padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px;">Close</button>
    </div>
</div>

<script>
document.querySelectorAll('.domain-link').forEach(function(link) {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        var domain = this.getAttribute('data-domain');
        var blocks = JSON.parse(this.getAttribute('data-blocks'));
        document.getElementById('overlay-domain').innerText = domain;
        document.getElementById('overlay').style.display = 'block';

        var content = '<ul>';
        if (blocks.length > 0) {
            blocks.forEach(function(block) {
                content += '<li>' + block.domain_name + '</li>';
            });
        } else {
            content += '<li>No blocks found.</li>';
        }
        content += '</ul>';
        document.getElementById('overlay-content').innerHTML = content;
    });
});
</script>

