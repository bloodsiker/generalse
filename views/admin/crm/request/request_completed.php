<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Request</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">

                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>

                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-4 small-12 columns">

                                <a href="/adm/crm/request" class="button primary tool"><i class="fi-arrow-left"></i> Back to request</a>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.export', 'view')): ?>
                                    <button class="button primary tool" onclick="tableToExcel('goods_data', 'Request Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                                <?php endif;?>
                            </div>
                            <div class="medium-5  small-12 columns">
                                <form action="/adm/crm/request/completed/" method="get" class="form">
                                    <div class="row align-bottom">
                                        <div class="medium-4 text-left small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                            <input type="text" id="date-start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" name="start" required>
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                            <input type="text" id="date-end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>" name="end">
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="medium-3 small-12 columns form">
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <?php if(isset($request_message) && $request_message != ''):?>
                    <div class="alert-success" style="margin: 0px auto 10px;"><?=$request_message?></div>
                <?php endif;?>
                <?php if($user->role == 'partner'):?>
                <table class="umbrella-table" id="goods_data">
                    <caption>Last requests on
                        <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-30 days') ?> &mdash;
                        <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                        <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th>Request id</th>
                        <th>Order number</th>
                        <th>Partner</th>
                        <th>Part Number</th>
                        <th>Part Description</th>
                        <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                            <th>Subtype</th>
                        <?php endif?>
                        <th>SO Number</th>
                        <th>Price</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Date request</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listCheckOrders)):?>
                        <?php foreach($listCheckOrders as $order):?>
                            <tr class="goods <?= (Umbrella\components\Functions::calcDiffSec($order['created_on']) < 120) ? 'check_lenovo_ok' : ''?>">
                                <td><?= $order['id']?></td>
                                <td><?= $order['order_number']?></td>
                                <td><?= $order['site_client_name']?></td>
                                <td><?= $order['part_number']?></td>
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['goods_name'])?></td>
                                <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['subtype_name'])?></td>
                                <?php endif?>
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></td>
                                <td><?= str_replace('.',',', round($order['price'], 2))?></td>
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['note'])?></td>
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['type_name'])?></td>
                                <td class="text-center">
                                    <?php if($order['note1'] != ' ' && $order['note1'] != null):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['note1'])?>"></i>
                                    <?php endif;?>
                                </td>
                                <?php $status_name = iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?>
                                <td class="<?= Umbrella\models\Orders::getStatusRequest($status_name);?>">
                                    <?= $status_name?>
                                    <?php if($status_name == 'Отказано'):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['command_text'])?>"></i>
                                    <?php endif;?>
                                </td>
                                <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator'
                    || $user->role == 'administrator-fin'
                    || $user->role == 'manager'):?>
                    <table class="umbrella-table" id="goods_data">
                        <caption>Last requests on
                            <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-30 days') ?> &mdash;
                            <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                            <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <th>Request id</th>
                            <th>Order number</th>
                            <th>Partner</th>
                            <th>Part Number</th>
                            <th>Part Description</th>
                            <th>Subtype</th>
                            <th>SO Number</th>
                            <th>Price</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Date request</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listCheckOrders)):?>
                            <?php foreach($listCheckOrders as $order):?>
                                <tr class="goods <?= (Umbrella\components\Functions::calcDiffSec($order['created_on']) < 120) ? 'check_lenovo_ok' : ''?>"
                                    data-id="<?= $order['id']?>">
                                    <td><?= $order['id']?></td>
                                    <td><?= $order['order_number']?></td>
                                    <td><?= $order['site_client_name']?></td>
                                    <td><span class="order_part_num"><?= $order['part_number']?></span></td>
                                    <td><span class="pn_goods_name"><?= iconv('WINDOWS-1251', 'UTF-8', $order['goods_name'])?></span></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['subtype_name'])?></td>
                                    <td><span class="order_so"><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></span></td>
                                    <td><?= str_replace('.',',', round($order['price'], 2))?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['note'])?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['type_name'])?></td>
                                    <td class="text-center">
                                        <?php if($order['note1'] != ' ' && $order['note1'] != null):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['note1'])?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <?php $status_name = iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?>
                                    <td class="order-tr-status <?= Umbrella\models\Orders::getStatusRequest($status_name);?>">
                                        <span class="order_status"><?= $status_name?></span>
                                        <?php if($status_name == 'Отказано'):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['command_text'])?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


<div class="reveal" id="add-request-modal" data-reveal>
    <form action="" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New request</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>SO Number</label>
                <input type="text" class="required" name="so_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Type</label>
                <select name="order_type_id" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($order_type as $type):?>
                        <option value="<?= $type['id']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $type['name'])?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                <div class="medium-12 small-12 columns">
                    <label>Delivery address</label>
                    <select name="note" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php foreach ($delivery_address as $address):?>
                            <option value="<?= $address?>"><?= $address?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="medium-12 small-12 columns">
                <label>Note</label>
                <textarea rows="3" name="note1"></textarea>
            </div>
            <input type="hidden" name="add_request" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="price-modal" data-reveal>
    <form action="" id="price-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Price</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Price </label>
                <input type="text" class="required" name="price" disabled>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="add-request-import-modal" data-reveal>
    <form action="/adm/crm/request/import" id="add-request-import-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import request</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                        <div class="medium-12 small-12 columns">
                            <label>Delivery address</label>
                            <select name="note" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php foreach ($delivery_address as $address):?>
                                    <option value="<?= $address?>"><?= $address?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="medium-12 small-12 columns">
                        <label>Type</label>
                        <select name="order_type_id" class="required" required>
                            <option value="" selected disabled>none</option>
                            <?php foreach ($order_type as $type):?>
                                <option value="<?= $type['id']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $type['name'])?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Note</label>
                        <textarea rows="3" name="note1"></textarea>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_file_form" class="button primary">Attach</label>
                                <input type="file" id="upload_file_form" class="show-for-sr" name="excel_file" required>
                            </div>

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                            href="/upload/attach_request/request_import.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="import_request" value="true">
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal" id="edit-pn" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Edit part number</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part number </label>
                        <input type="text" id="order_pn" name="order_pn" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-order-pn" class="button primary">Edit</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal" id="edit-so" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Edit SO number</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>SO number</label>
                        <input type="text" id="order_so" name="order_so" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-order-so" class="button primary">Edit</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="edit-status" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Edit status</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Status</label>
                        <textarea name="" id="order_status" cols="30" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-order-status" class="button primary">Edit</button>
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