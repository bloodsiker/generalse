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
                            <div class="medium-12 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.send', 'view')): ?>
                                    <button class="button primary tool" id="add-request-button"><i class="fi-plus"></i> Request</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.multi-request.send', 'view')): ?>
                                    <button class="button primary tool" id="add-multi-request-button"><i class="fi-plus"></i> Multi Request</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import', 'view')): ?>
                                    <button data-open="add-request-import-modal" class="button primary tool"><i class="fi-plus"></i> Import request</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.export', 'view')): ?>
                                    <button data-open="export-modal" class="button primary tool"><i class="fi-page-export"></i> Export to Excel</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.price', 'view')): ?>
                                    <button class="button primary tool" id="price-button"><i class="fi-plus"></i> Price</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.allprice', 'view')): ?>
                                    <button data-open="download-all-price" class="button primary tool"><i class="fi-download"></i> ALL PRICES</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import.status', 'view')): ?>
                                    <button data-open="import-edit-status-modal" class="button primary tool"><i class="fi-plus"></i> Import status</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.deleted', 'view')): ?>
                                    <button data-open="open-removed-request" class="button primary tool"><i class="fi-x-circle"></i> Deleted requests</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.upload.price', 'view')): ?>
                                    <button data-open="open-upload-price" class="button primary tool"><i class="fi-page-export"></i> Upload Price</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.analog', 'view')): ?>
                                    <a href="/adm/crm/request/list_analog" class="button primary tool"><i class="fi-list"></i> Analog</a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <?php if(isset($request_message['add_request']) && $request_message['add_request'] != ''):?>
                    <div class="medium-12 small-12 columns" style="text-align: center">
                        <div class="alert-success" style="margin: 0px auto 10px;"><?=$request_message['add_request']?></div>
                    </div>
                <?php endif;?>
                <?php if(isset($request_message['replace_by_analog']) && $request_message['replace_by_analog'] != ''):?>
                    <div class="medium-12 small-12 columns" style="text-align: center">
                        <div class="alert-success" style="margin: 0px auto 10px;"><?=$request_message['replace_by_analog']?></div>
                    </div>
                <?php endif;?>
                <?php if($user->role == 'partner'):?>
                <table class="umbrella-table">
                    <caption>List requests
                        <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th>Request id</th>
                        <th>Partner</th>
                        <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                            <th>Partner status</th>
                        <?php endif?>
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
                        <th>Date create</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listCheckOrders)):?>
                        <?php foreach($listCheckOrders as $order):?>
                            <tr class="goods <?= (Umbrella\components\Functions::calcDiffSec($order['created_on']) < 120) ? 'check_lenovo_ok' : ''?>"
                                <?= is_null($order['number'])? null : 'data-number=' . $order['number']?>>
                                <td><?= is_null($order['number'])? $order['id'] : 'Multi-request ' . $order['number']?></td>
                                <td><?= $order['site_client_name']?></td>
                                <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['site_client_status'])?></td>
                                <?php endif?>
                                <td style="background: <?= in_array($order['part_number'], $arrayPartNumber) ? '#f79898' : 'inherit'?>">
                                    <?= $order['part_number']?>
                                </td>
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
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?></td>
                                <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator'
                    || $user->role == 'administrator-fin'
                    || $user->role == 'manager'):?>
                    <table class="umbrella-table">
                        <caption>List requests
                            <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <th>Request id</th>
                            <th>Partner</th>
                            <th>Partner status</th>
                            <th>Part Number</th>
                            <th class="sort">Part Description</th>
                            <th>Subtype</th>
                            <th>SO Number</th>
                            <th>Price</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Date create</th>
                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.delete', 'view')): ?>
                                <th>Delete</th>
                            <?php endif;?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listCheckOrders)):?>
                            <?php foreach($listCheckOrders as $order):?>
                                <tr class="goods <?= (Umbrella\components\Functions::calcDiffSec($order['created_on']) < 120) ? 'check_lenovo_ok' : ''?>"
                                    data-id="<?= $order['id']?>"
                                    <?= is_null($order['number'])? null : 'data-number=' . $order['number']?>>
                                    <td><?= is_null($order['number'])? $order['id'] : 'Multi-request ' . $order['number']?></td>
                                    <td><?= $order['site_client_name']?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['site_client_status'])?></td>
                                    <td data-pn="<?= $order['part_number']?>" class="order-tr-pn" style="background: <?= in_array($order['part_number'], $arrayPartNumber) ? '#f79898' : 'inherit'?>">
                                        <span class="order_part_num"><?= $order['part_number']?></span>
                                        <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.pn_edit', 'view')): ?>
                                                <a href="" class="button edit-pn delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td class="order-tr-goods-name">
                                        <span class="pn_goods_name"><?= iconv('WINDOWS-1251', 'UTF-8', $order['goods_name'])?></span>
                                        <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.clear_pn_desc', 'view')): ?>
                                                <a href="" class="button clear_goods_name delete" onclick="return confirm('Вы уверены что хотите очистить название?') ? true : false;"><i class="fi-loop"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?= iconv('WINDOWS-1251', 'UTF-8', $order['subtype_name'])?>
                                    </td>
                                    <td class="order-tr-so">
                                        <span class="order_so"><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></span>
                                        <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.so_edit', 'view')): ?>
                                                <a href="" class="button edit-so delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
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
                                    <td class="order-tr-status">
                                        <span class="order_status"><?= iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?></span>
                                        <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.status_edit', 'view')): ?>
                                                <a href="" class="button edit-status delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                                    <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.delete', 'view')): ?>
                                        <td class="text-center">
                                            <?php if(is_null($order['number'])):?>
                                                <button data-reqid="<?= $order['id']?>" onclick="return confirm('Вы уверены что хотите удалить?') ? true : false;" class="delete delete-request"><i class="fi-x-circle"></i></button>
                                            <?php endif;?>
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


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.send', 'view')): ?>
    <div class="reveal" id="add-request-modal" data-reveal>
        <form action="" id="add-request-form" method="post" class="form" data-abide novalidate>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>New request</h3>
                </div>
                <div class="medium-10 small-10 columns">
                    <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                    <span style="color: orange;" class="pn-analog"></span>
                    <input type="text" class="required" name="part_number" onkeyup="checkCurrPartNumber(this)" autocomplete="off" required>
                </div>

                <div class="medium-2 small-2 columns">
                    <label>Count</label>
                    <input type="text" class="required" name="part_quantity" value="1" onkeyup="validCount(this)" autocomplete="off" required>
                </div>

                <div class="medium-6 small-6 columns">
                    <label>Price</label>
                    <input type="text" name="price" disabled>
                </div>

                <div class="medium-6 small-6 columns group-analog hide">
                    <label>Price analog</label>
                    <input type="text" name="analog-price" disabled>
                </div>

                <div class="medium-12 small-12 columns">
                    <label>SO Number/Note</label>
                    <input type="text" name="so_number" autocomplete="off">
                </div>


                <div class="medium-12 small-12 columns">
                    <label>Part description RUS</label>
                    <input type="text" name="pn_name_rus" autocomplete="off">
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

                <div class="medium-12 small-12 columns">
                    <label>Delivery address</label>
                    <select name="note" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php if(is_array($delivery_address)):?>
                            <?php foreach ($delivery_address as $address):?>
                                <option value="<?= $address?>"><?= $address?></option>
                            <?php endforeach;?>
                        <?php endif; ?>
                        <option value="other_address">Write another address</option>
                    </select>
                    <input type="hidden" name="your_address" placeholder="Enter your address">
                </div>

                <div class="medium-12 small-12 columns">
                    <label>Flash on PNC</label>
                    <input type="text" name="note1" autocomplete="off">
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
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.multi-request.send', 'view')): ?>
    <div class="reveal large" id="add-multi-request-modal" data-reveal>
            <div class="row align-top">
                <div class="medium-5 small-12 columns">
                    <form action="" id="add-multi-request-form" method="post" class="form" data-abide novalidate>
                        <div class="row align-top">
                            <div class="medium-12 small-12 columns">
                                <h3>New request</h3>
                            </div>

                            <div class="medium-9 small-9 columns">
                                <label>Part Number</label>
                                <input type="text" class="required" name="multi_part_number" autocomplete="off" required>
                            </div>

                            <div class="medium-3 small-3 columns">
                                <label>Quantity</label>
                                <input type="text" class="required" name="part_quantity" value="1" onkeyup="validCount(this)" autocomplete="off" required>
                            </div>

                            <div class="medium-12 small-12 columns">
                                <label>Part Description</label>
                                <input type="text" name="goods_name">
                            </div>

                            <div class="medium-12 small-12 columns">
                                <ul class="stocks-view">

                                </ul>
                            </div>

                            <div class="medium-12 small-12 columns">
                                <label>Stocks</label>
                                <select name="stock_id">

                                </select>
                            </div>

                            <input type="hidden" name="stock_count" value="">
                            <input type="hidden" name="stock_name" value="">

                            <div class="medium-12 small-12 columns">
                                <label>Period of the request(days)</label>
                                <input type="text" name="period" onkeyup="checkCurrPartNumber(this)" autocomplete="off">
                            </div>

                            <div class="medium-12 small-12 columns">
                                <label>Note</label>
                                <input type="text" name="note1" autocomplete="off">
                            </div>

                            <div class="medium-12 small-12 columns">
                                <button type="submit" class="button primary">Add to cart</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="medium-7 small-12 columns">
                    <div class="row align-top">
                        <div class="medium-12 small-12 columns">
                            <h3>Cart</h3>
                        </div>
                        <div class="medium-12 small-12 columns" id="cart-container">
                            <?php require_once ROOT . '/views/admin/crm/request/multi-request-cart.php'?>
                        </div>
                    </div>
                </div>
            </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.price', 'view')): ?>
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
                    <label>Price</label>
                    <input type="text" class="required" name="price" disabled>
                </div>

                <div class="large-12 small-12 columns group-analog hide">
                    <label>Part Analog <span style="color: orange;" class="pn-analog"></span>
                        <div class="input-group">
                            <input type="text" name="part-analog" id="copy" class="required" style="margin: 0"/>
                            <span class="btn-clip input-group-label copy-analog" data-clipboard-target="#copy">Copy</span>
                        </div>
                    </label>
                </div>

                <div class="medium-12 small-12 columns group-analog hide">
                    <label>Price analog</label>
                    <input type="text" class="required" name="analog-price" disabled>
                </div>

                <div class="medium-12 small-12 columns group-stocks hide">
                    <label>Stock <span style="color: #4CAF50;" class="name-stock"></span></label>
                    <input type="text" class="required" name="quantity" disabled>
                </div>
            </div>
        </form>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import', 'view')): ?>
    <div class="reveal" id="add-request-import-modal" data-reveal>
        <form action="/adm/crm/request/import" id="add-request-import-form" method="post" class="form" enctype="multipart/form-data" data-abide
              novalidate>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Import request</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label>Delivery address</label>
                            <select name="note" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php if(is_array($delivery_address)):?>
                                    <?php foreach ($delivery_address as $address):?>
                                        <option value="<?= $address?>"><?= $address?></option>
                                    <?php endforeach;?>
                                <?php endif; ?>
                                <option value="other_address">Write another address</option>
                            </select>
                            <input type="hidden" name="your_address" placeholder="Enter your address">
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
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import.status', 'view')): ?>
    <div class="reveal" id="import-edit-status-modal" data-reveal>
        <form action="/adm/crm/request/edit_status" id="import-edit-status-form" method="post" class="form" enctype="multipart/form-data" data-abide
              novalidate>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Edit status from excel</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">

                        <div class="medium-12 small-12 columns">
                            <div class="row align-bottom ">
                                <div class="medium-12 small-12 columns">
                                    <label for="upload_file_form_2" class="button primary">Attach</label>
                                    <input type="file" id="upload_file_form_2" class="show-for-sr" name="excel_file" required>
                                </div>

                            </div>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="medium-6 small-12 columns">
                                    <div style="padding-bottom: 37px; color: #fff"><a
                                                href="/upload/attach_request/edit_status_request.xlsx" style="color: #2ba6cb"
                                                download="">download</a> a template file to import
                                    </div>
                                </div>
                                <input type="hidden" name="edit_status_from_excel" value="true">
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
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.pn_edit', 'view')): ?>
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
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.so_edit', 'view')): ?>
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
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.status_edit', 'view')): ?>
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
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.upload.price', 'view')): ?>
    <div class="reveal" id="open-upload-price" data-reveal>
        <form action="/adm/crm/request/upload_price" id="price-upload" method="post" class="form" enctype="multipart/form-data" data-abide
              novalidate>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Upload price</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">


                        <div class="medium-12 small-12 columns">
                            <div class="row" style="color: #fff">
                                <h4>Формат и название файлов</h4>
                                <ul>
                                    <li>Electrolux(Партнер): <span style="color: orange">Price_Electrolux.zip</span></li>
                                    <li>Electrolux(Оптовик): <span style="color: orange">Price_Electrolux_Opt.zip</span></li>
                                    <li>Electrolux GE(Партнер GE): <span style="color: orange">Electrolux_Prices_GE.zip</span></li>
                                </ul>
                            </div>
                        </div>

                        <div class="medium-6 small-12 columns">
                            <label>Group Partner</label>
                            <select name="id_group" class="required" required>
                                <option value="2">Electrolux</option>
                                <option value="4">Electrolux GE</option>
                            </select>
                        </div>

                        <div class="medium-6 small-12 columns">
                            <label>Price</label>
                            <select name="partner_status" class="required" required>
                                <option value="Партнер">Партнер</option>
                                <option value="Оптовик">Оптовик</option>
                                <option value="Партнер GE">Партнер GE</option>
                            </select>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <div class="row align-bottom ">
                                <div class="medium-12 small-12 columns">
                                    <label for="upload_new_price" class="button primary">Attach</label>
                                    <input type="file" id="upload_new_price" class="show-for-sr" name="excel_file" multiple>
                                </div>

                            </div>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="upload-progress">
                                    <div class="upload-bar"></div >
                                    <div class="upload-percent">0%</div >
                                </div>
                            </div>
                        </div>


                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="medium-6 small-12 columns">
                                    <div id="status" style="color: #fff;"></div>
                                </div>
                                <div class="medium-6 small-12 columns">
                                    <input type="submit" class="button primary" value="Upload File to Server">

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
<?php endif?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.allprice', 'view')): ?>
    <div class="reveal" id="download-all-price" data-reveal>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Download all prices in excel file</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">

                        <div class="medium-12 small-12 columns">
                            <div class="row" style="color: #fff">
                                <ul>
                                    <?php if($user->role == 'administrator' || $user->role == 'manager'):?>
                                        <li>
                                            <a href="<?= $user->linkUrlDownloadAllPrice(2, 'Партнер')?>" download>
                                                <span style="color: orange"><?= $user->linkNameDownloadAllPrice(2, 'Партнер')?></span>
                                            </a>
                                            <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(2, 'Партнер')?></span>
                                        </li>
                                        <li>
                                            <a href="<?= $user->linkUrlDownloadAllPrice(2, 'Оптовик')?>" download>
                                                <span style="color: orange"><?= $user->linkNameDownloadAllPrice(2, 'Оптовик')?></span>
                                            </a>
                                            <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(2, 'Оптовик')?></span>
                                        </li>
                                        <li>
                                            <a href="<?= $user->linkUrlDownloadAllPrice(4, 'Партнер GE')?>" download>
                                                <span style="color: orange"><?= $user->linkNameDownloadAllPrice(4, 'Партнер GE')?></span>
                                            </a>
                                            <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(4, 'Партнер GE')?></span>
                                        </li>
                                    <?php elseif ($user->role == 'partner'):?>
                                        <li>
                                            <a href="<?= $user->linkUrlDownloadAllPrice()?>" download>
                                                <span style="color: orange"><?= $user->linkNameDownloadAllPrice()?></span>
                                            </a>
                                            <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice()?></span>
                                        </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.export', 'view')): ?>
    <!--=== EXPORT EXCEL ====-->
    <div class="reveal large" id="export-modal" data-reveal>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Generate report</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <form action="/adm/crm/export/request/" method="POST" id="form-generate-excel" data-abide>
                    <h4 style="color: #fff">Between date</h4>
                    <div class="row align-bottom" style="background: #323e48; padding-top: 10px; margin-bottom: 10px">
                        <div class="medium-8 small-8 columns">
                            <div class="row">
                                <div class="medium-6 small-12 columns">
                                    <label>From Date</label>
                                    <input type="text" class="required date" value="<?= Umbrella\components\Functions::formatDate(end($listCheckOrders)['created_on'])?>" name="start" required>
                                </div>
                                <div class="medium-6 small-12 columns">
                                    <label>To Date</label>
                                    <input type="text" class="required date" value="<?= date('Y-m-d')?>" name="end" required>
                                </div>
                            </div>
                        </div>
                        <div class="medium-4 small-4 columns">
                            <label>Status</label>
                            <select name="processed" id="processed">
                                <option value="0">Not completed</option>
                                <option value="1">Completed</option>
                            </select>
                        </div>
                    </div>

                    <h4 style="color: #fff">Partners</h4>
                    <?php if($user->role == 'administrator' || $user->role == 'fin-administrator'):?>
                        <div class="row align-bottom" style="background: #323e48; padding-top: 10px">
                            <div class="medium-12 small-12 columns">
                            <ul class="tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="deeplinked-tabs">
                                <?php foreach ($userInGroup as $groups):?>
                                    <li class="tabs-title">
                                        <a href="#group-<?= $groups['group_id']?>" aria-selected="true"><?= $groups['group_name']?></a>
                                    </li>
                                <?php endforeach;?>
                            </ul>

                            <div class="tabs-content" data-tabs-content="deeplinked-tabs" style="background: #323e48; margin-bottom: 10px">
                                <?php foreach ($userInGroup as $groups):?>
                                    <div class="tabs-panel" id="group-<?= $groups['group_id']?>">
                                        <div class="row">
                                            <div class="medium-12 small-12 columns">
                                                <span>
                                                    <input type="checkbox" onclick="checkAllCheckbox(event, '#group-<?= $groups['group_id']?>')" id="id-<?= $groups['group_id']?>-all">
                                                    <label class="check all" for="id-<?= $groups['group_id']?>-all" >Выбрать всех</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php foreach($groups['users'] as $partner):?>
                                            <div class="medium-4 small-4 columns">
                                                <span>
                                                   <input type="checkbox" onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                    <label  class="check" for="id-<?=$partner['id_user'] ?>" ><?=$partner['name_partner'] ?></label><br>
                                                </span>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="row align-bottom" style="background: #323e48; padding-top: 10px">
                            <div class="medium-12 small-12 columns">
                                <input type="text" id="search" placeholder="Search" autocomplete="off">
                            </div>
                            <div class="medium-12 small-12 columns">
                            <span>
                                <input type="checkbox" onclick="checkAllCheckbox(event)" id="id-all">
                                <label class="check all" for="id-all" >Выбрать всех</label>
                            </span>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <div class="row">
                                    <?php if(is_array($partnerList)):?>
                                        <?php foreach($partnerList as $partner):?>
                                            <div class="medium-4 small-4 columns">
                                                <span>
                                                    <?php $checkUser = $user->id_user == $partner['id_user'] ? true : false?>
                                                    <input type="checkbox" <?= $checkUser ? 'checked' : '' ?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                    <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= $checkUser ? 'green' : ''?>;"><?=$partner['name_partner'] ?></label><br>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="row align-bottom" style="padding-top: 10px; margin-top: 10px">
                        <div class="medium-3 small-3 medium-offset-9 columns">
                            <button type="submit" id="apply-stock-filter" class="button primary">Generate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif;?>

