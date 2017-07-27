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
                            <div class="medium-9 small-12 columns">
                                <?php if (AdminBase::checkDenied('crm.other.request.send', 'view')): ?>
                                    <button data-open="add-request-modal" class="button primary tool" id="add-request-button"><i class="fi-plus"></i> Request</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.other.request.import', 'view')): ?>
                                    <button data-open="add-request-import-modal" class="button primary tool"><i class="fi-plus"></i> Import request</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.other.request.export', 'view')): ?>
                                    <button class="button primary tool" onclick="tableToExcel('goods_data', 'Request Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                                <?php endif;?>
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
                <table id="goods_data">
                    <caption>List requests
                        <span id="count_refund" class="text-green">(<?php if (isset($listRequests)) echo count($listRequests) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th>Request id</th>
                        <th>Partner</th>
                        <th>Part Number</th>
                        <th>Part Description</th>
                        <th>SO Number</th>
                        <th>Price</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Date create</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listRequests)):?>
                        <?php foreach ($listRequests as $request):?>
                            <tr class="goods <?= (Functions::calcDiffSec($request['date_create']) < 120) ? 'check_lenovo_ok' : ''?>"
                                data-id="<?= $request['id']?>">
                                <td><?= $request['id']?></td>
                                <td><?= $request['name_partner']?></td>
                                <td><?= $request['part_number']?></td>
                                <td><?= $request['part_description']?></td>
                                <td><?= $request['so_number']?></td>
                                <td><?= str_replace('.',',', $request['price'])?></td>
                                <td><?= $request['address']?></td>
                                <td><?= $request['order_type']?></td>
                                <td class="text-center">
                                    <?php if($request['note'] != ' ' && $request['note'] != null):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= $request['note']?>"></i>
                                    <?php endif;?>
                                </td>
                                <td class="status <?= OtherRequest::getStatusRequest($request['status_name'])?>"><?= $request['status_name']?></td>
                                <td><?= Functions::formatDate($request['date_create'])?></td>
                                <td class="action-control">
                                    <?php if($request['action'] == 0):?>

                                    <?php elseif($request['action'] == 1):?>
                                        <a href="" data-action="3" class="accept request-action">Agree</a>
                                        <a href="" data-action="4" class="dismiss request-action">Disagree</a>
                                    <?php elseif($request['action'] == 2):?>
                                        <span style="color: red">Отказано</span>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= !empty($request['comment_disagree']) ? $request['comment_disagree'] : 'Нету комментариев'?>"></i>
                                    <?php elseif($request['action'] == 3):?>
                                        <span style="color: orange">Ожидается отправка</span>
                                    <?php elseif($request['action'] == 4):?>
                                        <span style="color: red">Нет согласия</span>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= !empty($request['comment_disagree']) ? $request['comment_disagree'] : 'Нету комментариев'?>"></i>
                                    <?php elseif($request['action'] == 5):?>
                                        <span style="color: green">Выполненный запрос</span>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator'
                    || $user->role == 'administrator-fin'
                    || $user->role == 'manager'):?>
                    <table id="goods_data">
                        <caption>List requests
                            <span id="count_refund" class="text-green">(<?php if (isset($listRequests)) echo count($listRequests) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <th>Request id</th>
                            <th>Partner</th>
                            <th>Part Number</th>
                            <th>Part Description</th>
                            <th>SO Number</th>
                            <th>Price</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Date create</th>
                            <th class="text-center">Actions</th>
                            <?php if (AdminBase::checkDenied('crm.request.delete', 'view')): ?>
                                <th class="text-center">Delete</th>
                            <?php endif;?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listRequests)):?>
                            <?php foreach ($listRequests as $request):?>
                                <tr class="goods <?= (Functions::calcDiffSec($request['date_create']) < 120) ? 'check_lenovo_ok' : ''?>"
                                    data-id="<?= $request['id']?>">
                                    <td><?= $request['id']?></td>
                                    <td><?= $request['name_partner']?></td>
                                    <td><?= $request['part_number']?></td>
                                    <td><?= $request['part_description']?></td>
                                    <td><?= $request['so_number']?></td>
                                    <td class="request-price">
                                        <span class="request_price"><?= str_replace('.',',', $request['price'])?></span>
                                        <?php if($request['action'] == 0):?>
                                            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                                <a href="" class="button edit-price delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td><?= $request['address']?></td>
                                    <td><?= $request['order_type']?></td>
                                    <td class="text-center">
                                        <?php if($request['note'] != ' ' && $request['note'] != null):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= $request['note']?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <td class="status <?= OtherRequest::getStatusRequest($request['status_name'])?>"><?= $request['status_name']?></td>
                                    <td><?= Functions::formatDate($request['date_create'])?></td>
                                    <td class="action-control">
                                        <?php if($request['action'] == 0):?>
                                            <a href="" data-action="1" class="return request-action">Согласование</a>
                                            <a href="" data-action="2" class="dismiss request-action">Отказ</a>
                                        <?php elseif($request['action'] == 1):?>
                                            <span style="color: green">Ожидаем действия партнера</span>
                                        <?php elseif($request['action'] == 2):?>
                                            <span style="color: red">Отказано</span>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= !empty($request['comment_disagree']) ? $request['comment_disagree'] : 'Нету комментариев'?>"></i>
                                        <?php elseif($request['action'] == 3):?>
                                            <a href="" data-action="5" class="accept request-action">Выполнить запрос</a>
                                        <?php elseif($request['action'] == 4):?>
                                            <span style="color: red">Нет согласия</span>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= !empty($request['comment_disagree']) ? $request['comment_disagree'] : 'Нету комментариев'?>"></i>
                                        <?php elseif($request['action'] == 5):?>
                                            <span style="color: green">Выполненный запрос</span>
                                        <?php endif;?>
                                    </td>
                                    <?php if (AdminBase::checkDenied('crm.request.delete', 'view')): ?>
                                        <td class="text-center">
                                            <a href="" class="delete request-delete"><i class="fi-x-circle"></i></a>
                                        </td>
                                    <?php endif;?>
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
                <select name="order_type" class="required" required>
                    <option value="" selected disabled>none</option>
                    <option value="Гарантия">Гарантия</option>
                    <option value="Не гарантия">Не гарантия</option>
                </select>
            </div>
            <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                <div class="medium-12 small-12 columns">
                    <label>Delivery address</label>
                    <select name="address" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php foreach ($delivery_address as $address):?>
                            <option value="<?= $address?>"><?= $address?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="medium-12 small-12 columns">
                <label>Note</label>
                <textarea rows="3" name="note"></textarea>
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



<div class="reveal" id="add-request-import-modal" data-reveal>
    <form action="/adm/crm/other-request/import" id="add-request-import-form" method="post" class="form" enctype="multipart/form-data" data-abide
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
                        <select name="order_type" class="required" required>
                            <option value="" selected disabled>none</option>
                            <option value="Гарантия">Гарантия</option>
                            <option value="Не гарантия">Не гарантия</option>
                        </select>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Note</label>
                        <textarea rows="3" name="note"></textarea>
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
                                            href="/upload/attach_other_request/request_import.xlsx" style="color: #2ba6cb"
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

<div class="reveal" id="edit-price" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Edit price</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Price </label>
                        <input type="text" id="request_price" name="request_price" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-request-price" class="button primary">Edit</button>
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
