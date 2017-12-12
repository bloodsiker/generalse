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
                    <form action="/adm/user/control/multi-delete" method="post">
                        <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                            <thead>
                            <tr>
                                <th width="50px">ID</th>
                                <th>Имя</th>
                                <th width="70">Action</th>
                                <th width="30"><input type="checkbox" id="checkAll"> <label for="checkAll">All</label></th>
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
                                        <td><input class="delete_user" type="checkbox" name="delete_users[]" value="<?=$userI['control_user_id']?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3"></td>
                                <td><button class="button no-margin small">Delete</button></td>
                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">
                        <input type="hidden" name="multi_delete_user" value="true">
                    </form>
                </div>
                <div class="medium-4 small-12 columns">

                    <div class="clearfix"></div>
                    <form action="" method="post">
                        <fieldset class="fieldset">
                            <legend>Пользователи</legend>
                            <div style="height: 400px; overflow-y: scroll">
                                <?php foreach ($userInGroup as $groups):?>
                                    <div class="parent-block">
                                        <div class="aqua" style="padding-left: 10px">
                                            <input class="select-group" type="checkbox"  id="group-<?= $groups['group_id']?>">
                                            <label for="group-<?= $groups['group_id']?>"><?= $groups['group_name']?></label>
                                        </div>
                                        <div class="child-block show"  style="margin-left: 25px; display: none">
                                            <?php foreach($groups['users'] as $userC):?>
                                                <?php if(!$user->checkUserInControl($id_user, $userC['id_user'])):?>
                                                   <input class="children-input-group" type="checkbox" id="id-<?=$userC['id_user'] ?>" name="id_user[]" value="<?=$userC['id_user'] ?>">
                                                    <label  class="check" for="id-<?=$userC['id_user'] ?>" ><?=$userC['name_partner'] ?></label><br>
                                                <?php endif;?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                            <input type="hidden" name="add_multi-user_control" value="true">
                            <button type="submit" style="margin-top: 30px" class="button small float-right">Добавить</button>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>