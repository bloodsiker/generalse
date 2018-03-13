<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Returns</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">

                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>

                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom align-justify">
                            <div class="medium-4  small-12 columns">
                                <form action="/adm/crm/returns/filter/" method="get" id="kpi" class="form">
                                    <div class="row align-bottom">
                                        <div class="medium-4 text-left small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                            <input type="text" id="date-start" name="start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" required>
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                            <input type="text" id="date-end" name="end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>" required>
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="medium-3 small-12 columns form">
                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.return.export', 'view')): ?>
                                    <a class="button primary tool" id="export-button"><i class="fi-page-export"></i> Export to Excel</a>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('crm.return.attach', 'view')): ?>
                                    <button class="button primary tool" id="add-return-file"><i class="fi-plus"></i> Attach File</button>
                                <?php endif;?>
                            </div>
                            <div class="medium-3 medium-offset-2 small-12 columns form">
                                <form action="/adm/crm/returns/s/" method="get" class="form" data-abide novalidate>
                                    <input type="text" class="required search-input" placeholder="Search..." name="search" required>
                                    <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="purchase-attache-file">
            <div class="row">
                <div class="medium-12 small-12 columns purchase-file-send">
                    <form action="/adm/crm/import_returns" id="return-excel-send" method="post" enctype="multipart/form-data">
                        <div class="row align-bottom">
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
                                <div style="padding-bottom: 37px; color: #fff"><a href="/upload/attach_return/return_import.xls" style="color: #2ba6cb" download>download</a> a template file to import</div>
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
                    <?php if(isset($arr_error_return) && !empty($arr_error_return)):?>
                        <p>Произошла ошибка при возврате таких Service Order</p>
                        <ul>
                            <?php foreach($arr_error_return as $error):?>
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
                            <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-7 days') ?> &mdash;
                            <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                            <span id="count_refund" class="text-green">(<?php if (isset($allReturnsByPartner)) echo count($allReturnsByPartner) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <th>Return Number</th>
                            <th>Partner</th>
                            <th>Order Number</th>
                            <th>Service Order</th>
                            <th>Stock</th>
                            <th>Date</th>
                            <th>Part Number</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>File</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th class="text-center no-sort"><i class="fi-check"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($allReturnsByPartner)): ?>
                            <?php foreach ($allReturnsByPartner as $return): ?>
                                <tr data-return="<?=$return['stock_return_id']?>" class="goods">
                                    <td><?= $return['stock_return_id']?></td>
                                    <td><?= $return['site_client_name']?></td>
                                    <td><?= $return['order_number']?></td>
                                    <td><?= $return['so_number']?></td>
                                    <?php if(empty($return['stock_name'])):?>
                                        <td class="selectInTable">
                                            <select name="stock" class="required" required>
                                                <option value="" selected disabled>none</option>
                                                <?php foreach ($user->renderSelectStocks($user->getId(), 'returns') as $stock):?>
                                                    <option value="<?= $stock?>"><?= $stock?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </td>
                                    <?php else:?>
                                        <td class="stock">
                                            <?= $return['stock_name'];?>
                                        </td>
                                    <?php endif;?>
                                    <td><?= Umbrella\components\Functions::formatDate($return['created_on'])?></td>
                                    <td><?= $return['part_number']?></td>
                                    <td><?= $return['order_type']?></td>
                                    <td><?= $return['goods_name']?></td>
                                    <td class="text-center return-document <?= (empty($return['document']) || $return['document'] == ' ') ? 'red' : 'blue' ?>">
                                        <button data-open="open-upload-return">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td class="text-center return-note">
                                        <?php if(!empty($return['note'])): ?>
                                            <i class="fi-info tool-tip has-tip [tip-top]"
                                               data-tooltip
                                               title="<?= $return['note'] ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td></td>
                                    <?php $status_name = $return['status_name']?>
                                    <td class="status_return <?= Umbrella\models\crm\Returns::getStatusRequest($status_name)?>"><?= $status_name?></td>
                                    <td class="text-center">
                                        <?php if($return['update_status_from_site'] != 2):?>
                                            <button class="apply-cout"><i class="fi-check"></i></button>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                <?php elseif($user->isAdmin() || $user->isManager()):?>
                    <table class="umbrella-table">

                        <caption>Last recordings on
                            <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-14 days') ?> &mdash;
                            <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                            <span id="count_refund" class="text-green">(<?php if (isset($allReturnsByPartner)) echo count($allReturnsByPartner) ?>)</span>
                        </caption>

                        <thead>
                        <tr>
                            <th>Return Number</th>
                            <th>Partner</th>
                            <th>Order Number</th>
                            <th width="150px">Service Order</th>
                            <th>Stock</th>
                            <th>Date</th>
                            <th>Part Number</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>File</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th class="text-center no-sort"><i class="fi-check"></i></th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($allReturnsByPartner)): ?>
                            <?php foreach ($allReturnsByPartner as $return): ?>
                                <tr data-return="<?=$return['stock_return_id']?>" class="goods">
                                    <td><?= $return['stock_return_id']?></td>
                                    <td><?= $return['site_client_name']?></td>
                                    <td><?= $return['order_number']?></td>
                                    <td><?= $return['so_number']?></td>
                                    <?php if(empty($return['stock_name'])):?>
                                        <td class="selectInTable">
                                            <select name="stock" class="required" required>
                                                <option value="" selected disabled>none</option>
                                                <?php foreach ($user->renderSelectStocks($user->getId(), 'returns') as $stock):?>
                                                    <option value="<?= $stock?>"><?= $stock?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </td>
                                    <?php else:?>
                                        <td class="stock">
                                            <?= $return['stock_name'];?>
                                        </td>
                                    <?php endif;?>
                                    <td><?= Umbrella\components\Functions::formatDate($return['created_on'])?></td>
                                    <td><?= $return['part_number']?></td>
                                    <td><?= $return['order_type']?></td>
                                    <td><?= $return['goods_name']?></td>
                                    <td class="text-center return-document <?= (empty($return['document']) || $return['document'] == ' ') ? 'red' : 'blue' ?>">
                                        <button data-open="open-upload-return">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td class="text-center return-note">
                                        <?php if(!empty($return['note'])): ?>
                                            <i class="fi-info tool-tip has-tip [tip-top]"
                                               data-tooltip
                                               title="<?= $return['note'] ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <?php $status_name = $return['status_name']?>
                                    <td class="status_return <?= Umbrella\models\crm\Returns::getStatusRequest($status_name)?>"><?= $status_name?></td>
                                    <td class="text-center">
                                        <?php if($return['update_status_from_site'] != 2):?>
                                            <button class="apply-cout"><i class="fi-check"></i></button>
                                        <?php endif;?>
                                    </td>
                                    <td class="action-control">
                                        <?php if($status_name == 'Предварительный' || $status_name == 'В обработке'):?>
                                            <a href="" class="accept return-accept"><i class="fi-check"></i></a>
                                            <a href="" class="dismiss return-dismiss"><i class="fi-x"></i></a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<div class="reveal" id="export-modal" data-reveal>
    <form action="/adm/crm/export/returns/" id="" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Generate report</h3>
            </div>
            <?php if($user->isAdmin()):?>

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

            <?php elseif($user->isManager()):?>

                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner
                                <select name="id_partner" class="required" required>
                                    <option value="all">All partners</option>
                                    <?php $user->renderSelectControlUsers($user->getId());?>
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

<div class="reveal" id="note-modal" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Note</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" cols="30" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="medium-6 medium-offset-6 small-12 columns">
            <button type="submit" id="send-return" class="button primary">Apply</button>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="open-upload-return" data-reveal>
    <form action="" id="return-upload" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Upload document</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <div class="medium-12 small-12 columns">
                        <div class="container-upload-file">

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="attach_return" class="button primary">Attach</label>
                                <input type="file" id="attach_return" class="show-for-sr" name="attach_return" required>
                            </div>
                        </div>
                    </div>


                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <input type="hidden" name="return_id" value="">
                            </div>
                            <div class="medium-6 small-12 columns">
                                <button type="submit" id="return-upload-send" class="button primary">Upload File</button>
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
