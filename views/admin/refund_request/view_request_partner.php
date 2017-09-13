<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">

            <div class="row header-content">
                <div class="medium-12 small-12 top-gray columns">
                    <h1>View your request</h1>
                </div>
                <div class="medium-12 small-12 bottom-gray colmns">
                    <div class="row">
                        <div class="medium-3 medium-offset-9 small-12 columns form">
                            <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                        </div>
                    </div>
                    <form action="/adm/refund_request/filter/" method="get" class="form form_warranty" id="form_warranty" data-abide novalidate>
                    <div class="row align-bottom">
                        <div class="medium-2 text-left small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                            <input type="text" id="date-start" name="start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" required>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                            <input type="text" id="date-end" name="end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>" required>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <button type="submit" class="button primary">apply filter</button>
                        </div>
                        <div class="medium-6 small-12 text-right columns">
                            <a href="/adm/refund_request/registration" class="button primary tool"><i class="fi-pencil"></i> Registration</a>
                            <a href="/adm/refund_request/view" class="button primary tool active-req"><i class="fi-eye"></i> Show requests</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="row body-content">
                <div class="medium-12 small-12 columns">
                    <h2 class="text-center">List requests <span class="text-green">(<?=count($requestByPartner)?>)</span></h2>
                    <button class="button primary float-right" onclick="tableToExcel('request', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                    <div id="request">
                        <table id="table_refund" class="umbrella-table table" border="1" cellspacing="0" cellpadding="5">
                            <thead>
                            <tr>
								<th>ID</th>
                                <th>SN</th>
                                <th>MTM</th>
                                <th>Lenovo SO</th>
                                <th>Lenovo SO create date (yyyy-mm-dd)</th>
                                <th>Partner SO/RMA number</th>
                                <th>Product group</th>
                                <th>Future unit location</th>
                                <th>Estimated cost(POP price)</th>
                                <th>Refund reason</th>
                                <th>File</th>
                                <th>DOA validation results</th>
                                <th>Date create</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (is_array($requestByPartner)): ?>
                                <?php foreach ($requestByPartner as $request): ?>
                                    <tr class="goods">
										<?php $status = Umbrella\models\Warranty::checkStatusRequest($request['SN'], $request['PN_MTM'], $request['site_id']);
                                        $id_gs = iconv('WINDOWS-1251', 'UTF-8', $status['purchase_id'])?>

                                        <td><?=$id_gs?></td>
                                        <td><?=$request['SN']?></td>
                                        <td><?=$request['PN_MTM']?></td>
                                        <td><?=$request['Lenovo_SO']?></td>
                                        <td><?=$request['SO_Create_Date']?></td>
                                        <td><?=$request['Partner_SO_RMA']?></td>
                                        <td><?=$request['Product_Group']?></td>
                                        <td><?=$request['Future_Unit_location']?></td>
                                        <td><?=$request['Estimated_cost']?></td>
                                        <td><?=$request['Refund_Reason']?></td>
                                        <td>
                                            <a data-open="<?= count(Umbrella\models\File::fileByWarranty($request['id_warrantry'])) ? 'show-file' : ''?>" class="file_request" data-file="<?=$request['id_warrantry']?>">
                                                <?= count(Umbrella\models\File::fileByWarranty($request['id_warrantry']))?> <i class="fi-download"></i>
                                            </a>
                                        </td>
                                        <?php $status = iconv('WINDOWS-1251', 'UTF-8', $status['status_name'])?>
                                        <td class="<?= Umbrella\models\Warranty::getStatusRequest($status)?>">
                                            <?php echo ($status == NULL) ? 'Expect' : $status ?>
                                        </td>
                                        <td><?=$request['date_create_request']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="reveal" id="show-file" data-reveal>
    <form action="#" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>List attachment file</h3>
            </div>
            <div class="medium-12 small-12 columns" id="container-file">
                <ul class="list-attachment-file">
                    <li><a href="" target="_blank" download="download"> <i class="fi-page-doc"></i> sdsd</a></li>
                </ul>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
