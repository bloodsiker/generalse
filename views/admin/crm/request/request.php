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

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.part_analog_gm', 'view')): ?>
                                    <button data-open="find-part-analog-gm"  class="button primary tool"><i class="fi-plus"></i> Part Analog GM</button>
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
                <?php if($user->isPartner()):?>
                    <?php if($user->getGroupName() == 'UKRAINE OOW'):?>

                        <table class="umbrella-table">
                            <caption>List requests
                                <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                            </caption>
                            <thead>
                            <tr>
                                <th>Request id</th>
                                <th>Partner</th>
                                <th>Date create</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($listCheckOrders)):?>
                                <?php foreach($listCheckOrders as $order):?>
                                    <tr class="goods <?= (Umbrella\components\Functions::calcDiffSec($order['created_on']) < 120) ? 'check_lenovo_ok' : ''?>"
                                        <?= is_null($order['number'])? null : 'data-number=' . $order['number']?>>
                                        <td><?= is_null($order['number'])? $order['id'] : 'Multi-request ' . $order['number']?></td>
                                        <td><?= Umbrella\components\Decoder::strToUtf($order['site_client_name'])?></td>
                                        <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <table class="umbrella-table">
                            <caption>List requests
                                <span id="count_refund" class="text-green">(<?php if (isset($listCheckOrders)) echo count($listCheckOrders) ?>)</span>
                            </caption>
                            <thead>
                            <tr>
                                <th>Request id</th>
                                <th>Partner</th>
                                <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
                                    <th>Partner status</th>
                                <?php endif?>
                                <th>Part Number</th>
                                <th>Part Description</th>
                                <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
                                    <th>Subtype</th>
                                <?php endif?>
                                <th>SO Number</th>
                                <th>Price uah</th>
                                <?php if($user->getGroupName() == 'Electrolux'):?>
                                    <th>Price euro</th>
                                <?php endif?>
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
                                        <td><?= \Umbrella\components\Decoder::strToUtf($order['site_client_name'])?></td>
                                        <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
                                            <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['site_client_status'])?></td>
                                        <?php endif?>
                                        <td style="background: <?= in_array($order['part_number'], $arrayPartNumber) ? '#f79898' : 'inherit'?>">
                                            <?= $order['part_number']?>
                                        </td>
                                        <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['goods_name'])?></td>
                                        <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
                                            <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['subtype_name'])?></td>
                                        <?php endif?>
                                        <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></td>
                                        <td><?= str_replace('.',',', round($order['price'], 2))?></td>
                                        <?php if($user->getGroupName() == 'Electrolux'):?>
                                            <td><?= str_replace('.',',', round($order['price_euro'], 2))?></td>
                                        <?php endif;?>
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
                                        <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?>
                                            <?php if (!empty($order['expected_date'])): ?>
                                                <?= \Umbrella\components\Functions::formatDate($order['expected_date']) ?>
                                            <?php endif; ?>

                                        </td>
                                        <td><?= Umbrella\components\Functions::formatDate($order['created_on'])?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    <?php endif;?>

                <?php elseif($user->isAdmin()
                    || $user->isManager()):?>
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
                            <th>Part Description</th>
                            <th>Subtype</th>
                            <th>SO Number</th>
                            <th>Price uah</th>
                            <th>Price euro</th>
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
                                    <td><?= \Umbrella\components\Decoder::strToUtf($order['site_client_name'])?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['site_client_status'])?></td>
                                    <td data-pn="<?= $order['part_number']?>" class="order-tr-pn" style="background: <?= in_array($order['part_number'], $arrayPartNumber) ? '#f79898' : 'inherit'?>">
                                        <span class="order_part_num"><?= $order['part_number']?></span>
                                        <?php if($user->isAdmin() || $user->isManager()):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.pn_edit', 'view')): ?>
                                                <a href="" class="button edit-pn delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td class="order-tr-goods-name">
                                        <span class="pn_goods_name"><?= iconv('WINDOWS-1251', 'UTF-8', $order['goods_name'])?></span>
                                        <?php if($user->isAdmin() || $user->isManager()):?>
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
                                        <?php if($user->isAdmin() || $user->isManager()):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.request.so_edit', 'view')): ?>
                                                <a href="" class="button edit-so delete"><i class="fi-pencil"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </td>
                                    <td><?= str_replace('.',',', round($order['price'], 2))?></td>
                                    <td><?= str_replace('.',',', round($order['price_euro'], 2))?></td>
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
                                        <?php if (!empty($order['expected_date'])): ?>
                                            <span class="expected_date"><?= \Umbrella\components\Functions::formatDate($order['expected_date']) ?></span>
                                        <?php else: ?>
                                            <span class="expected_date"></span>
                                        <?php endif; ?>

                                        <?php if($user->isAdmin() || $user->isManager()):?>
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
    <?php require(views_path('admin/crm/request/_part/request_modal.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.multi-request.send', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/multi_request_modal.php'))?>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.part_analog_gm', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_part_analog_gm.php'))?>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.price', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_price.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_import.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.import.status', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_import_status.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.pn_edit', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_pn_edit.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.so_edit', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_so_edit.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.status_edit', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_status_edit.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.upload.price', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_upload_price.php'))?>
<?php endif?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.allprice', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_all_price.php'))?>
<?php endif?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request.export', 'view')): ?>
    <?php require(views_path('admin/crm/request/_part/request_export.php'))?>
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
                    <th>Price</th>
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
    <?php require views_path('admin/crm/request/_part/request_deleted.php')?>
<?php endif;?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
