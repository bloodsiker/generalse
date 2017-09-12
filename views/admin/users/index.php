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
                <div class="medium-9 small-12 columns">
                    <h2 class="float-left">Список пользователей</h2>
                    <?php if (Umbrella\app\AdminBase::checkDenied('user.add', 'view')): ?>
                        <a href="/adm/user/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                    <?php endif;?>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Имя</th>
                            <th>Страна</th>
                            <th>Логин</th>
                            <th>Роль</th>
                            <th>Группа</th>
                            <th>Активность</th>
                            <th>Доступы</th>
                            <th>Управление <br> пользователями</th>
                            <th width="150px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listUsers)): ?>
                            <?php foreach ($listUsers as $userI): ?>
                                <tr>
                                    <td><?=$userI['id_user']?></td>
                                    <td><?=$userI['name_partner']?></td>
                                    <td><?=$userI['short_name'] . " - " . $userI['full_name']?></td>
                                    <td><?=$userI['login']?></td>
                                    <td><?=$userI['name_role']?></td>
                                    <td><?=$userI['group_name']?></td>
                                    <td><?=$userI['date_active']?></td>
                                    <td>
                                        <?php if (Umbrella\app\AdminBase::checkDenied('user.denied', 'view')): ?>
                                            <a href="/adm/user/denied/<?=$userI['id_user']?>" class="button no-margin small"><i class="fi-wrench"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (Umbrella\app\AdminBase::checkDenied('user.control', 'view')): ?>
                                            <a href="/adm/user/control/<?=$userI['id_user']?>" class="button no-margin small"><i class="fi-eye"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('user.delete', 'view')): ?>
                                                <a href="/adm/user/delete/<?=$userI['id_user']?>" class="button no-margin small" onclick="return confirm('Вы уверены что хотите удалить пользователя?') ? true : false;"><i class="fi-x"></i></a>
                                            <?php endif; ?>

                                            <?php if (Umbrella\app\AdminBase::checkDenied('user.edit', 'view')): ?>
                                                <a href="/adm/user/update/<?=$userI['id_user']?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                                            <?php endif; ?>

                                            <?php if (Umbrella\app\AdminBase::checkDenied('user.lock', 'view')): ?>
                                                <button data-userid="<?=$userI['id_user']?>" class="button no-margin small <?= $userI['is_active'] == 1 ? 'green' : 'red'?>">
                                                    <i class="fi-<?= $userI['is_active'] == 1 ? 'unlock' : 'lock'?>"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="medium-3 small-12 columns">
                    <h2 class="float-left">Group</h2>
                    <?php if (Umbrella\app\AdminBase::checkDenied('group.add', 'view')): ?>
                        <a href="/adm/group/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                    <?php endif;?>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Group</th>
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($groupList)): ?>
                            <?php foreach ($groupList as $group): ?>
                                <tr>
                                    <td><?= $group['group_name']?></td>
                                    <td>
                                    <?php if (Umbrella\app\AdminBase::checkDenied('group.view', 'view')): ?>
                                        <a href="/adm/group/<?= $group['id'] ?>" class="button no-margin small"><i
                                                    class="fi-eye"></i></a>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <div style="margin-bottom: 50px"></div>
                    <h2 class="float-left">Branch</h2>
                    <?php if (Umbrella\app\AdminBase::checkDenied('branch.add', 'view')): ?>
                        <a href="/adm/branch/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                    <?php endif;?>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Branch</th>
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($branchList)): ?>
                            <?php foreach ($branchList as $branch): ?>
                                <tr>
                                    <td><?= $branch['branch_name']?></td>
                                    <td>
                                        <a href="/adm/branch/view/<?= $branch['id_branch'] ?>" class="button no-margin small"><i
                                                class="fi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <div style="margin-bottom: 50px"></div>
                    <h2 class="float-left">Страны</h2>
                    <?php if (Umbrella\app\AdminBase::checkDenied('country.add', 'view')): ?>
                        <a href="/adm/country/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                    <?php endif;?>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Страна</th>
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($countryList)): ?>
                            <?php foreach ($countryList as $country): ?>
                                <tr>
                                    <td><?= $country['short_name'] . " - " . $country['full_name'] ?></td>
                                    <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                        <td>
                                            <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                                <?php if (Umbrella\app\AdminBase::checkDenied('country.delete', 'view')): ?>
                                                    <a href="/adm/country/delete/<?= $country['id_country'] ?>" class="button no-margin small"
                                                   onclick="return confirm('Вы уверены что хотите удалить страну?') ? true : false;"><i
                                                            class="fi-x"></i></a>
                                                <?php endif; ?>

                                                <?php if (Umbrella\app\AdminBase::checkDenied('country.edit', 'view')): ?>
                                                    <a href="/adm/country/update/<?= $country['id_country'] ?>" class="button no-margin small"><i
                                                            class="fi-pencil"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>