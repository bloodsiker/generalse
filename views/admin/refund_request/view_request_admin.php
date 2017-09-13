<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
            <div class="row header-content">
                <div class="medium-12 small-12 top-gray columns">
                    <h1>View your request</h1>
                </div>
                <div class="medium-12 small-12 bottom-gray colmns">
                    <form action="/adm/refund_request/filter/" method="get" class="form form_warranty" id="form_warranty" data-abide novalidate>
                    <div class="row align-bottom">
                        <div class="medium-6 columns">
                            <div class="row align-bottom">
                                <div class="medium-4 text-left small-12 columns">
                                    <label><i class="fi-flag"></i> Request Country
                                        <select class="country" name="Request_Country">
                                            <option value="" selected>Country</option>
                                            <?php if (isset($countryList) && is_array($countryList)): ?>
                                                <?php foreach ($countryList as $country): ?>
                                                    <option value="<?=$country['full_name']?>" <?php if((isset($_GET['Request_Country']) && $_GET['Request_Country'] != "") && ($_GET['Request_Country'] == $country['full_name'])) echo 'selected'?>><?=$country['full_name']?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </label>
                                </div>
                                <div class="medium-4 text-left small-12 columns">
                                    <label><i class="fi-flag"></i> Partner
                                        <select class="country" name="id_partner">
                                            <option value="" selected>Name_partner</option>
                                            <?php if (isset($allPartner) && is_array($allPartner)): ?>
                                                <?php foreach ($allPartner as $partner): ?>
                                                    <option value="<?=$partner['id_user']?>" <?php if(isset($_GET['id_partner']) && $_GET['id_partner'] != "" && $_GET['id_partner'] == $partner['id_user']) echo 'selected'?>><?=$partner['name_partner']?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="row align-bottom">
                                <div class="medium-4 text-left small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                    <input type="text" id="date-start" name="start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>">
                                </div>
                                <div class="medium-4 text-left small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                    <input type="text" id="date-end" name="end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>">
                                </div>
                                <div class="medium-4 small-12 columns">
                                    <button type="submit" class="button primary">apply filter</button>
                                </div>
                            </div>
                        </div>

                        <div class="medium-6 columns">
                            <div class="row">
                                <div class="medium-6 medium-offset-6 small-12 columns form">
                                    <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                                </div>
                                <div class="medium-12 small-12 text-right columns">
                                    <a href="/adm/refund_request/registration" class="button primary tool"><i class="fi-pencil"></i> Registration</a>
                                    <a href="/adm/refund_request/view" class="button primary tool active-req"><i class="fi-eye"></i> Show requests</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>


            <div class="row body-content">
                <div class="medium-12 small-12 columns">
                    <h2 class="text-center">List requests <span id="count_refund" class="text-green">(<?=count($allRequest)?>)</span></h2>
                    <button class="button primary float-right" onclick="tableToExcel('request', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                    <div id="request">
                        <table id="table_refund" class="umbrella-table table" border="1" cellspacing="0" cellpadding="5">
                            <thead>
                            <tr>
								<th>ID</th>
                                <th>Partner</th>
                                <th>Country</th>
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
								<th>Comm</th>
                                <th>DOA validation results</th>
                                <th>Date create</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (is_array($allRequest)): ?>
                                <?php foreach ($allRequest as $request): ?>
                                    <?php $status_all = Umbrella\models\Warranty::checkStatusRequest($request['SN'], $request['PN_MTM'], $request['site_id']);
                                    $id_gs = iconv('WINDOWS-1251', 'UTF-8', $status_all['purchase_id'])?>
                                    <tr class="goods <?php echo ($request['lenovo_ok'] == 1) ? 'check_lenovo_ok' : '' ?>"
                                        data-id="<?=$request['id_warrantry']?>"
                                        data-gm-id="<?=$status_all['id']?>">
                                        <td class="<?php echo ($request['lenovo_ok'] == 0) ? 'check_lenovo' : 'uncheck_lenovo' ?>"><?=$id_gs?></td>
                                        <td><?=$request['name_partner']?></td>
                                        <td><?=$request['Request_Country']?></td>
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
										<td data-comment="<?=$request['Additional_Comment']?>" class="comment">
                                            <a href="" class="add-lenovo-num"><i class="fi-comments"></i></a><br>
                                            <span class="text-lenovo-num"><?=$request['lenovo_num']?></span><br>
                                            <?=(empty($request['Additional_Comment'])) ? '' : '+'?>
                                        </td>
                                        <?php $status = iconv('WINDOWS-1251', 'UTF-8', $status_all['status_name'])?>
                                        <td class="<?= Umbrella\models\Warranty::getStatusRequest($status)?>">
                                            <?php echo ($status == NULL) ? 'Expect' : $status ?>
											<br>
                                            <?php $date_write = iconv('WINDOWS-1251', 'UTF-8', $status_all['writeoff_status_on']);
                                            echo $date_write; ?>
                                        </td>
                                        <td><?=$request['date_create_request']?></td>
                                        <td class="action-control">
                                            <?php if($status == 'предварительное'):?>
                                                <a href="" class="accept refund-accept"><i class="fi-check"></i></a>
                                                <a href="" class="dismiss refund-dismiss"><i class="fi-x"></i></a>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
						
						<?php if(count($allRequest) >= 30):?>
                            <div class="text-center button_load">
                                <div class="button primary" style="width: inherit;" id="load-request">Показать еще</div>
                                <div class="text-center">
                                    <img src="/template/admin/img/loading.gif" id="imgLoad">
                                </div>
                            </div>
                        <?php endif;?>
						
                    </div>
                </div>
            </div>
    </div>
</div>
<div style="height: 100px"></div>

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

<div class="reveal" id="show-comment" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Additional Comment</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <div class="modal_comment">

            </div>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="add-lenovo-num" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Lenovo number</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <input type="hidden" name="add-parts" value="true">
                    <div class="medium-12 small-12 columns">
                        <label>Number <span class="lenovo_num"></span></label>
                        <input type="text" id="lenovo_num" name="lenovo_num" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-lenovo-num" class="button primary">Add</button>
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
