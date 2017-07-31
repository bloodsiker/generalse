<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <?php if($user->role == 'administrator-fin'):?>
        <div class="row admin_dashboard_menu">
            <div class="medium-12 small-12 columns">
                <ul class="admin_menu">

                    <?php require_once ROOT . '/views/admin/layouts/admin_dashboard_menu.php' ?>

                </ul>
            </div>
        </div>
    <?php endif;?>

    <?php if($user->role == 'administrator-fin'):?>

    <div class="row body-content">
        <div class="medium-12 small-12 columns">
            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <caption>Requests for payment</caption>
                <thead>
                <tr>
                    <th width="50px">ID</th>
                    <th>Partner</th>
                    <th>Labor Cost</th>
                    <th>Customer</th>
                    <th style="text-align: center;">Action balance</th>
                    <th style="text-align: center;">Comment</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Date accrual </th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($requestUsersPaid)):?>
                    <?php foreach ($requestUsersPaid as $requestPaid):?>
                        <tr data-paid-id="<?=$requestPaid['id']?>">
                            <td><?=$requestPaid['id']?></td>
                            <td><?=$requestPaid['name_partner']?></td>
                            <td><?=$requestPaid['balance']?>$</td>
                            <td><?=$requestPaid['customer_name']?></td>
                            <td><?=$requestPaid['action_balance']?></td>
                            <td><?=$requestPaid['comment']?></td>
                            <td class="<?= Umbrella\models\Balance::getStatusPaid($requestPaid['status'])?>"><?=$requestPaid['status']?></td>
                            <td><?=$requestPaid['date_create']?></td>
                            <td style="text-align: center;">
                                <?php if($requestPaid['paid'] == 0):?>
                                    <button class="ok-paid paid green">Подтвердить</button>
                                <?php endif;?>
                            </td>
                            <td style="text-align: center;">
                                <?php if($requestPaid['paid'] == 0):?>
                                    <button class="no-paid paid red">Отклонить</button>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif;?>



<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>