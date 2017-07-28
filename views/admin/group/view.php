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
                <div class="medium-6 small-12 columns">
                    <h2 class="float-left">Список пользователей в группе <?= $group->getNameGroup($id_group)?></h2>
                    <a href="/adm/users" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Имя</th>
                            <th width="70">Доступы</th>
                            <th width="70">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listUserByGroup)): ?>
                            <?php foreach ($listUserByGroup as $userI): ?>
                                <tr>
                                    <td><?=$userI['id_user']?></td>
                                    <td><?=$userI['name_partner']?></td>
                                    <td>
                                    <?php if (Umbrella\app\AdminBase::checkDenied('user.denied', 'view')): ?>
                                        <a href="/adm/user/denied/<?=$userI['id_user']?>" class="button no-margin small"><i class="fi-wrench"></i></a>
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (Umbrella\app\AdminBase::checkDenied('group.user.delete', 'view')): ?>
                                            <a href="/adm/group/delete/user/<?=$id_group?>/<?= $userI['id_user'] ?>" class="button no-margin small"
                                               onclick="return confirm('Вы уверены?') ? true : false;"><i
                                                    class="fi-x"></i></a>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="medium-4 small-12 columns">
                    <h2>Добавить пользователя</h2>
                    <form action="" method="post">
                        <select name="id_user">
                            <option value=""></option>
                            <?php foreach ($listUsers as $userG):?>
                                <option <?= ($group->checkUserInGroups($userG['id_user']) ? 'disabled' : '')?> value="<?=$userG['id_user']?>"><?=$userG['name_partner']?></option>
                            <?php endforeach;?>
                        </select>
                        <?php if (Umbrella\app\AdminBase::checkDenied('group.user.add', 'view')): ?>
                            <input type="hidden" name="add_user_group" value="true">
                            <button type="submit" style="margin-top: 15px" class="button small float-right">Добавить</button>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="medium-2 small-12 columns">
                    <?php if (Umbrella\app\AdminBase::checkDenied('group.stock.view', 'view')): ?>
                        <h2>Разделы</h2>
                        <ul class="menu-section">
                            <li><a href="/adm/group/<?=$id_group?>/stock/purchase">Purchase</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/order">Order</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/returns">Returns</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/stocks">Stocks</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/disassembly">Disassembly</a></li>
                            <li><a href="/adm/group/<?=$id_group?>/stock/supply">Supply</a></li>
                        </ul>
                    <?php endif; ?>


                    <h2>Group denied</h2>
                    <ul class="menu-section">
                        <li><a href="/adm/group/denied/<?=$id_group?>">Denied</a></li>
                    </ul>

                </div>

            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>