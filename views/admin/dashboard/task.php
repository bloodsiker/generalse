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
                <caption>Tasks</caption>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Раздел</th>
                    <th>Заказчик</th>
                    <th width="90">Активный</th>
                    <th width="40" class="text-center"><i class="fi-wrench"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($taskList)):?>
                    <?php foreach ($taskList as $task):?>
                        <tr>
                            <td><?=$task['id']?></td>
                            <td><?=$task['step_0'] ?></td>
                            <td><?=$task['step_2']?></td>
                            <td><?=$task['customer_name']?></td>
                            <td class="text-center">
                                <?php if($task['is_active'] == 1):?>
                                <div class="is-active-task">Yes</div>
                            <?php elseif ($task['is_active'] == 0):?>
                                    <div class="is-inactive-task">No</div>
                            <?php endif;?>
                            </td>
                            <td class="text-center"><a href="" class="task-setting"><i class="fi-wrench"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>


<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>