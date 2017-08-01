<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

<div class="btn-group pull-left" role="group" aria-label="...">
    <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;"><span class="glyphicon glyphicon-arrow-left"></span> В кабинет</button>
    <button type="button" class="btn btn-danger" onclick="tableToExcel('purchase', 'Purchase export Table')"><span class="glyphicon glyphicon-export"></span> Export</button>
</div>

<div id="purchase">
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Purchase &raquo; <?=(isset($_POST['start'])) ? $_POST['start'] : $_POST['start']?> &mdash; <?=(isset($_POST['end'])) ? $_POST['end'] : $_POST['end']?></caption>
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

<script type="text/javascript">
    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
        return function(table, name) {
            if (!table.nodeType) table = document.getElementById(table);
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
            window.location.href = uri + base64(format(template, ctx))
        }
    })()
</script>
</body>
</html>