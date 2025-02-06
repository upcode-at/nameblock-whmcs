<div menuitemname="NameBlock Orders" class="panel panel-default panel-accent-green" id="nameblockPanel">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fas fa-cubes"></i>&nbsp; NameBlock Orders
        </h3>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Domain</th>
                    <th>Product Name</th> <!-- Add Product Name column -->
                    <th>Status <i class="fa fa-info-circle" title="Pending: Order is being processed. Completed: Order has been processed. Failed: Order processing failed." style="cursor: pointer;"></i></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$pendingOrders item=order}
                <tr>
                    <td>{$order->order_id}</td>
                    <td>
                        <a href="#" class="domain-link" data-domain="{$order->domain}" data-blocks='{$blocksData[$order->domain]|json_encode}'>{$order->domain}</a>
                    </td>
                    <td>{$order->product_name}</td> <!-- Display Product Name -->
                    <td>{$order->status}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
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

