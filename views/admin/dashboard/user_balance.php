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

    <div class="row" style="margin-top: 20px">
        <div class="column medium-4">

            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th colspan="2" style="text-align: center;"><?=$userInfo['name_partner']?></th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center;">Total balance</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="price" colspan="2"><?=Balance::getBalanceByPartner($id_user)?>$</td>
                </tr>
                <tr>
                    <td>
                        <?php $changeDate = $currentMonthYear['year'] . ' ' . $currentMonthYear['month']?>
                        <?=(isset($_GET['interval'])) ? Balance::getNameMonth($_GET['interval']) : $changeDate?>
                    </td>
                    <td style="text-align: center;"><span id="month-balance"><?=$balanceMonth?></span>$</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form action="/adm/dashboard/balance-u/<?=$id_user?>/" method="get" class="dashboard-form">
                            <div class="input-group">
                                <select class="input-group-field" name="interval" onchange="this.form.submit()">
                                    <?php if(is_array($listInterval)):?>
                                        <?php foreach ($listInterval as $interval):?>
                                            <?php $year_month = $interval['year'] . '-' . Balance::formatMonth($interval['month'])?>
                                            <option <?=(isset($_GET['interval']) && $_GET['interval'] == $year_month) ? 'selected' : ''?> value="<?=$year_month?>">
                                                <?=$interval['year'] . ' ' . $interval['month_name']?> || <?=Balance::getBalanceMonthByPartner($id_user, $year_month)?>$
                                            </option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                        </form>
                    </td>
                </tr>

                <?php if($user->role == 'administrator-fin'):?>
                <tr id="penalty">
                    <td colspan="2">Penalties</td>
                </tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>

        <div class="column medium-4 hide" id="block-penalty">
            <table class="dashboard-section" border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Charging penalties</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2">
                        <form action="" method="post" class="dashboard-form" id="send-penalty-form">
                            <label for="">Customer</label>
                            <select class="input-group-field" name="id_customer">
                                <?php if(is_array($listCustomer)):?>
                                    <?php foreach ($listCustomer as $customer):?>
                                        <option value="<?=$customer['id']?>">
                                            <?=$customer['customer_name']?>
                                        </option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>

                            <label for="">Amount of penalty</label>
                            <div class="input-group">
                                <span class="input-group-label">$</span>
                                <input class="input-group-field" type="text"  name="penalty">
                            </div>

                            <label>
                                Comments
                                <textarea name="comment" rows="2"></textarea>
                            </label>
                            <input type="hidden" name="interval" value="<?=(isset($_GET['interval'])) ? $_GET['interval'] : '' ?>">
                            <input type="hidden" name="send-penalty" value="true">
                            <input type="hidden" name="_token" value="<?=$_SESSION['_token']?>">
                            <div class="" style="margin-top: 10px">
                                <input type="submit" class="button float-right" value="Send">
                                <input type="submit" id="cancel-penalty" class="button float-left" value="Cancel">
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
                <caption>Operations for <?=(isset($_GET['interval'])) ? Balance::getNameMonth($_GET['interval']) : $changeDate?></caption>
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
                            <td class="<?= Balance::getStatusRequest($details['balance'])?>"><?=$details['balance']?>$</td>
                            <td><?=$details['customer_name']?></td>
                            <td><?=$details['action_balance']?></td>
                            <td><?=$details['section']?></td>
                            <td><?=$details['id_row_section']?></td>
                            <td><?=$details['comment']?></td>
                            <td class="<?=Balance::getStatusPaid($details['status'])?>"><?=$details['status']?></td>
                            <td><?=$details['date_create']?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>

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

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>