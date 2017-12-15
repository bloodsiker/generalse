<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Orders</h1>
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
                            <div class="medium-5 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.add', 'view')): ?>
                                    <button class="button primary tool" id="add-checkout-button"><i class="fi-plus"></i> Add</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.attach', 'view')): ?>
                                    <button data-open="add-order-import-modal" class="button primary tool" id="add-order-file"><i class="fi-plus"></i> Attach File</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.export', 'view')): ?>
                                    <button data-open="export-modal" class="button primary tool"><i class="fi-page-export"></i> Export to Excel</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.batch', 'view')): ?>
                                    <button data-open="add-supply-modal" class="button primary tool"><i class="fi-plus"></i>
                                        Check Batch
                                    </button>
                                <?php endif;?>
                            </div>
                            <div class="medium-4  small-12 columns">
                                <form action="/adm/crm/orders/" method="get" id="kpi" class="form">
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
                                <form action="/adm/crm/orders/s/" method="get" class="form" data-abide novalidate>
                                    <input type="text" class="required search-input" placeholder="Search..." name="search" required>
                                    <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <?php if(isset($arr_error_pn)):?>
                        <p><?php if(isset($arr_error_text)) echo $arr_error_text;?></p>
                        <ul>
                            <?php foreach($arr_error_pn as $error):?>
                                <li><?=$error ?></li>
                            <?php endforeach;?>
                        </ul>
                        <p>Обратитесь пожалуйста к менеджеру.</p>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <?php if($user->isPartner()):?>
                <table class="umbrella-table">
                    <caption>Last recordings on
                        <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-14 days') ?> &mdash;
                        <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                        <span id="count_refund" class="text-green">(<?php if (isset($allOrders)) echo count($allOrders) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                            <th class="sort">Request id</th>
                        <?php endif; ?>
                        <th class="sort">Partner</th>
                        <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                            <th>Partner status</th>
                        <?php endif?>
                        <th class="sort">Order Number</th>
                        <th class="sort">Service Order</th>
                        <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.type_repair', 'view')): ?>
                            <th class="sort">Type</th>
                        <?php endif; ?>
                        <th>Note</th>
                        <th class="sort">Status</th>
                        <th class="text-center" width="70">Address</th>
                        <th class="sort">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($allOrders)):?>
                        <?php foreach($allOrders as $order):?>
                            <tr ondblclick="showDetailOrders(<?=$order['order_id']?>, <?=$order['site_account_id']?>)" data-order-id="<?=$order['order_id']?>" class="goods">
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                                    <td><?= $order['request_id']?></td>
                                <?php endif;?>
                                <td><?= $order['site_client_name']?></td>
                                <?php if($user->name_partner == 'GS Electrolux' || $user->name_partner == 'GS Electrolux GE'):?>
                                    <td><?= $order['site_client_status']?></td>
                                <?php endif?>
                                <td><?= $order['order_number']?></td>
                                <td><?= $order['so_number']?></td>
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.type_repair', 'view')): ?>
                                    <td><?= $order['type_name']?></td>
                                <?php endif; ?>

                                <td class="text-center">
                                    <?php if($order['note1'] != ' ' && $order['note1'] != null):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= $order['note1']?>"></i>
                                    <?php endif;?>
                                </td>

                                <?php $status_name = $order['status_name']?>
								<td class="<?= Umbrella\models\Orders::getStatusRequest($status_name);?>">
                                    <?= $status_name?>
                                    <?php if($status_name == 'Отказано'):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= $order['command_text']?>"></i>
                                    <?php endif;?>
                                    <?php if($status_name == 'Выдан'): ?>
                                        <br><?= Umbrella\components\Functions::formatDate($order['shipped_on'])?>
                                    <?php endif;?>
                                </td>
                                <td class="text-center">
                                <?php if(isset($order['note']) && $order['note'] != ' '):?>
                                    <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                       data-tooltip aria-haspopup="true"
                                       data-show-on="small"
                                       data-click-open="true"
                                       title="<?= $order['note']?>"></i>
                                <?php endif;?>
                                </td>
                                <td>
                                    <?= Umbrella\components\Functions::formatDate($order['created_on'])?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>

                <?php elseif($user->isAdmin() || $user->isManager()):?>

                    <table class="umbrella-table">
                        <?php if(isset($_GET['start']) && !empty($_GET['start'])):?>
                            <caption>Last recordings on
                                <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                                <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                                <span id="count_refund" class="text-green">(<?php if (isset($allOrders)) echo count($allOrders) ?>)</span>
                            </caption>
                        <?php endif;?>
                        <thead>
                        <tr>
                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                                <th>Request id</th>
                            <?php endif;?>
                            <th class="sort">Partner</th>
                            <th>Partner status</th>
                            <th>Order Number</th>
                            <th class="sort">Service Order</th>
                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.type_repair', 'view')): ?>
                                <th class="sort">Type</th>
                            <?php endif;?>
                            <th>Note</th>
                            <th class="sort">Status</th>
                            <th class="text-center" width="70">Address</th>
                            <th class="sort">Date</th>
                            <th>Reserve</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allOrders)):?>
                            <?php foreach($allOrders as $order):?>
                                <tr ondblclick="showDetailOrders(<?=$order['order_id']?>, <?=$order['site_account_id']?>)"  data-order-id="<?=$order['order_id']?>" class="goods">
                                    <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.request_id', 'view')): ?>
                                        <td><?= $order['request_id']?></td>
                                    <?php endif;?>
                                    <td><?= $order['site_client_name']?></td>
                                    <td><?= $order['site_client_status']?></td>
                                    <td><?= $order['order_number']?></td>
                                    <td><?= $order['so_number']?></td>
                                    <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.type_repair', 'view')): ?>
                                        <td><?= $order['type_name']?></td>
                                    <?php endif;?>

                                    <td class="text-center">
                                        <?php if($order['note1'] != ' ' && $order['note1'] != null):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= $order['note1']?>"></i>
                                        <?php endif;?>
                                    </td>

                                    <?php $status_name = $order['status_name']?>
                                    <td class="<?=Umbrella\models\Orders::getStatusRequest($status_name);?>">
                                        <?= $status_name?>
                                        <?php if($status_name == 'Отказано'):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= $order['command_text']?>"></i>
                                        <?php endif;?>
                                        <?php if($status_name == 'Выдан'): ?>
                                            <br><?= Umbrella\components\Functions::formatDate($order['shipped_on'])?>
                                        <?php endif;?>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isset($order['note']) && $order['note'] != ' '):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= $order['note']?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?= Umbrella\components\Functions::formatDate($order['created_on'])?>
                                    </td>
                                    <td>
                                        <?= Umbrella\components\Functions::formatDate($order['to_date'])?>
                                    </td>
                                    <td>
                                        <?= $order['created_by']?>
                                    </td>
                                    <td class="action-control">
                                    <?php if($status_name == 'Предварительный' || $status_name == 'В обработке' || $status_name == 'Резерв'):?>
                                        <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.accept', 'view')): ?>
                                            <a href="" class="accept order-accept"><i class="fi-check"></i></a>
                                        <?php endif;?>

                                        <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.dismiss', 'view')): ?>
                                            <a href="" class="dismiss order-dismiss"><i class="fi-x"></i></a>
                                        <?php endif;?>
                                    <?php endif;?>

                                    <?php if(isset($order['request_id'])):?>
                                        <?php if($status_name != 'Выдан' && $status_name != 'Отказано'):?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.return_to_request', 'view')): ?>
                                            <a href="" data-request-id="<?=$order['request_id']?>" class="return order-return"><i class="fi-loop"></i></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    <?php endif;?>
                                    </td>
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
<div class="reveal" id="add-checkout-modal" data-reveal>
    <form action="" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New checkout</h3>
            </div>
            <?php if($user->isAdmin()):?>

                <div class="medium-12 small-12 columns">
                    <label>Partner</label>
                    <select name="id_partner" id="id_partner" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php if(is_array($partnerList)):?>
                            <?php foreach($partnerList as $partner):?>
                                <option value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>

            <?php elseif ($user->isManager() || $user->isPartner()):?>

                <div class="medium-12 small-12 columns">
                    <label><i class="fi-list"></i> Partner</label>
                    <select name='id_partner' id='id_partner' class='required' required>
                        <?php $user->renderSelectControlUsers($user->id_user);?>
                    </select>
                </div>

            <?php endif;?>

            <div class="medium-12 small-12 columns">
                <label>Stock</label>
                <select name="stock" id="stock" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($user->renderSelectStocks($user->id_user, 'order') as $stock):?>
                        <option value="<?= $stock?>"><?= $stock?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Service Order</label>
                <input type="text" class="required" name="service_order" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Type</label>
                <select name="order_type_id" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($order_type as $type):?>
                        <option value="<?= $type['id']?>"><?= $type['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Quantity <span style="color: #4CAF50;" class="quantity-product"></span></label>
                <input type="number" value="1" min="1" max="50" class="required" name="quantity" required>
            </div>
            <div class="medium-12 small-12 columns hide">
                <label>Note</label>
                <textarea rows="3" name=""></textarea>
            </div>

            <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                <div class="medium-12 small-12 columns">
                    <label>Delivery address</label>
                    <select name="note" id="note" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php foreach ($delivery_address as $address):?>
                            <option value="<?= $address?>"><?= $address?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>



<!--=== EXPORT EXCEL ====-->
<div class="reveal large" id="export-modal" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Generate report</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <form action="/adm/crm/export/orders/" method="POST" id="form-generate-excel" data-abide>

                <h4 style="color: #fff">Between date</h4>
                <div class="row align-bottom" style="background: #323e48; padding-top: 10px; margin-bottom: 10px">
                    <div class="medium-6 small-6 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <label>From Date</label>
                                <input type="text" class="required date" name="start" required>
                            </div>
                            <div class="medium-6 small-12 columns">
                                <label>To Date</label>
                                <input type="text" class="required date" name="end" required>
                            </div>
                        </div>
                    </div>
                    <div class="medium-3 small-3 columns">
                        <label>Status</label>
                        <select name="status_name" id="status_name">
                            <option value="">none</option>
                            <option value="В обработке">В обработке</option>
                            <option value="Предварительный">Предварительный</option>
                            <option value="Отказано">Отказано</option>
                            <option value="Выдан">Выдан</option>
                            <option value="Резерв">Резерв</option>
                        </select>
                    </div>
                    <div class="medium-3 small-3 columns">
                        <label>Type</label>
                        <select name="order_type_id">
                            <option value="">none</option>
                            <option value="1">Гарантия</option>
                            <option value="2">Негарантия</option>
                        </select>
                    </div>
                </div>

                <h4 style="color: #fff">Partners</h4>
                <?php if($user->isAdmin()):?>
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
                                                    <label class="check all" for="id-<?= $groups['group_id']?>-all">Выбрать всех</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php foreach($groups['users'] as $partner):?>
                                                <div class="medium-4 small-4 columns">
                                                <span>
                                                    <?php $checked = Umbrella\models\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                                    <input type="checkbox" <?= ($checked ? 'checked' : '')?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                    <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$partner['name_partner'] ?></label><br>
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
                                                <?php $checked = Umbrella\models\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                                <?php $checkUser = $user->id_user == $partner['id_user'] ? true : false?>
                                                <input type="checkbox" <?= ($checked || $checkUser) ? 'checked' : ''?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked || $checkUser) ? 'green' : ''?>;"><?=$partner['name_partner'] ?></label><br>
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


