<?php require_once views_path('admin/layouts/header.php') ?>

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

                            <?php require_once views_path('admin/layouts/crm_menu.php') ?>

                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-5 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.add', 'view')): ?>
                                    <button class="button primary tool" <?= $user->getUserBlockedGM() == 'blocked' ? 'disabled' : null ?> id="add-checkout-button"><i class="fi-plus"></i> Add</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.attach', 'view')): ?>
                                    <button data-open="add-order-import-modal" <?= $user->getUserBlockedGM() == 'blocked' ? 'disabled' : null ?> class="button primary tool" id="add-order-file"><i class="fi-plus"></i> Attach File</button>
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
                        <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
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
                                <?php if($user->getName() == 'GS Electrolux' || $user->getName() == 'GS Electrolux GE'):?>
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
                                        <?php if($status_name != 'Выдан'):?>
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

<?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.add', 'view')): ?>
    <?php if($user->getUserBlockedGM() != 'blocked'): ?>
        <?php require(views_path('admin/crm/orders/_part/add_order.php'))?>
    <?php endif; ?>
<?php endif; ?>

<!--=== EXPORT EXCEL ====-->
<?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.export', 'view')): ?>
    <?php require(views_path('admin/crm/orders/_part/export_excel.php'))?>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.batch', 'view')): ?>
    <?php require(views_path('admin/crm/orders/_part/order_batch.php'))?>
<?php endif; ?>



<?php if (Umbrella\app\AdminBase::checkDenied('crm.orders.attach', 'view')): ?>
    <?php if($user->getUserBlockedGM() != 'blocked'): ?>
        <?php require(views_path('admin/crm/orders/_part/add_order_import.php'))?>
    <?php endif; ?>
<?php endif; ?>


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

<?php require_once views_path('admin/layouts/footer.php') ?>
