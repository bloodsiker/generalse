<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row body-content">
            <div class="medium-12 small-12 columns">
                <div class="risk-message">
                    <p>Добрый день.</p>
                    <p>К сожалению, у Вас есть просроченные платежи. <br>
                    Доступ к личному кабинету будет заблокирован (2017-10-02) до полного погашения просроченных счетов.</p>
                    <p>Для дополнительной информации просьба связаться с нами по адресу: <a href="mailto:gsteam@generalse.com">gsteam@generalse.com</a></p>
                    <p>Спасибо.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="medium-12 small-12 columns">
        <div class="row body-content">
            <div class="medium-8 medium-offset-2 small-12 columns">
                <h2 class="float-left">Просроченные счета</h2>
                <div class="clearfix"></div>
                <table class="umbrella-table" style="margin-bottom: 200px" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th>Номер счета</th>
                        <th>Дата счета</th>
                        <th>Сумма счета</th>
                        <th>Номер заказа</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($listRisks)): ?>
                        <?php foreach ($listRisks as $risk): ?>
                            <tr>
                                <td><?=$risk['bill_number']?></td>
                                <td><?=\Umbrella\components\Functions::formatDate($risk['bill_date'])?></td>
                                <td><?=$risk['bill_summa']?></td>
                                <td><?=$risk['order_number']?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
