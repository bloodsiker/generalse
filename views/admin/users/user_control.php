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
                    <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
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
                    <div class="clearfix"></div>
                    <form action="" method="post">
                        <fieldset class="fieldset">
                            <legend>Пользователи</legend>
                            <div style="height: 400px; overflow-y: scroll">
                                <?php foreach ($listUsers as $userC):?>
                                    <?php if(!$user->checkUserInControl($id_user, $userC['id_user'])):?>
                                        <input id="user-<?=$userC['id_user']?>" type="checkbox" name="id_user[]" value="<?=$userC['id_user']?>">
                                        <label for="user-<?=$userC['id_user']?>"><?=$userC['name_partner']?></label>
                                        <br>
                                    <?php endif;?>
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