<div class="reveal" id="add-supply-modal" data-reveal>
    <form action="/adm/crm/export/batch" id="add-batch-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Batch</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
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
                                            href="/upload/attach_batch/Batch Upload.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="check_butch" value="true">
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Check</button>
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


<div class="reveal" id="add-order-import-modal" data-reveal>
    <form action="/adm/crm/orders" id="orders-excel-send" method="post" enctype="multipart/form-data" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import orders</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <?php if($user->role == 'administrator' || $user->role == 'administrator-fin'):?>

                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner</label>
                            <select name="id_partner" id="id_partner_one" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php if(is_array($partnerList)):?>
                                    <?php foreach($partnerList as $partner):?>
                                        <option <?php echo (isset($id_partner) && $id_partner == $partner['id_user']) ? 'selected' : '' ?> value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>

                    <?php elseif ($user->role == 'manager' || $user->role == 'partner'):?>

                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner</label>
                            <select name="id_partner" id="id_partner_one" class="required" required>
                                <?php $user->renderSelectControlUsers($user->id_user);?>
                            </select>
                        </div>

                    <?php endif;?>


                    <div class="medium-12 small-12 columns">
                        <label><i class="fi-list"></i> Stock
                            <select name="stock" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php foreach ($user->renderSelectStocks($user->id_user, 'order') as $stock):?>
                                    <option value="<?= $stock?>"><?= $stock?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>

                    <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                        <div class="medium-12 small-12 columns">
                            <label>Delivery address</label>
                            <select name="notes" id="notes" class="required" required>
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
                                <option value="<?= $type['id']?>"><?= $type['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>


                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="exampleFileUpload" class="button primary">Attach</label>
                                <input type="file" id="exampleFileUpload" class="show-for-sr" name="excel_file" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="send_excel_file" value="true">


                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                            href="/upload/attach_order/orders_import.xls" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
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


<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Order goods</h3>
        </div>
        <div class="medium-12 small-12 columns" id="container-details">

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
