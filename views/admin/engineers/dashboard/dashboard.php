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
                <div class="medium-7 small-12 columns">
                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="8">KPI</th>
                        </tr>
                        <tr>
                            <th class="text-center">KPI</th>
                            <th>Condition</th>
                            <th>Target</th>
                            <th>Weight</th>
                            <th>Result</th>
                            <th>Coef.</th>
                            <th>Rate</th>
                            <th>Бонус</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="umbrella-tr-td">SW repair TAT</td>
                                <td>1 business day</td>
                                <td>90%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">Engineer Order TAT</td>
                                <td>1 business day</td>
                                <td>95%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Выплата</td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">Repair TAT</td>
                                <td>1 business day</td>
                                <td>90%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">FTF 30 days</td>
                                <td>no more</td>
                                <td>4%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">FTF 90 days</td>
                                <td>no more</td>
                                <td>6%</td>
                                <td>0,1</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">L2 rate</td>
                                <td>more</td>
                                <td>30%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="umbrella-tr-td">Return Spare Parts</td>
                                <td>1 business day</td>
                                <td>95%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td>0,00</td>
                                <td></td>
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

                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="7">РАЗБОРКА</th>
                        </tr>
                        <tr>
                            <th>Производитель</th>
                            <th>Тип товара</th>
                            <th>Предварительная разборка</th>
                            <th>Разобрано</th>
                            <th>Принятая разборка</th>
                            <th>Запчасти Б/У</th>
                            <th>Запчасти BAD</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
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
                                <th class="text-center" colspan="4">ДВИЖЕНИЕ ТЕХНИКИ (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Производитель</th>
                            </tr>
                            <tr>
                                <th class="text-center">Наименование техники</th>
                                <th class="text-center">Вход на участок</th>
                                <th class="text-center">Выход с участка</th>
                                <th class="text-center">Остаток к-во</th>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <tr>
                                <td class="umbrella-tr-td text-center">Всего:</td>
                                <td><?= $totalDeviceProducer['quantity_in'] ?></td>
                                <td><?= $totalDeviceProducer['quantity_out'] ?></td>
                                <td><?= $totalDeviceProducer['quantity_stock'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <table class="umbrella-table margin-bottom">
                            <thead>
                            <tr>
                                <th class="text-center" colspan="4">ДВИЖЕНИЕ ТЕХНИКИ (<?= \Umbrella\models\engineer\Dashboard::nameMonth($month) ?>/<?= $year ?>) - Классификатор</th>
                            </tr>
                            <tr>
                                <th class="text-center">Наименование техники</th>
                                <th class="text-center">Вход на участок</th>
                                <th class="text-center">Выход с участка</th>
                                <th class="text-center">Остаток к-во</th>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <tr>
                                <td class="umbrella-tr-td text-center">Всего:</td>
                                <td><?= $totalDeviceClassifier['quantity_in'] ?></td>
                                <td><?= $totalDeviceClassifier['quantity_out'] ?></td>
                                <td><?= $totalDeviceClassifier['quantity_stock'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="3">РЕМОНТЫ</th>
                        </tr>
                        <tr>
                            <th class="text-center">Наименование техники</th>
                            <th class="text-center">Открытые ремонты</th>
                            <th class="text-center">Закрытые ремонты</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="umbrella-tr-td">Смартфоны</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Планшеты</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Ноутбуки</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Моноблоки</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Матерински платы</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Дисплеи</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td">Бытовая техника</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="umbrella-tr-td text-center">Всего:</td>
                            <td></td>
                            <td></td>
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
