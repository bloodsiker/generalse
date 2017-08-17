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
    </style>
</head>
<body>


<div id="orders">
    <table border="1" id="export-orders" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Backlog Analysis &raquo;</caption>
        <thead>
        <tr>
            <th style="text-align: center;">Part Number</th>
            <th style="text-align: center;">Part Description</th>
            <th style="text-align: center;">SO Number</th>
            <th style="text-align: center;">Customer Name</th>
            <th style="text-align: center;">Comments</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($new_array)):?>
            <?php foreach ($new_array as $item):?>
                <tr>
                    <td><?=$item['part_number']?></td>
                    <td><?=$item['Part_Description']?></td>
                    <td><?=$item['SO_Number']?></td>
                    <td><?=$item['customer_name']?></td>
                    <td><?=$item['comments']?></td>
                </tr>
            <?php endforeach;?>
        <?php endif;;?>
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