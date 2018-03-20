<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Dashboard</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/engineers_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-12 small-12 columns">
                                <button data-open="filter-modal" class="button primary tool"><i class="fi-filter"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content">
            <div class="row">
                <div class="medium-12 small-12 columns text-center">
                    <p>Данные за <?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?></p>
                </div>
                <?php if($nameEngineers): ?>
                    <?php foreach ($nameEngineers as $engineer): ?>
                        <div class="medium-6 small-6 columns">
                            <table class="umbrella-table margin-bottom">
                                <thead>
                                <tr>
                                    <th class="text-center" colspan="8"><?= $engineer['worker_name'] ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center">KPI</th>
                                    <th>Condition</th>
                                    <th>Target</th>
                                    <th>Weight</th>
                                    <th>Result</th>
                                    <th>Coef</th>
                                    <th>Rate</th>
                                    <th>Бонус</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($newKpi as $key => $engineerKpi): ?>
                                    <?php if($engineer['worker_id'] == $key): ?>
                                        <?php foreach ($engineerKpi as $kpi): ?>
                                            <tr>
                                                <td class="umbrella-tr-td"><?= $kpi['name'] ?></td>
                                                <td><?= $kpi['condition'] ?></td>
                                                <td><?= $kpi['target'] ?>%</td>
                                                <td><?= $kpi['weight'] ?></td>
                                                <td><?= $kpi['result'] ?></td>
                                                <td><?= $kpi['coef'] ?></td>
                                                <td><?= $kpi['rate'] ?></td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="6"></td>
                                    <td><?= array_sum(array_column($newKpi[$engineer['worker_id']], 'rate')); ?></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="thank_you_page">
                        <h3>За данный интервал нету данных</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require(views_path('admin/engineers/dashboard/_part/filter.php'))?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
