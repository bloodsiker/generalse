<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Supply</h1>
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
                            <div class="medium-3 small-12 columns">

                                <?php if (AdminBase::checkDenied('crm.supply.accept', 'view')): ?>
                                    <button data-open="add-supply-modal" class="button primary tool"><i class="fi-plus"></i>
                                        Create
                                    </button>
                                <?php endif;?>


                                <?php if (AdminBase::checkDenied('crm.supply.create', 'view')): ?>
                                    <button class="button primary tool" disabled id="send_button" onclick="send()"><i class="fi-check"></i> Accept</button>
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
                <div class="medium-12 small-12 columns">
                    <?php if(isset($supply_error_part) && !empty($supply_error_part)):?>
                        <p>Заявка отправлена кроме парт номеров, которые не найдены в базе:</p>
                        <ul>
                            <?php foreach($supply_error_part as $error):?>
                                <li><?=$error ?></li>
                            <?php endforeach;?>
                        </ul>
                        <p>Обратитесь пожалуйста к менеджеру.</p>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <?php if($user->role == 'partner'):?>

                    <table>
                        <thead>
                        <tr>
                            <th class="sort">Number</th>
                            <th class="sort">Arriving Date</th>
                            <th class="sort">Status</th>
                            <?php if (AdminBase::checkDenied('crm.supply.accept', 'view')): ?>
                                <th class="sort">Checked</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allSupply)):?>
                            <?php foreach ($allSupply as $supply):?>
                                <?php $status = iconv('WINDOWS-1251', 'UTF-8', $supply['status_name'])?>
                                <tr data-siteid="<?=$supply['site_id']?>" class="">
                                    <td><?=$supply['supply_id']?></td>
                                    <td><?=Functions::formatDate($supply['expected_arriving_date'])?></td>
                                    <td class="<?=Supply::getStatusSupply($status)?>"><?=$status?></td>
                                    <?php if (AdminBase::checkDenied('crm.supply.accept', 'view')): ?>
                                        <td>
                                            <?php if($status != 'Подтверждена'):?>
                                            <input type="checkbox"
                                                   onchange="checked_filed(
                                                                            event,
                                                                            '<?=$supply['site_id']?>'
                                                                            )">
                                            <?php endif;?>

                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>

                <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>

                    <table>
                        <thead>
                        <tr>
                            <th class="sort">Partner</th>
                            <th class="sort">Number</th>
                            <th class="sort">Arriving Date</th>
                            <th class="sort">Status</th>
                            <th class="sort">Checked</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allSupply)):?>
                            <?php foreach ($allSupply as $supply):?>
                                <?php $status = iconv('WINDOWS-1251', 'UTF-8', $supply['status_name'])?>
                                <tr data-siteid="<?=$supply['site_id']?>" class="">
                                    <td><?=$supply['site_client_name']?></td>
                                    <td><?=$supply['supply_id']?></td>
                                    <td><?=Functions::formatDate($supply['expected_arriving_date'])?></td>
                                    <td class="<?=Supply::getStatusSupply($status)?>"><?=$status?></td>
                                    <td>
                                        <?php if($status != 'Подтверждена'):?>
                                            <input type="checkbox"
                                                   onchange="checked_filed(
                                                           event,
                                                           '<?=$supply['site_id']?>'
                                                           )">
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
<div class="reveal" id="add-supply-modal" data-reveal>
    <form action="#" id="add-supply-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Supply</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label>Supply Name</label>
                                <input type="text" pattern=".{3,}" class="required" name="supply_name" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Arriving Date</label>
                                <input type="text" class="required date" name="arriving_date" required>
                            </div>
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
                                            href="/upload/attach_supply/supply_import.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="add_supply" value="true">
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

<!--
<div class="reveal" id="refactor-field-modal" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <div class="row align-bottom ">
                        <div class="medium-12 small-12 columns">
                            <label>Part Number</label>
                            <input id="part-number" disabled type="text" pattern=".{8,}" class="required" name="part_number"
                                   required>
                        </div>
                        <div class="medium-12 small-12 columns">
                            <label>Description PN</label>
                            <input id="description-pn"  disabled type="text" class="required" name="description_pn" required>
                        </div>
                        <div class="medium-12 small-12 columns">
                            <label>SO Number</label>
                            <input id="so-number" disabled type="text" class="required" name="so_number" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
-->

<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Supply</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <table>
                <thead>
                <tr>
                    <th>PartNumber</th>
                    <th>Description PN</th>
                    <th>SO Number</th>
                    <th>Quantity</th>
                    <th>Quantity reserv</th>
                    <th>Price</th>
                    <th>Tracking Number</th>
                    <th>Manufacture country</th>
                    <th>Partner</th>
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
