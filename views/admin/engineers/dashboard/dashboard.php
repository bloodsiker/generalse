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
        <div class="body-content" style="margin-bottom: 100px">
            <div class="row">

                <div class="medium-7 small-12 columns">

                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="7">РАЗБОРКА (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Производитель</th>
                        </tr>
                        <tr>
                            <th>Производитель</th>
                            <th>Предварительная разборка</th>
                            <th>Разобрано</th>
                            <th>Принятая разборка</th>
                            <th>Запчасти Б/У</th>
                            <th>Запчасти BAD</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($disassemblyProducer)): ?>
                            <?php foreach ($disassemblyProducer as $value): ?>
                                <tr>
                                    <td class="umbrella-tr-td"><?= $value['produser_name'] ?></td>
                                    <td><?= $value['quantity_prev'] ?></td>
                                    <td><?= $value['quantity_decompiled'] ?></td>
                                    <td><?= $value['quantity_shipped'] ?></td>
                                    <td><?= $value['quantity_ok'] ?></td>
                                    <td><?= $value['quantity_bad'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <tr>
                            <td class="umbrella-tr-td text-center">Всего:</td>
                            <td><?= $totalDisassemblyProducer['quantity_prev'] ?></td>
                            <td><?= $totalDisassemblyProducer['quantity_decompiled'] ?></td>
                            <td><?= $totalDisassemblyProducer['quantity_shipped'] ?></td>
                            <td><?= $totalDisassemblyProducer['quantity_ok'] ?></td>
                            <td><?= $totalDisassemblyProducer['quantity_bad'] ?></td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="7">РАЗБОРКА (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Классификатор</th>
                        </tr>
                        <tr>
                            <th>Тип товара</th>
                            <th>Предварительная разборка</th>
                            <th>Разобрано</th>
                            <th>Принятая разборка</th>
                            <th>Запчасти Б/У</th>
                            <th>Запчасти BAD</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($disassemblyClassifier)): ?>
                            <?php foreach ($disassemblyClassifier as $value): ?>
                                <tr>
                                    <td class="umbrella-tr-td"><?= $value['class_name'] ?></td>
                                    <td><?= $value['quantity_prev'] ?></td>
                                    <td><?= $value['quantity_decompiled'] ?></td>
                                    <td><?= $value['quantity_shipped'] ?></td>
                                    <td><?= $value['quantity_ok'] ?></td>
                                    <td><?= $value['quantity_bad'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <tr>
                            <td class="umbrella-tr-td text-center">Всего:</td>
                            <td><?= $totalDisassemblyClassifier['quantity_prev'] ?></td>
                            <td><?= $totalDisassemblyClassifier['quantity_decompiled'] ?></td>
                            <td><?= $totalDisassemblyClassifier['quantity_shipped'] ?></td>
                            <td><?= $totalDisassemblyClassifier['quantity_ok'] ?></td>
                            <td><?= $totalDisassemblyClassifier['quantity_bad'] ?></td>
                        </tr>
                        </tbody>
                    </table>


                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="6">БОНУСЫ</th>
                        </tr>
                        <tr>
                            <th class="text-center">Инженер</th>
                            <th>Ремонт ПСР</th>
                            <th>Ремонт негарантийный</th>
                            <th>Ремонт БТ</th>
                            <th>Восстановление МП</th>
                            <th>Текущая сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="umbrella-tr-td">Подвальный Е.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Рудык А.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Новиков В.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Кацапчук Е.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="medium-5 small-12 columns">

                    <div class="medium-12 small-12 columns">
                        <table class="umbrella-table margin-bottom">
                            <thead>
                            <tr>
                                <th class="text-center" colspan="5">ДВИЖЕНИЕ ТЕХНИКИ (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Производитель</th>
                            </tr>
                            <tr>
                                <th class="text-center">Наименование техники</th>
                                <th class="text-center">Вход на участок</th>
                                <th class="text-center">Выход с участка</th>
                                <th class="text-center">Остаток к-во</th>
                                <th class="text-center">Принято разборок</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($movementDevicesProducer)): ?>
                                <?php foreach ($movementDevicesProducer as $value): ?>
                                    <tr>
                                        <td class="umbrella-tr-td"><?= $value['produser_name'] ?></td>
                                        <td><?= $value['quantity_in'] ?></td>
                                        <td><?= $value['quantity_out'] ?></td>
                                        <td><?= $value['quantity_stock'] ?></td>
                                        <td><?= $value['quantity_decompile'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <tr>
                                <td class="umbrella-tr-td text-center">Всего:</td>
                                <td><?= $totalDeviceProducer['quantity_in'] ?></td>
                                <td><?= $totalDeviceProducer['quantity_out'] ?></td>
                                <td><?= $totalDeviceProducer['quantity_stock'] ?></td>
                                <td><?= $totalDeviceProducer['quantity_decompile'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <table class="umbrella-table margin-bottom">
                            <thead>
                            <tr>
                                <th class="text-center" colspan="5">ДВИЖЕНИЕ ТЕХНИКИ (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Классификатор</th>
                            </tr>
                            <tr>
                                <th class="text-center">Наименование техники</th>
                                <th class="text-center">Вход на участок</th>
                                <th class="text-center">Выход с участка</th>
                                <th class="text-center">Остаток к-во</th>
                                <th class="text-center">Принято разборок</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($movementDevicesClassifier)): ?>
                                <?php foreach ($movementDevicesClassifier as $value): ?>
                                    <tr>
                                        <td class="umbrella-tr-td"><?= $value['classifier_name'] ?></td>
                                        <td><?= $value['quantity_in'] ?></td>
                                        <td><?= $value['quantity_out'] ?></td>
                                        <td><?= $value['quantity_stock'] ?></td>
                                        <td><?= $value['quantity_decompile'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <tr>
                                <td class="umbrella-tr-td text-center">Всего:</td>
                                <td><?= $totalDeviceClassifier['quantity_in'] ?></td>
                                <td><?= $totalDeviceClassifier['quantity_out'] ?></td>
                                <td><?= $totalDeviceClassifier['quantity_stock'] ?></td>
                                <td><?= $totalDeviceClassifier['quantity_decompile'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="7">РЕМОНТЫ (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>)</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th rowspan="2" class="text-center">Наименование техники</th>
                                <th colspan="3" class="text-center">Открытые ремонты</th>
                                <th colspan="3" class="text-center">Закрытые ремонты</th>
                            </tr>
                            <tr>
                                <td>Устройство</td>
                                <td>Материнская плата</td>
                                <td>Дисплей</td>
                                <td>Устройство</td>
                                <td>Материнская плата</td>
                                <td>Дисплей</td>
                            </tr>

                        <?php if(is_array($arrayRepairs)): ?>
                            <?php foreach ($arrayRepairs as $value): ?>
                                <tr>
                                    <td class="umbrella-tr-td"><?= $value['class_name'] ?></td>
                                    <td><?= is_array($value['device']) ? $value['device']['quantity_open'] : null ?></td>
                                    <td><?= is_array($value['mat']) ? $value['mat']['quantity_open'] : null ?></td>
                                    <td><?= is_array($value['lcd']) ? $value['lcd']['quantity_open'] : null ?></td>
                                    <td><?= is_array($value['device']) ? $value['device']['quantity_close'] : null ?></td>
                                    <td><?= is_array($value['mat']) ? $value['mat']['quantity_close'] : null ?></td>
                                    <td><?= is_array($value['lcd']) ? $value['lcd']['quantity_close'] : null ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                            <tr>
                                <td class="text-center">Всего:</td>
                                <td><?= isset($totalRepair['device']) && is_array($totalRepair['device']) ? $totalRepair['device']['open'] : null ?></td>
                                <td><?= isset($totalRepair['mat']) && is_array($totalRepair['mat']) ? $totalRepair['mat']['open'] : null ?></td>
                                <td><?= isset($totalRepair['lcd']) && is_array($totalRepair['lcd']) ? $totalRepair['lcd']['open'] : null ?></td>
                                <td><?= isset($totalRepair['device']) && is_array($totalRepair['device']) ? $totalRepair['device']['close'] : null ?></td>
                                <td><?= isset($totalRepair['mat']) && is_array($totalRepair['mat']) ? $totalRepair['mat']['close'] : null ?></td>
                                <td><?= isset($totalRepair['lcd']) && is_array($totalRepair['lcd']) ? $totalRepair['lcd']['close'] : null ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">В сумме:</td>
                                <td class="text-center" colspan="3"><?= $totlaRepairsSum['open'] ?? null ?></td>
                                <td class="text-center" colspan="3"><?= $totlaRepairsSum['close'] ?? null ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require(views_path('admin/engineers/dashboard/_part/filter.php'))?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
