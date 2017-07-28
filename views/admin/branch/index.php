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
                    <h2 class="float-left">Список пользователей в branch</h2>
                    <a href="/adm/users" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Имя</th>
                            <th>Роль</th>
                            <th width="70">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($userListBranch)): ?>
                            <?php foreach ($userListBranch as $userI): ?>
                                <tr>
                                    <td><?=$userI['id_user']?></td>
                                    <td><?=$userI['name_partner']?></td>
                                    <td><?=$userI['name_role']?></td>
                                    <td><a href="/adm/branch/delete/<?=$id_branch?>/<?= $userI['id_user'] ?>" class="button no-margin small"
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
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Пользователи</th>
                            <th>Branch</th>
                            <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                <th width="100px">Action</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listUsers)): ?>
                            <?php foreach ($listUsers as $userM): ?>
                                <tr>
                                    <td><?= $userM['name_partner']?></td>
                                    <td><?= $userM['branch_name']?></td>
                                    <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                        <td>
                                            <input type="checkbox" <?=(Umbrella\models\Branch::checkUserInBranch($allUserBranch, $userM['id_user']) ? 'checked disabled' : '')?> name="id_user[]" value="<?=$userM['id_user']?>">
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td colspan="3">
                                <input type="hidden" name="add_user_branch" value="true">
                                <button type="submit" style="margin-bottom: 0" class="button small float-right">Сохранить</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>