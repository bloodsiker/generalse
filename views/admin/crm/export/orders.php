<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.bootstrap.min.css" rel="stylesheet">

    <style>
        table td, th{
            padding: 3px;
        }
        .btn-group{
            margin: 10px 10px 20px;
        }
        #orders{
            margin-bottom: 50px;
        }
        caption{
            text-align: center;
            font-weight:bold;
            font-size:16px;
            color: #000
        }
        .text-green{
            color: green;
        }
    </style>
</head>
<body>

<div class="btn-group pull-left hidden" role="group" aria-label="...">
    <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;"><span class="glyphicon glyphicon-arrow-left"></span> В кабинет</button>
    <button type="button" class="btn btn-danger" onclick="tableToExcel('orders', 'Orders export')"><span class="glyphicon glyphicon-export"></span> Export</button>
</div>

<div id="orders">
    <table border="1" id="export-orders" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Orders &raquo; <?=(isset($_POST['start'])) ? $_POST['start'] : $_POST['start']?> &mdash; <?=(isset($_POST['end'])) ? $_POST['end'] : $_POST['end']?> <span class="text-green">(<?php if (isset($listExport)) echo count($listExport) ?>)</span></caption>
        <thead>
        <tr>
            <th>Partner</th>
            <th>Partner status</th>
            <th style="text-align: center;">Order Number</th>
            <th>Service Order</th>
            <th>Part Number</th>
            <th>Goods name</th>
            <th style="text-align: center;">Stock name</th>
            <th style="text-align: center;">Quantity</th>
            <th style="text-align: center;">Price</th>
            <th style="text-align: center;">Type</th>
            <th style="text-align: center;">Note</th>
            <th style="text-align: center;">Status</th>
            <th style="text-align: center;">Date order</th>
            <th style="text-align: center;">Date shipped</th>
            <th style="text-align: center;">Date request</th>
            <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                <th style="text-align: center;">Request id</th>
            <?php endif?>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($listExport) && is_array($listExport)):?>
            <?php foreach ($listExport as $export):?>
                <tr>
                    <td><?= $export['site_client_name']?></td>
                    <td><?= $export['site_client_status']?></td>
                    <td style="text-align: center;"><?=$export['order_number']?></td>
                    <td><?= $export['so_number']?></td>
                    <td><?=$export['part_number']?></td>
                    <td><?= $export['goods_name']?></td>
                    <td style="text-align: center;"><?= $export['stock_name']?></td>
                    <td style="text-align: center;"><?=$export['quantity']?></td>
                    <td style="text-align: center;"><?= round($export['price'], 2)?></td>
                    <td style="text-align: center;"><?= $export['type_name']?></td>
                    <td style="text-align: center;"><?= $export['note1']?></td>
                    <td style="text-align: center;">
                        <?= $export['status_name'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= !empty($export['created_on']) ? \Carbon\Carbon::parse($export['created_on'])->format('Y-m-d') : null ?>
                    </td>
                    <td>
                        <?= !empty($export['shipped_on']) ? \Carbon\Carbon::parse($export['shipped_on'])->format('Y-m-d') : null ?>
                    </td>
                    <td style="text-align: center;">
                        <?= !empty($export['request_date']) ? \Carbon\Carbon::parse($export['request_date'])->format('Y-m-d') : null?>
                    </td>
                    <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                        <td style="text-align: center;"><?= $export['request_id']?></td>
                    <?php endif?>
                </tr>
            <?php endforeach;?>
        <?php endif?>
        </tbody>
    </table>
</div>


<script src='//code.jquery.com/jquery-1.12.4.js'></script>
<script src='https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.4.0/js/buttons.bootstrap.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
<script src='//cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js'></script>
<script src='//cdn.datatables.net/buttons/1.4.0/js/buttons.colVis.min.js'></script>

<script>

    $(document).ready(function() {
        var table = $('#export-orders').DataTable( {
            lengthChange: false,
            ordering: false,
            paging: false,
            searching: false,
            buttons: [
                {
                    text: 'В кабинет',
                    action: function ( e, dt, node, config ) {
                        window.history.go(-1);
                    }
                },
                'copy', 'excel', 'colvis'
            ]
        } );

        table.buttons().container()
            .appendTo( '#export-orders_wrapper .col-sm-6:eq(0)' );
    } );
</script>

</body>
</html>