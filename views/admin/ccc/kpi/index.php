<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>KPI</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/ccc_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">


                        <form action="/adm/ccc/kpi/date-" method="get" class="form" data-abide novalidate>
                            <div class="row align-bottom">
                                <div class="medium-2 text-left small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                    <input type="text"  id="date-start"  name="start" value="<?= isset($_GET['start']) ? $_GET['start'] : null?>" required>
                                </div>
                                <div class="medium-2 small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                    <input type="text" id="date-end" name="end" value="<?= isset($_GET['end']) ? $_GET['end'] : null?>" required>
                                </div>
                                <div class="medium-2 small-12 columns">
                                    <button type="submit" class="button primary">Show</button>
                                </div>

                                <div class="medium-4 text-left small-12 columns">
                                </div>

                                <div class="medium-2 text-right small-12 columns">
                                    <?php if (Umbrella\app\AdminBase::checkDenied('ccc.kpi_ccc.import', 'view')): ?>
                                        <button class="button primary tool" data-open="import-analog-modal"><i class="fi-plus"></i> Import KPI</button>
                                    <?php endif;?>
                                </div>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <div class="body-content checkout">
            <div class="row">
                <?php if(isset($success_import) && !empty($success_import)):?>
                    <h2>Успешно добавленный новый масиив KPI</h2>
                <?php endif; ?>

                <div class="medium-12 small-12 coumns">
                    <?php if(isset($_GET['date'])):?>
                        <h2 class="text-center">Report from the <strong><?= $_GET['date']?></strong></h2>
                    <?php elseif (isset($_GET['start']) && isset($_GET['start'])):?>
                        <h2 class="text-center">Report from the <strong><?= $_GET['start']?> on <?= $_GET['end']?> </strong></h2>
                    <?php else:?>
                        <h2 class="text-center">Report from the <strong><?= $listData[0]['created_at']?></strong></h2>
                    <?php endif;?>

                </div>
                <div class="medium-10 small-12 columns">
                    <table class="ccc-kpi">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Target</th>
                                <?php if(is_array($listManager)):?>
                                    <?php foreach ($listManager as $manager):?>
                                        <th><?= $manager?></th>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Исходящий звонок</td>
                                <td>-</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td><?= $date['out_call']?></td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Входящий звонок (total)</td>
                                <td>-</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td><?= $date['inc_call']?></td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Входящий звонок (BY)</td>
                                <td>-</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td><?= $date['inc_call_BY']?></td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Входящий звонок (F1)</td>
                                <td>-</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td><?= $date['inc_call_F1']?></td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Соотношение всех карт к обработанным звонкам</td>
                                <td>>= <?= $KPI::COEFFICIENT_MAPS ?>%</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td class="<?= $KPI->controlTargetUp($date['coefficient_maps'], $KPI::COEFFICIENT_MAPS)?>"><?= $date['coefficient_maps']?> %</td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Соотношение принятых к пропущенным звонкам</td>
                                <td>>= <?= $KPI::COEFFICIENT_CALLS ?>%</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td class="<?= $KPI->controlTargetUp($date['coefficient_calls'], $KPI::COEFFICIENT_CALLS)?>"><?= $date['coefficient_calls']?> %</td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Скорость поднятия трубки при входящем звонке</td>
                                <td><= <?= $KPI::INC_CALL_RATE ?> сек</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td class="<?= $KPI->controlTargetDown($date['inc_call_rate'], $KPI::INC_CALL_RATE)?>"><?= $date['inc_call_rate']?> сек</td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td>Среднее время разговора</td>
                                <td><= <?= $KPI::AVG_TALK_TIME ?> мин</td>
                                <?php if(is_array($lastDate)):?>
                                    <?php foreach ($lastDate as $date):?>
                                        <td class="<?= $KPI->controlTargetDown($date['avg_talk_time'], $KPI::AVG_TALK_TIME)?>"><?= $date['avg_talk_time']?> мин</td>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="medium-2 small-12 columns">
                    <table>
                        <thead>
                        <tr>
                            <th>Дата обработки показателей</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listData)):?>
                            <?php foreach ($listData as $date):?>
                                <tr class="hover-pointer" onclick="window.location.href = '/adm/ccc/kpi/date-?date=<?= $date['created_at']?>';">
                                    <td><?= $date['created_at']?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="reveal" id="import-analog-modal" data-reveal>
    <form action="/adm/ccc/kpi/import-kpi" id="import-analog-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import KPI</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns" style="color: #fff; margin-bottom: 15px">
                        Важно! Даты в excel файле должны быть в формате <br> YYYY-mm-dd (2017-01-31)
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
                                            href="/upload/import_kpi/CCC_KPI.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="import-ccc-kpi" value="true">
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
