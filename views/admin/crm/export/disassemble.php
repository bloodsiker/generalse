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
        #disassembly{
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


<div id="disassembly">
    <table border="1" id="export-disassembly" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Disassembly &raquo; <?=(isset($_GET['start'])) ? $_GET['start'] : $_GET['start']?> &mdash; <?=(isset($_GET['end'])) ? $_GET['end'] : $_GET['end']?> <span class="text-green">(<?php if (isset($listExport)) echo count($listExport) ?>)</span></caption>
        <thead>
        <tr>
            <th>ID</th>
            <th>Partner</th>
            <th>Part Number</th>
            <th>Serial Number</th>
            <th>Device name</th>
            <th>Date create</th>
            <th>Goods name</th>
            <th>Part Number</th>
            <th>Stock name</th>
            <th>Quantity</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php $site_id = ''?>
        <?php if(isset($listExport) && is_array($listExport)):?>
            <?php foreach ($listExport as $export):?>
                <?php if($export['site_id'] == $site_id):?>
                <tr>
                    <td><?=(isset($id_gs)) ? $id_gs : ''?></td>
                    <td><?=$export['name_partner']?></td>
                    <td><?=$export['part_number']?></td>
                    <td><?=$export['serial_number']?></td>
                    <td><?=$export['dev_name']?></td>
                    <td><?=$export['date_create']?></td>
                    <td><?=$export['mName']?></td>
                    <td><?=$export['goods_part']?></td>
                    <td><?=iconv('WINDOWS-1251', 'UTF-8',$export['stock_name'])?></td>
                    <td><?=$export['quantity']?></td>
                    <td><?=(isset($status)) ? $status : ''?></td>
                </tr>
                <?php else:?>
                    <?php $status = Umbrella\models\Disassembly::checkStatusRequestMSSQL($export['site_id']);
                    $id_gs = iconv('WINDOWS-1251', 'UTF-8', $status['decompile_id']);
                    $status = iconv('WINDOWS-1251', 'UTF-8', $status['status_name'])?>

                    <tr>
                        <td><?=(isset($id_gs)) ? $id_gs : ''?></td>
                        <td><?=$export['name_partner']?></td>
                        <td><?=$export['part_number']?></td>
                        <td><?=$export['serial_number']?></td>
                        <td><?=$export['dev_name']?></td>
                        <td><?=$export['date_create']?></td>
                        <td><?=$export['mName']?></td>
                        <td><?=$export['goods_part']?></td>
                        <td><?=iconv('WINDOWS-1251', 'UTF-8',$export['stock_name'])?></td>
                        <td><?=$export['quantity']?></td>
                        <td><?=(isset($status)) ? $status : ''?></td>
                    </tr>
                <?php endif;?>
                <?php $site_id = $export['site_id']?>
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
        var table = $('#export-disassembly').DataTable( {
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
            .appendTo( '#export-disassembly_wrapper .col-sm-6:eq(0)' );
    } );
</script>
</body>
</html>