<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Disassembly</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-justify align-bottom">
                            <div class="medium-6 small-12 columns">
                                <form action="" method="post" class="form" data-abide novalidate>
                                    <div class="row align-bottom">

                                        <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'): ?>

                                            <div class="medium-4 small-12 columns">
                                                <label><i class="fi-list"></i> Partner
                                                    <select name="id_partner">
                                                        <option value=""></option>
                                                        <?php if (is_array($partnerList)): ?>
                                                            <?php foreach ($partnerList as $partner): ?>
                                                                <option <?php echo (isset($id_partner) && $id_partner == $partner['id_user']) ? 'selected' : '' ?>
                                                                        value="<?= $partner['id_user'] ?>"><?= $partner['name_partner'] ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </label>
                                            </div>

                                        <?php elseif ($user->role == 'partner'): ?>
                                            <div class="medium-4 small-12 columns">
                                                <label><i class="fi-list"></i> Partner</label>
                                                <select name='id_partner'>
                                                    <?php $user->renderSelectControlUsers($user->id_user); ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>

                                        <div class="medium-5 small-12 columns">
                                            <label><i class="fi-magnifying-glass"></i> Serial Number </label>
                                            <span class="form-error">
											  The device with the serial number is not found. Check the correct serial number or contact your account manager
											</span>
                                            <input type="text" class="search-input" pattern=".{8,}"
                                                   placeholder="Input serial number..." name="serial_number"
                                                   value="<?php echo (isset($serial_number)) ? $serial_number : '' ?>"
                                                   required>
                                        </div>
                                        <div class="medium-3 small-12 columns">
                                            <button class="button primary" name="search_serial"><i
                                                        class="fi-magnifying-glass"></i> request
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="medium-2 medium-offset-2 small-12 columns">
                                <button class="primary button" id="send-form">send</button>
                            </div>
                            <div class="medium-2  small-12 columns">
                                <a href="/adm/crm/disassembly_list" class="button primary">list disassembly</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <?php if (isset($bomList) && count($bomList) > 0): ?>
                    <?php if (is_array($bomList)): ?>
                        <span>Specify the reason for disassembly device</span>
                        <textarea name="note" id="note" cols="30" rows="3" class="required"></textarea>
                        <span id="count_rows_info">At least <strong style="color: red;">5</strong> lines must be marked and correctly filled for request sending</span>
                        <table class="umbrella-table" id="result_disassembly">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Part Number</th>
                                <th>Desription</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bomList as $bom): ?>
                                <tr data-sn="<?= $_POST['serial_number'] ?>"
                                    data-pn="<?= iconv('WINDOWS-1251', 'UTF-8', $bom['dev_part_number']) ?>"
                                    data-name="<?= iconv('WINDOWS-1251', 'UTF-8', $bom['dev_mName']) ?>"
                                    data-stock="SWAP">
                                    <td width="50" class="selectInTable">
                                        <label class="checkbox-label ">
                                            <input class="checkbox" type="checkbox">
                                        </label>
                                    </td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $bom['PartNumber']) ?></td>
                                    <td><?= iconv('WINDOWS-1251', 'UTF-8', $bom['mName']) ?></td>
                                    <td width="200" class="selectInTable">
                                        <select name="stock" class="required">
                                            <option value="" selected="" disabled="">none</option>
                                            <?php foreach ($user->renderSelectStocks($user->id_user, 'disassembly') as $stock): ?>
                                                <option value="<?= $stock ?>"><?= $stock ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td width="100" class="price selectInTable" contenteditable>
                                        <input type="number" value="1" min="1" max="5">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php elseif (isset($bomList)): ?>
                    <div class="thank_you_page">
                        <h3>The device with the
                            SN <?= (!empty($_POST['serial_number'])) ? $_POST['serial_number'] : '' ?><br> not found
                        </h3>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
