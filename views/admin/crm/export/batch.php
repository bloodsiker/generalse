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
        #batch{
            margin-bottom: 50px;
        }
        caption{
            text-align: center;
            font-weight:bold;
            font-size:16px;
            color: #000
        }
    </style>
</head>
<body>


<table border="1" id="batch" cellpadding="5" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th style="text-align: center; background: #92d8ec;" colspan="7">Order General</th>
        <th style="text-align: center; background: #ecb27a;" colspan="14">Customer Details</th>
        <th style="text-align: center; background: #a6ec8c;" colspan="9">Product and Warranty</th>
        <th style="text-align: center; background: #ddeca1;" colspan="9">Order Type</th>
        <th style="text-align: center; background: #e7b5ec;" colspan="40">Spare Parts</th>
        <th style="text-align: center; background: #dddddd;" colspan="28">Repair Details</th>
        <th style="text-align: center; background: #cccccc;" colspan="8">Date</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php foreach ($table_th as $th => $value):?>
            <td><?= $value?></td>
        <?php endforeach;?>
    </tr>
    <?php if(isset($new_array) && is_array($new_array)):?>
        <?php foreach ($new_array as $tr):?>
            <tr>
                <?php foreach ($tr as $td_key => $td_value):?>
                    <td><?= $td_value?></td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
    <?php endif?>
    </tbody>
</table>



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
        var table = $('#batch').DataTable( {
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
            .appendTo( '#batch_wrapper .col-sm-6:eq(0)' );
    } );
</script>
</body>
</html>