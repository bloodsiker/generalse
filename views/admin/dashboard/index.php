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

    <div class="row body-content">
        <div class="medium-12 small-12 columns">
            <table border="1" cellspacing="0" cellpadding="5">
                <caption>User info</caption>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Login</th>
                    <th>Role</th>
                    <th>Created_at</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?=$userInfo['name_partner']?></td>
                    <td><?=$userInfo['short_name'] . '-' . $userInfo['full_name']?></td>
                    <td><?=$userInfo['login']?></td>
                    <td><?=$userInfo['name_role']?></td>
                    <td><?=$userInfo['date_create']?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($user->role == 'branch-fin'):?>

    <div class="row body-content">
        <div class="medium-12 small-12 columns">
            <table border="1" cellspacing="0" cellpadding="5">
                <caption>Users in the branch</caption>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Created_at</th>
                    <th width="150">Total balance</th>
                    <th width="70">View</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($listUsersInBranch)):?>
                    <?php foreach ($listUsersInBranch as $userBranch):?>
                        <tr>
                            <td><?=$userBranch['name_partner']?></td>
                            <td><?=$userBranch['name_role']?></td>
                            <td><?=$userBranch['date_create']?></td>
                            <td><?=Balance::getBalanceByPartner($userBranch['id_user'])?>$</td>
                            <td><a href="/adm/dashboard/balance-u/<?=$userBranch['id_user']?>" class="button no-margin small"><i class="fi-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif;?>

    <?php if($user->role == 'administrator-fin'):?>
    <div class="row" style="margin-top: 20px">
        <div class="column medium-4">

        </div>

        <div class="column medium-4">

        </div>

        <div class="column medium-4">

            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Total balance</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="price" colspan="2"><?= Umbrella\models\Balance::getBalanceByPartner($user->id_user)?>$</td>
                </tr>
                <tr>
                    <td>
                        <?php $changeDate = $currentMonthYear['year'] . ' ' . $currentMonthYear['month']?>
                        <?=(isset($_GET['interval'])) ? Umbrella\models\Balance::getNameMonth($_GET['interval']) : $changeDate?>
                    </td>
                    <td style="text-align: center;"><span id="month-balance"><?=$balanceMonth?></span>$</td>
                    <!--                    <td id="expand-balance"><i class="fi-plus"></i></td>-->
                </tr>
                <tr>
                    <td colspan="2">
                        <form action="/adm/dashboard/" method="get" class="dashboard-form">
                            <div class="input-group">
                                <select class="input-group-field" name="interval" onchange="this.form.submit()">
                                    <?php if(is_array($listInterval)):?>
                                        <?php foreach ($listInterval as $interval):?>
                                            <?php $year_month = $interval['year'] . '-' . Umbrella\models\Balance::formatMonth($interval['month'])?>
                                            <option <?=(isset($_GET['interval']) && $_GET['interval'] == $year_month) ? 'selected' : ''?> value="<?=$year_month?>">
                                                <?=$interval['year'] . ' ' . $interval['month_name']?> || <?= Umbrella\models\Balance::getBalanceMonthByPartner($user->id_user, $year_month)?>$
                                            </option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="row" id="show-details" style="margin-top: 20px;">
        <div class="column medium-12">
            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <caption>Operations for <?=(isset($_GET['interval'])) ? Umbrella\models\Balance::getNameMonth($_GET['interval']) : $changeDate?></caption>
                <thead>
                <tr>
                    <th width="50px">ID</th>
                    <th>Labor Cost</th>
                    <th>Customer</th>
                    <th style="text-align: center;">Action balance</th>
                    <th style="text-align: center;">Section</th>
                    <th style="text-align: center;">ID record</th>
                    <th style="text-align: center;">Comment</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Date accrual </th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($userDetailsBalance)):?>
                    <?php foreach ($userDetailsBalance as $details):?>
                        <tr class="<?=(!empty($details['section'])) ? 'info-operation' : ''?>"
                            data-section="<?=$details['section']?>"
                            data-row-id="<?=$details['id_row_section']?>"
                            data-task-id="<?=$details['id_task']?>">
                            <td><?=$details['id']?></td>
                            <td class="<?= Umbrella\models\Balance::getStatusRequest($details['balance'])?>"><?=$details['balance']?>$</td>
                            <td><?=$details['customer_name']?></td>
                            <td><?=$details['action_balance']?></td>
                            <td><?=$details['section']?></td>
                            <td><?=$details['id_row_section']?></td>
                            <td><?=$details['comment']?></td>
                            <td class="<?= Umbrella\models\Balance::getStatusPaid($details['status'])?>"><?=$details['status']?></td>
                            <td><?=$details['date_create']?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif;?>

    <?php if($user->role == 'partner'):?>
    <div class="row" style="margin-top: 20px">
        <div class="column medium-4">

            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th>Section</th>
                    <th style="text-align: center;">All request</th>
                    <th style="text-align: center;">Accepted request <i class="fi-info has-tip [tip-top]" style="font-size: 16px;" data-tooltip aria-haspopup="true" data-options="show_on:large" title="For the last 7 days"></i></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><a href="/adm/refund_request/view">Refund Request</a></td>
                    <td style="text-align: center;"><span class="badge secondary"><?= Umbrella\models\Dashboard::countAllRefundRequest($user->id_user)?></span></td>
                    <td style="text-align: center;"><span class="badge success"><?= Umbrella\models\Dashboard::countSuccessRefundRequest($user->id_user, iconv('UTF-8', 'WINDOWS-1251', 'подтверждено'))?></span></td>
                </tr>
                <tr>
                    <td><a href="/adm/crm/purchase">Purchase</a></td>
                    <td style="text-align: center;"><span class="badge secondary"><?= Umbrella\models\Dashboard::countAllPurchaseRequest($user->id_user)?></span></td>
                    <td style="text-align: center;"><span class="badge success"><?= Umbrella\models\Dashboard::countSuccessPurchaseRequest($user->id_user, iconv('UTF-8', 'WINDOWS-1251', 'Покупка (принята)'))?></span></td>
                </tr>
                <tr>
                    <td><a href="/adm/crm/orders">Order</a></td>
                    <td style="text-align: center;"><span class="badge secondary"><?= Umbrella\models\Dashboard::countAllOrdersRequest($user->id_user)?></span></td>
                    <td style="text-align: center;"><span class="badge success"><?= Umbrella\models\Dashboard::countSuccessOrdersRequest($user->id_user, iconv('UTF-8', 'WINDOWS-1251', 'Выдан'))?></span></td>
                </tr>
                <tr>
                    <td><a href="/adm/crm/returns">Return</a></td>
                    <td style="text-align: center;"><span class="badge secondary"><?= Umbrella\models\Dashboard::countAllOrdersRequest($user->id_user)?></span></td>
                    <td style="text-align: center;"><span class="badge success"><?= Umbrella\models\Dashboard::countSuccessReturnsRequest($user->id_user, iconv('UTF-8', 'WINDOWS-1251', 'Принят'))?></span></td>
                </tr>
                <tr>
                    <td><a href="/adm/crm/disassembly_list">Disassembly</a></td>
                    <td style="text-align: center;"><span class="badge secondary"><?= Umbrella\models\Dashboard::countAllDecompilesRequest($user->id_user)?></span></td>
                    <td style="text-align: center;"><span class="badge success"><?= Umbrella\models\Dashboard::countSuccessDecompilesRequest($user->id_user, iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена'))?></span></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="column medium-4">
            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th>Period of time</th>
                    <th style="text-align: center;">Coefficient </th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?='01 ' . date('F Y')?> &mdash; <?=date('d F Y')?></td>
                        <td style="text-align: center;"><?=$coefficient->coefficientResult()?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="column medium-4">

            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Total balance</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="price" colspan="2"><?= Umbrella\models\Balance::getBalanceByPartner($user->id_user)?>$</td>
                </tr>
                <tr>
                    <td>
                        <?php $changeDate = $currentMonthYear['year'] . ' ' . $currentMonthYear['month']?>
                        <?=(isset($_GET['interval'])) ? Umbrella\models\Balance::getNameMonth($_GET['interval']) : $changeDate?>
                    </td>
                    <td style="text-align: center;"><span id="month-balance"><?=$balanceMonth?></span>$</td>
<!--                    <td id="expand-balance"><i class="fi-plus"></i></td>-->
                </tr>
                <tr>
                    <td colspan="2">
                        <form action="/adm/dashboard/" method="get" class="dashboard-form">
                            <div class="input-group">
                                <select class="input-group-field" name="interval" onchange="this.form.submit()">
                                    <?php if(is_array($listInterval)):?>
                                        <?php foreach ($listInterval as $interval):?>
                                            <?php $year_month = $interval['year'] . '-' . Umbrella\models\Balance::formatMonth($interval['month'])?>
                                            <option <?=(isset($_GET['interval']) && $_GET['interval'] == $year_month) ? 'selected' : ''?> value="<?=$year_month?>">
                                                <?=$interval['year'] . ' ' . $interval['month_name']?> || <?= Umbrella\models\Balance::getBalanceMonthByPartner($user->id_user, $year_month)?>$
                                            </option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                                <?php if(isset($_GET['interval']) && $_GET['interval'] != date('Y-m')):?>
                                <div class="input-group-button expand-balance">
                                    <input type="button" id="expand-balance" class="button" value="+">
                                </div>
                                <?php endif;?>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr class="action_output_balance">
                    <td colspan="2">
                        <form action="/adm/dashboard/pay" method="post" class="dashboard-form">
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" onkeydown="return false;" id="output_balance" type="text" max="<?=$balanceMonth?>" name="receive_funds" value="<?=$balanceMonth?>">
                                <input type="hidden" name="interval" value="<?=(isset($_GET['interval'])) ? $_GET['interval'] : '' ?>">
                                <input type="hidden" name="action" value="pay">
                                <div class="input-group-button output-request">
                                    <input type="submit" class="button" id="send_output_balance" value="Output request">
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="row" id="show-details" style="margin-top: 20px;">
        <div class="column medium-12">
            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <caption>Operations for <?=(isset($_GET['interval'])) ? Umbrella\models\Balance::getNameMonth($_GET['interval']) : $changeDate?></caption>
                <thead>
                <tr>
                    <th width="50px">ID</th>
                    <th>Labor Cost</th>
                    <th>Customer</th>
                    <th style="text-align: center;">Action balance</th>
                    <th style="text-align: center;">Section</th>
                    <th style="text-align: center;">ID record</th>
                    <th style="text-align: center;">Comment</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Date accrual </th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($userDetailsBalance)):?>
                    <?php foreach ($userDetailsBalance as $details):?>
                        <tr class="<?=(!empty($details['section'])) ? 'info-operation' : ''?>"
                            data-section="<?=$details['section']?>"
                            data-row-id="<?=$details['id_row_section']?>"
                            data-task-id="<?=$details['id_task']?>">
                            <td><?=$details['id']?></td>
                            <td class="<?= Umbrella\models\Balance::getStatusRequest($details['balance'])?>"><?=$details['balance']?>$</td>
                            <td><?=$details['customer_name']?></td>
                            <td><?=$details['action_balance']?></td>
                            <td><?=$details['section']?></td>
                            <td><?=$details['id_row_section']?></td>
                            <td><?=$details['comment']?></td>
                            <td class="<?= Umbrella\models\Balance::getStatusPaid($details['status'])?>"><?=$details['status']?></td>
                            <td><?=$details['date_create']?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>


    <div class="reveal large" id="show-info" data-reveal>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Show details</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <table>
                    <thead>
                    <tr>
                        <th>Part number</th>
                        <th>Goods name</th>
                        <th>Quantity</th>
                        <th>Stock name</th>
                        <th>Classifier</th>
                        <th>Goods Sub Type</th>
                        <th>Price</th>
                        <th>Pay</th>
                    </tr>
                    </thead>
                    <tbody id="container-details">

                    </tbody>
                </table>
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php if($_SERVER['HTTP_REFERER'] == 'http://' . $_SERVER['HTTP_HOST'] . '/'):?>
        <div id="general-wait" class="">
            <div id="container-wait">
                <img id="general-logo" src="/template/site/img/About/CalWhiteLogo.svg" width="20%" alt="">
            </div>
        </div>
    <?php endif;?>


<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>