<?php require(views_path('admin/layouts/header.php')) ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>MDS</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/repairs_ree_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-3 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-12 small-12 columns">
                                <button data-open="create-so-modal" class="button primary tool"><i class="fi-plus"></i> Create SO</button>
                            </div>
                        </div>
                    </div>
                    <div class="medium-9  small-12 columns">
                        <form action="/adm/repairs_ree/mds/" method="post" class="form">
                            <div class="row align-bottom">
                                <div class="medium-3 text-left small-12 columns">
                                    <label for="right-label">Status SO</label>
                                    <select name="SOStatus" id="">
                                        <option <?=(isset($_POST['SOStatus']) && $_POST['SOStatus'] == 'All') ? 'selected' : null?> value="All">All</option>
                                        <option <?=(isset($_POST['SOStatus']) && $_POST['SOStatus'] == 'Closed') ? 'selected' : null?> value="Closed">Closed</option>
                                        <option <?=(isset($_POST['SOStatus']) && $_POST['SOStatus'] == 'In Progress') ? 'selected' : null?> value="In Progress">In Progress</option>
                                    </select>
                                </div>
                                <div class="medium-3 text-left small-12 columns">
                                    <label for="right-label">Date Type</label>
                                    <select name="type_date" id="">
                                        <option <?=(isset($_POST['type_date']) && $_POST['type_date'] == 'created_on') ? 'selected' : null?> value="created_on">По дате создания запроса</option>
                                        <option <?=(isset($_POST['type_date']) && $_POST['type_date'] == 'RepairFinishiTime') ? 'selected' : null?> value="RepairFinishiTime">По дате закрытия ремонта</option>
                                    </select>
                                </div>
                                <div class="medium-2 text-left small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                    <input type="text" class="required" id="date-start" value="<?=(isset($_POST['start']) && $_POST['start'] != '') ? $_POST['start'] : null?>" name="start" required>
                                </div>
                                <div class="medium-2 small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                    <input type="text" class="required" id="date-end" value="<?=(isset($_POST['end']) && $_POST['end'] != '') ? $_POST['end'] : null?>" name="end" required>
                                </div>
                                <div class="medium-2 small-12 columns">
                                    <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content">
            <div class="row">
                <?php if(isset($message) && !empty($message)):?>
                    <div class="medium-12 small-12 columns" style="text-align: center">
                        <div class="alert-success" style="margin: 20px auto;"><?=$message?></div>
                    </div>
                <?php endif;?>
                <div class="medium-12 small-12 columns">
                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th>Partner</th>
                            <th>SO Number</th>
                            <th>Serial Number</th>
                            <th>Partner Job Order</th>
                            <th>Defective PN</th>
                            <th>Replaced PN</th>
                            <th>Status SO</th>
                            <th>Creation Date</th>
                            <th>Closure Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allMds)): ?>
                            <?php foreach ($allMds as $mds): ?>
                            <tr>
                                <td><?= $mds['site_client_name'] ?></td>
                                <td><?= $mds['so'] ?></td>
                                <td><?= $mds['IMEIorSN'] ?></td>
                                <td><?= $mds['PartnerJobOrder'] ?></td>
                                <td></td>
                                <td></td>
                                <td><?= $mds['SOStatus'] ?></td>
                                <td><?= $mds['created_on'] ?></td>
                                <td><?= $mds['RepairFinishiTime'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<?php require(views_path('admin/repairs_ree/mds/_part/create-so.php'))?>

<?php require(views_path('admin/layouts/footer.php')) ?>
