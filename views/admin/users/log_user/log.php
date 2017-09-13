<?php require_once ROOT . '/views/admin/layouts/header.php' ?>
    <div class="row admin_head_menu">
        <div class="medium-10 small-12 columns">
            <ul class="admin_menu float-right">

                <?php require_once ROOT . '/views/admin/layouts/admin_menu.php' ?>

            </ul>
        </div>
    </div>

    <div class="row" style="margin-bottom: 200px">
        <div class="medium-12 small-12 columns">
            <div class="row body-content">
                <div class="medium-10 medium-offset-1 small-12 columns">
                    <h2 class="float-left">Логи пользователей</h2>
                    <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Имя</th>
                            <th>Лог</th>
                            <th>IP-user</th>
                            <th>User agent</th>
                            <th width="150px">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($allLogs)): ?>
                            <?php foreach ($allLogs as $log): ?>
                                <tr>
                                    <td><?=$log['id_log']?></td>
                                    <td><?=$log['name_partner']?></td>
                                    <td><?=$log['log_text']?></td>
                                    <td><?=$log['ip_user']?></td>
                                    <td><?=$log['user_agent']?></td>
                                    <td><?=$log['date_log']?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if(count($allLogs) >= 30):?>
                        <div class="text-center button_load">
                            <div class="button primary" style="width: inherit;" id="load-log">Показать еще</div>
                            <div class="text-center">
                                <img src="/template/admin/img/loading.gif" id="imgLoad">
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>