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
        #purchase{
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


<div id="purchase">
    <table border="1" id="export-purchase" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Purchase &raquo; <?=(isset($_POST['start'])) ? $_POST['start'] : $_POST['start']?> &mdash; <?=(isset($_POST['end'])) ? $_POST['end'] : $_POST['end']?> <span class="text-green">(<?php if (isset($listExport)) echo count($listExport) ?>)</span></caption>
        <thead>
        <tr>
            <th>ID</th>
            <th>Partner</th>
            <th>Stock name</th>
            <th>Part Number</th>
            <th>SO Number</th>
            <th>Goods name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
            <th>Date create</th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($listExport) && is_array($listExport)):?>
            <?php foreach ($listExport as $export):?>

                <tr>
                    <td><?=$export['purchase_id']?></td>
                    <td><?=$export['site_client_name']?></td>
                    <td><?=iconv('WINDOWS-1251', 'UTF-8',$export['stock_name'])?></td>
                    <td><?=$export['part_number']?></td>
                    <td><?=$export['so_number']?></td>
                    <td><?=iconv('WINDOWS-1251', 'UTF-8',$export['goods_name'])?></td>
                    <td><?=$export['quantity']?></td>
                    <td><?=str_replace('.',',', round($export['price'], 2))?></td>
                    <td><?=iconv('WINDOWS-1251', 'UTF-8',$export['status_name'])?></td>
                    <td><?=$export['created_on']?></td>
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
        var table = $('#export-purchase').DataTable( {
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
            .appendTo( '#export-purchase_wrapper .col-sm-6:eq(0)' );
    } );
</script>
</body>
</html>