<?php require_once ROOT . '/views/admin/layouts/header.php' ?>
    <div class="row admin_head_menu">
        <div class="medium-10 small-12 columns">
            <ul class="admin_menu float-right">

                <?php require_once ROOT . '/views/admin/layouts/admin_menu.php' ?>

            </ul>
        </div>
    </div>
    <div class="row">
        <div class="medium-12 small-12 columns">
            <div class="row body-content">
                <div class="medium-6 small-12 columns">
                    <h2 class="float-left">Список складов в группе <?= $group->getNameGroup($id_group)?> (<?= $section?>)</h2>
                    <a href="/adm/group/<?=$id_group?>" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Stock name</th>
                            <th width="70">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listStocksGroup)): ?>
                            <?php foreach ($listStocksGroup as $stock): ?>
                                <tr>
                                    <td><?=$stock['stock_name']?></td>
                                    <td>
                                        <?php if (Umbrella\app\AdminBase::checkDenied('group.stock.delete', 'view')): ?>
                                            <a href="/adm/group/delete/stock/<?= $stock['id_row'] ?>" class="button no-margin small"
                                               onclick="return confirm('Вы уверены?') ? true : false;"><i
                                                        class="fi-x"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="medium-4 small-12 columns">
                    <h2>Добавить склад</h2>
                    <form action="" method="post">
                        <select name="id_stock">
                            <option value=""></option>
                            <?php foreach ($allStocks as $stock):?>
                                <option <?= ($group->checkStockInGroups($id_group, $stock['id'], $section) ? 'disabled' : '')?> value="<?=$stock['id']?>"><?=$stock['stock_name']?></option>
                            <?php endforeach;?>
                        </select>

                        <?php if (Umbrella\app\AdminBase::checkDenied('group.stock.add', 'view')): ?>
                            <input type="hidden" name="add_stock_group" value="true">
                            <button type="submit" style="margin-top: 15px" class="button small float-right">Добавить</button>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="medium-2 small-12 columns">
                    <?php if (Umbrella\app\AdminBase::checkDenied('group.stock.view', 'view')): ?>
                        <h2>Разделы</h2>
                        <ul class="menu-section">
                            <li><a href="/adm/group/<?=$id_group?>/stock/purchase" class="<?= Umbrella\components\Url::IsActive('/stock/purchase', 'active-section') ?>">Purchase</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/order" class="<?= Umbrella\components\Url::IsActive('/stock/order', 'active-section') ?>">Order</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/returns" class="<?= Umbrella\components\Url::IsActive('/stock/returns', 'active-section') ?>">Returns</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/stocks" class="<?= Umbrella\components\Url::IsActive('/stock/stocks', 'active-section') ?>">Stocks</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/disassembly" class="<?= Umbrella\components\Url::IsActive('/stock/disassembly', 'active-section') ?>">Disassembly</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/supply" class="<?= Umbrella\components\Url::IsActive('/stock/supply', 'active-section') ?>">Supply</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/request" class="<?= Umbrella\components\Url::IsActive('/stock/request', 'active-section') ?>">Request</a></li>
                        </ul>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>