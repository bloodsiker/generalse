<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Purchase</h1>
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
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.purchase.add', 'view')): ?>
                                    <button class="button primary tool" id="add-checkout-button"><i class="fi-plus"></i> Add</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.purchase.attach', 'view')): ?>
                                    <button class="button primary tool" id="add-checkout-file"><i class="fi-plus"></i> Attach File</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.purchase.export', 'view')): ?>
                                    <a class="button primary tool" id="export-button"><i class="fi-page-export"></i> Export to Excel</a>
                                <?php endif;?>
                            </div>
                            <div class="medium-4  small-12 columns">
                                <form action="/adm/crm/purchase/" method="get" id="kpi" class="form">
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
                            <div class="medium-3 medium-offset-1 small-12 columns form">
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
		<div class="purchase-attache-file">
            <div class="row">
                <div class="medium-12 small-12 columns purchase-file-send">
                    <form action="/adm/crm/purchase" id="purchase-excel-send" method="post" enctype="multipart/form-data">
                        <div class="row align-bottom">
						<?php if($user->role == 'administrator' || $user->role == 'administrator-fin'):?>
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

                            <?php elseif ($user->role == 'manager'):?>

                                <div class="medium-2 small-12 columns">
                                    <label><i class="fi-list"></i> Partner</label>
                                    <select name="id_partner" id="id_partner_one" class="required" required>
                                        <?php $user->renderSelectControlUsers($user->id_user);?>
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
                                        <?php foreach ($user->renderSelectStocks($user->id_user, 'purchase') as $stock):?>
                                            <option value="<?= $stock?>"><?= $stock?></option>
                                        <?php endforeach;?>
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
                                <div style="padding-bottom: 37px; color: #fff"><a href="/upload/attach_purchase/purchase_import.xls" style="color: #2ba6cb" download>download</a> a template file to import</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="body-content checkout">
			<div class="row">
                <div class="medium-12 small-12 columns">
                    <?php if(isset($arr_check_stock)):?>
                        <p>Эти парт номера были найдены на таких складах:</p>
                        <ul>
                            <?php foreach($arr_check_stock as $pn_stock):?>
                                <li><?=$pn_stock['part_number'] ?> найден на <?=$pn_stock['stock_name'] ?></li>
                            <?php endforeach;?>
                        </ul>
                        <p>Пожалуйста, оформите заказ.</p>
                    <?php endif;?>
                    <?php if(isset($arr_error_pn)):?>
                        <p>Заявка отправлена кроме парт номеров, которые не найдены в базе:</p>
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
                <table class="umbrella-table" id="goods_data">
                    <caption>Last recordings on
                        <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                        <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                        <span id="count_refund" class="text-green">(<?php if (isset($listPurchases)) echo count($listPurchases) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th scope="col" width="150px" class="sort">Purchase Number</th>
                        <th scope="col" class="sort">Partner</th>
                        <th scope="col" class="sort">Stock</th>
                        <th scope="col" class="sort">Status</th>
                        <th scope="col" class="sort">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listPurchases)):?>
                        <?php foreach($listPurchases as $purchase):?>
                            <tr data-siteid="<?=$purchase['purchase_id']?>" data-purchase=""
                                class="<?php echo(Umbrella\components\Functions::calcDiffSec($purchase['created_on']) < 120) ? 'check_lenovo_ok' : ''?>">
                                <td><?=$purchase['purchase_id'] ?></td>
                                <td><?=$purchase['site_client_name'] ?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $purchase['stock_name'])?></td>
								<?php $status = iconv('WINDOWS-1251', 'UTF-8', $purchase['status_name'])?>
								<td class="<?= Umbrella\models\Purchases::getStatusRequest($status)?>"><?= ($status == NULL) ? 'Expect' : $status?></td>
                                <td><?=Umbrella\components\Functions::formatDate($purchase['created_on'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                    <table class="umbrella-table" id="goods_data">
                        <caption>Last recordings on
                            <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                            <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                            <span id="count_refund" class="text-green">(<?php if (isset($listPurchases)) echo count($listPurchases) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <th scope="col" width="150px" class="sort">Purchase Number</th>
                            <th scope="col" class="sort">Partner</th>
                            <th scope="col" class="sort">Stock</th>
                            <th scope="col" class="sort">Status</th>
                            <th scope="col" class="sort">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listPurchases)):?>
                            <?php foreach($listPurchases as $purchase):?>
                                <tr data-siteid="<?=$purchase['purchase_id']?>" data-purchase=""
                                    class="<?php echo(Umbrella\components\Functions::calcDiffSec($purchase['created_on']) < 120) ? 'check_lenovo_ok' : ''?>">
                                    <td><?=$purchase['purchase_id'] ?></td>
                                    <td><?=$purchase['site_client_name']?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $purchase['stock_name'])?></td>
                                    <?php $status = iconv('WINDOWS-1251', 'UTF-8', $purchase['status_name'])?>
                                    <td class="<?= Umbrella\models\Purchases::getStatusRequest($status)?>"><?= ($status == NULL) ? 'Expect' : $status?></td>
                                    <td><?=Umbrella\components\Functions::formatDate($purchase['created_on'])?></td>
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
    <form action="#" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New checkout</h3>
            </div>
			<?php if($user->role == 'administrator' || $user->role == 'administrator-fin'):?>

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

            <?php elseif ($user->role == 'manager'):?>

                <div class="medium-12 small-12 columns">
                    <label><i class="fi-list"></i> Partner</label>
                    <select name='id_partner' id='id_partner' class='required' required>
                        <?php $user->renderSelectControlUsers($user->id_user);?>
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
                    <?php foreach ($user->renderSelectStocks($user->id_user, 'purchase') as $stock):?>
                        <option value="<?= $stock?>"><?= $stock?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Service Order</label>
                <input type="text" class="required" name="service_order" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product">Lenovo A 1000</span></label>
				<span class="result_stock" style="color: orange;"></span>
                <input type="text" class="required" name="part_number" autocomplete="off" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Price</label>
                <input type="number" step="0.10" class="required" name="price" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Quantity</label>
                <input type="number" value="1" min="1" max="50" class="required" name="quantity" required>
            </div>
            <div class="medium-12 small-12 columns">
                <span style="color: red" class="error_form_purchases"></span>
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
            <form action="/adm/crm/export/purchase/" method="POST" id="form-generate-excel" data-abide>

                <h4 style="color: #fff">Between date</h4>
                <div class="row align-bottom" style="background: #323e48; padding-top: 10px; margin-bottom: 10px">
                    <div class="medium-8 small-8 columns">
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
                    <div class="medium-4 small-4 columns">
                        <label>Status</label>
                        <select name="status_name" id="status_name">
                            <option value="">none</option>
                            <option value="Покупка (принята)">Покупка (принята)</option>
                            <option value="Покупка (не принята)">Покупка (не принята)</option>
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
                                                <input type="checkbox" <?= ($checked ? 'checked' : '')?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$partner['name_partner'] ?></label><br>
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


<!-- OLD EXPORT  -->
<div class="reveal" id="export-modal2" data-reveal>
    <form action="/adm/crm/export/purchase/" id="" method="get" class="form" data-abide novalidate>
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
                                    <option value="all">All partner</option>
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

<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Purchase goods</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <table class="umbrella-table">
                <thead>
                <tr>
                    <th>PartNumber</th>
                    <th>Service Order</th>
                    <th>Goods Name</th>
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
