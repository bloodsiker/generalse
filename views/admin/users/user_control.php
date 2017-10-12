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
                <div class="medium-8 small-12 columns">
                    <h2 class="float-left">Список пользователей для управления(<?= count($listControlUsers)?>)</h2>
                    <a href="/adm/users" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Имя</th>
                            <th width="70">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listControlUsers)): ?>
                            <?php foreach ($listControlUsers as $userI): ?>
                                <tr>
                                    <td><?=$userI['control_user_id']?></td>
                                    <td><?=$userI['name_partner']?></td>
                                    <td><a href="/adm/user/control/delete/<?=$id_user?>/<?= $userI['control_user_id'] ?>" class="button no-margin small"
                                           onclick="return confirm('Вы уверены?') ? true : false;"><i
                                                class="fi-x"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="medium-4 small-12 columns">
                    <h2 class="float-left">Пользователи</h2>
                    <div class="clearfix"></div>
                    <form action="" method="post">
                        <select name="id_user" id="">
                            <?php foreach ($listUsers as $userC):?>
                                <option <?= ($user->checkUserInControl($id_user, $userC['id_user']) ? 'disabled' : '')?> value="<?=$userC['id_user']?>"><?=$userC['name_partner']?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" name="add_user_control" value="true">
                        <button type="submit" style="margin-top: 15px" class="button small float-right">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>