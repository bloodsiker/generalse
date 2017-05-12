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
        #disassembly{
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
    <button type="button" class="btn btn-danger" onclick="tableToExcel('disassembly', 'W3C Example Table')"><span class="glyphicon glyphicon-export"></span> Export</button>
</div>

<div id="disassembly">
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <caption>Export &laquo; Disassembly &raquo; <?=(isset($_GET['start'])) ? $_GET['start'] : $_GET['start']?> &mdash; <?=(isset($_GET['end'])) ? $_GET['end'] : $_GET['end']?></caption>
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
                    <?php $status = Disassembly::checkStatusRequest($export['part_number'], $export['serial_number']);
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