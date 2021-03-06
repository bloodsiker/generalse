<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Debtors EL UA</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/ccc_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-9 small-12 columns">
                        <button data-open="open-filter-modal"  class="button primary tool"><i class="fi-filter"></i> Filter</button>
                        <button onclick="callIsOver(event)" class="button primary tool"><i class="fa fa-volume-control-phone"></i> Обзвон завершен</button>
                    </div>
                    <div class="medium-3 small-12 columns">
                        <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="body-content">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <table id="goods_data" class="umbrella-table fixtable">
                        <thead>
                        <tr>
                            <th>Клиент</th>
                            <th>Номер заказа</th>
                            <th>Сумма заказа</th>
                            <th>Дата выдачи заказа</th>
                            <th>Номер счета</th>
                            <th>Сумма счета</th>
                            <th>Дата выставления счета</th>
                            <th>Статус оплаты</th>
                            <th>Оплатить до</th>
                            <th>Сумма оплаты</th>
                            <th>Дата оплаты</th>
                            <th>Отсрочка</th>
                            <th>Дней до погашения</th>
                            <th>Телефоны</th>
                            <th>Адрес доставки</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($allDebtors)): ?>
                            <?php foreach ($allDebtors as $debtor): ?>
                                <tr ondblclick="showComments(<?= $debtor['site_account_id'] ?>)"
                                    class="goods <?= $debtor['order_status'] == 'просрочен' ? 'red' : null ?>">
                                    <td class="<?= $debtor['call_is_over'] == 1 ? 'blue' : null ?>">
                                        <?= $debtor['client_name'] ?>
                                    </td>
                                    <td><?= $debtor['order_number'] ?></td>
                                    <td><?= $debtor['summa_order'] ?></td>
                                    <td><?= $debtor['order_shiped_on'] ?></td>
                                    <td><?= $debtor['bill_number'] ?></td>
                                    <td><?= $debtor['bill_summa'] ?></td>
                                    <td><?= $debtor['bill_created_on'] ?></td>
                                    <td><?= $debtor['order_status'] ?></td>
                                    <td><?= $debtor['order_payment_to'] ?></td>
                                    <td><?= $debtor['payment_summa'] ?></td>
                                    <td><?= $debtor['payment_dates'] ?></td>
                                    <td><?= $debtor['order_delay'] ?></td>
                                    <td><?= $debtor['days'] ?></td>
                                    <td><?= $debtor['phones'] ?></td>
                                    <td><?= $debtor['address'] ?></td>
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

<?php require(views_path('admin/ccc/debtors/_part/comments_modal.php'))?>

<?php require(views_path('admin/ccc/debtors/_part/filter_modal.php'))?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
