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
                                <?php if (AdminBase::checkDenied('crm.orders.add', 'view')): ?>
                                    <button class="button primary tool" id="add-checkout-button"><i class="fi-plus"></i> Add</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.orders.attach', 'view')): ?>
                                    <button class="button primary tool" id="add-order-file"><i class="fi-plus"></i> Attach File</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.orders.export', 'view')): ?>
                                    <a class="button primary tool" id="export-button"><i class="fi-page-export"></i> Export to Excel</a>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.orders.batch', 'view')): ?>
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
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="purchase-attache-file">
            <div class="row">
                <div class="medium-12 small-12 columns purchase-file-send">
                    <form action="/adm/crm/orders" id="orders-excel-send" method="post" enctype="multipart/form-data">
                        <div class="row align-bottom">
                            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                <div class="medium-2 small-12 columns">
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
                            <?php elseif ($user->role == 'partner'):?>
                                <div class="medium-2 small-12 columns">
                                    <label><i class="fi-list"></i> Partner</label>
                                    <select name="id_partner" id="id_partner_one" class="required" required>
                                        <?php $user->renderSelectControlUsers($user->id_user);?>
                                    </select>
                                </div>
                            <?php endif;?>
                            <div class="medium-2 small-12 columns">
                                <label><i class="fi-list"></i> Stock
                                    <select name="stock" class="required" required>
                                        <option value="" selected disabled>none</option>
                                        <?php if($user->name_partner == 'GS Electrolux'
                                            || $user->name_partner == 'Electrolux Amati Service'
                                            || $user->name_partner == 'Electrolux DE Service'
                                            || $user->name_partner == 'Electrolux Electroservice'
                                            || $user->name_partner == 'Electrolux Kaskad-1'
                                            || $user->name_partner == 'Electrolux Meridian'
                                            || $user->name_partner == 'Electrolux Transservice'):?>
                                            <option value="OK (Выборгская, 104)">OK (Выборгская, 104)</option>
                                            <option value="BAD">BAD</option>
                                        <?php else:?>
                                            <option value="BAD">BAD</option>
                                            <option value="Not Used">Not Used</option>
                                            <option value="Restored">Restored</option>
                                            <option value="Dismantling">Dismantling</option>
                                        <?php endif;?>
                                    </select>
                                </label>
                            </div>
                            <input type="hidden" name="send_excel_file" value="true">
                            <div class="medium-2 small-12 columns">
                                <label for="exampleFileUpload" class="button primary">Attach</label>
                                <input type="file" id="exampleFileUpload" class="show-for-sr" name="excel_file" required>
                            </div>
                            <div class="medium-2 small-12 columns">
                                <button class="button primary"> send
                                </button>
                            </div>
                            <div class="medium-4 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a href="/upload/attach_order/orders_import.xls" style="color: #2ba6cb" download>download</a> a template file to import</div>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Note</label>
                                <textarea rows="3" name="notes"></textarea>
                            </div>
                        </div>
                    </form>
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
                <?php if($user->role == 'partner'):?>
                <table id="goods_data">
                    <caption>Last recordings on
                        <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                        <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                        <span id="count_refund" class="text-green">(<?php if (isset($allOrders)) echo count($allOrders) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th class="sort">Partner</th>
                        <th class="sort">Order Number</th>
                        <th class="sort">Service Order</th>
                        <th class="sort">Status</th>
                        <th class="text-center" width="70">Note</th>
                        <th class="sort">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($allOrders)):?>
                        <?php foreach($allOrders as $order):?>
                            <tr data-order-id="<?=$order['order_id']?>" class="goods">
                                <td><?= $order['site_client_name']?></td>
                                <td><?= $order['order_number']?></td>
                                <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></td>
                                <?php $status_name = iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?>
								<td class="<?=Orders::getStatusRequest($status_name);?>">
                                    <?= $status_name?>
                                    <?php if($status_name == 'Отказано'):?>
                                        <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                           data-tooltip aria-haspopup="true"
                                           data-show-on="small"
                                           data-click-open="true"
                                           title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['command_text'])?>"></i>
                                    <?php endif;?>
                                </td>
                                <td class="text-center">
                                <?php if(isset($order['note']) && $order['note'] != ' '):?>
                                    <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                       data-tooltip aria-haspopup="true"
                                       data-show-on="small"
                                       data-click-open="true"
                                       title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['note'])?>"></i>
                                <?php endif;?>
                                </td>
                                <td><?= Functions::formatDate($order['created_on'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                    <table id="goods_data">
                        <?php if(isset($_GET['start']) && !empty($_GET['start'])):?>
                            <caption>Last recordings on
                                <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                                <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                                <span id="count_refund" class="text-green">(<?php if (isset($allOrders)) echo count($allOrders) ?>)</span>
                            </caption>
                        <?php endif;?>
                        <thead>
                        <tr>
                            <th class="sort">Order id</th>
                            <th class="sort">Partner</th>
                            <th class="sort">Order Number</th>
                            <th class="sort">Service Order</th>
                            <th class="sort">Status</th>
                            <th class="text-center" width="70">Note</th>
                            <th class="sort">Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allOrders)):?>
                            <?php foreach($allOrders as $order):?>
                                <tr data-order-id="<?=$order['order_id']?>" class="goods">
                                    <td><?= $order['order_id']?></td>
                                    <td><?= $order['site_client_name']?></td>
                                    <td><?= $order['order_number']?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $order['so_number'])?></td>
                                    <?php $status_name = iconv('WINDOWS-1251', 'UTF-8', $order['status_name'])?>
                                    <td class="<?=Orders::getStatusRequest($status_name);?>">
                                        <?= $status_name?>
                                        <?php if($status_name == 'Отказано'):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['command_text'])?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isset($order['note']) && $order['note'] != ' '):?>
                                            <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                               data-tooltip aria-haspopup="true"
                                               data-show-on="small"
                                               data-click-open="true"
                                               title="<?= iconv('WINDOWS-1251', 'UTF-8', $order['note'])?>"></i>
                                        <?php endif;?>
                                    </td>
                                    <td><?= Functions::formatDate($order['created_on'])?></td>
                                    <td class="action-control">
                                        <?php if($status_name == 'Предварительный' || $status_name == 'В обработке'):?>
                                        <a href="" class="accept order-accept"><i class="fi-check"></i></a>
                                        <a href="" class="dismiss order-dismiss"><i class="fi-x"></i></a>
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
            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
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
            <?php elseif ($user->role == 'partner'):?>
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
                    <?php if($user->name_partner == 'GS Electrolux'
                        || $user->name_partner == 'Electrolux Amati Service'
                        || $user->name_partner == 'Electrolux DE Service'
                        || $user->name_partner == 'Electrolux Electroservice'
                        || $user->name_partner == 'Electrolux Kaskad-1'
                        || $user->name_partner == 'Electrolux Meridian'
                        || $user->name_partner == 'Electrolux Transservice'):?>
                        <option value="OK (Выборгская, 104)">OK (Выборгская, 104)</option>
                        <option value="BAD">BAD</option>
                    <?php elseif($user->name_partner == 'Techpoint'
                        || $user->name_partner == 'Technoservice'
                        || $user->name_partner == 'Servisexpress'
                        || $user->name_partner == 'GS Electrolux GE'):?>
                        <option value="OK">OK</option>
                        <option value="BAD">BAD</option>
                    <?php else:?>
                        <option value="BAD">BAD</option>
                        <option value="Not Used">Not Used</option>
                        <option value="Restored">Restored</option>
                        <option value="Dismantling">Dismantling</option>
                    <?php endif;?>
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
                <label>Quantity <span style="color: #4CAF50;" class="quantity-product"></span></label>
                <input type="number" value="1" min="1" max="50" class="required" name="quantity" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Note</label>
                <textarea rows="3" name="note"></textarea>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal" id="export-modal" data-reveal>
    <form action="/adm/crm/export/orders/" id="" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Generate report</h3>
            </div>
            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner
                                <select name="id_partner" class="required" required>
                                    <option value="all">All partners</option>
                                    <?php if(is_array($partnerList)):?>
                                        <?php foreach($partnerList as $partner):?>
                                            <option value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <div class="medium-12 small-12 columns">
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
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Generate</button>
            </div>
        </div>
    </form>
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


<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Purchase goods</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <table>
                <thead>
                <tr>
                    <th>PartNumber</th>
                    <th>Goods Name</th>
                    <th>Service Order</th>
                    <th>Stock name</th>
                    <th>Quantity</th>
                    <th>Price</th>
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
