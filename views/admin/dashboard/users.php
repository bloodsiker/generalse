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
                <caption>Users</caption>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Active</th>
                    <th>Created_at</th>
                    <th width="150">Total balance</th>
                    <th width="70">View</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($partnerList)):?>
                    <?php foreach ($partnerList as $userPartner):?>
                        <tr>
                            <td><?=$userPartner['name_partner']?></td>
                            <td><?=$userPartner['short_name'] . '-' . $userPartner['full_name']?></td>
                            <td><?=$userPartner['date_active']?></td>
                            <td><?=$userPartner['date_create']?></td>
                            <td><?= Umbrella\models\Balance::getBalanceByPartner($userPartner['id_user'])?>$</td>
                            <td><a href="/adm/dashboard/balance-u/<?=$userPartner['id_user']?>" class="button no-margin small"><i class="fi-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>


<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>