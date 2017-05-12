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

<div class="btn-group pull-left" role="group" aria-label="...">
    <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;"><span class="glyphicon glyphicon-arrow-left"></span> В кабинет</button>
    <button type="button" class="btn btn-danger" onclick="tableToExcel('batch', 'Export Table')"><span class="glyphicon glyphicon-export"></span> Export</button>
</div>


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