<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Request goods</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <table class="umbrella-table">
                <thead>
                <tr>
                    <th>Request ID</th>
                    <th>PartNumber</th>
                    <th>Goods Name</th>
                    <th>Service Order</th>
                    <th>Status</th>
                    <th>Date create</th>
                    <th>Period</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody id="container-details">

                </tbody>
            </table>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.deleted', 'view')): ?>
    <div class="reveal large" id="open-removed-request" data-reveal>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Deleted requests</h3>
            </div>
            <div class="medium-12 small-12 columns">

                <table class="umbrella-table">
                    <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Partner</th>
                        <th>Part Number</th>
                        <th>Part Description</th>
                        <th>SO Number</th>
                        <th>Price</th>
                        <th>Note</th>
                        <th>Date create</th>
                        <th width="70"></th>
                    </tr>
                    </thead>
                   <tbody>
                   <?php if(is_array($listRemovedRequest)):?>
                       <?php foreach ($listRemovedRequest as $removedRequest):?>
                           <tr>
                               <td><?= $removedRequest['id']?></td>
                               <td><?= $removedRequest['site_client_name']?></td>
                               <td><?= $removedRequest['part_number']?></td>
                               <td><?= $removedRequest['goods_name']?></td>
                               <td><?= $removedRequest['so_number']?></td>
                               <td><?= round($removedRequest['price'], 2)?></td>
                               <td><?= $removedRequest['note1']?></td>
                               <td><?= Umbrella\components\Functions::formatDate($removedRequest['created_on'])?></td>
                               <td><button data-reqid="<?= $removedRequest['id']?>" class="delete restored">restore</button></td>
                           </tr>
                       <?php endforeach;?>
                   <?php endif;?>
                   </tbody>
                </table>

            </div>
        </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif;?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
