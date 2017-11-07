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
            <div class="row" style="margin-top: 20px">
                <div class="medium-12 small-12 columns text-center">
                    <h2>Group - <b>&laquo;  <?= $group->getNameGroup($id_group) ?>&raquo;</b></h2>
                </div>
            </div>
            <div class="row body-content">
                <div class="medium-12 small-12 columns">
                    <h2 class="float-left">Список разделов в шапке</h2>
                    <a href="/adm/group/<?= $id_group?>" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="300">Раздел</th>
                            <th>slug</th>
                            <th width="100">Доступ</th>
                            <th width="50"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list_page)):?>
                            <?php foreach ($list_page as $page):?>
                                <tr style="<?= ($page['id'] == $p_id) ? 'background: #66bb6a' : ''?>">
                                    <td><?= $page['name']?></td>
                                    <td><?= $page['slug']?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="name" value="<?= $page['name']?>">
                                            <input type="hidden" name="slug" value="<?= $page['slug']?>">
                                            <?php if(!in_array($page['slug'], $new_array)):?>
                                                <input type="hidden" name="action" value="denied">
                                                <input type="submit" class="alert button tiny expanded" value="Denied">
                                            <?php else:?>
                                                <input type="hidden" name="action" value="success">
                                                <input type="submit" class="success button tiny expanded" value="Access">
                                            <?php endif;?>
                                        </form>
                                    </td>
                                    <td>
                                        <?php if($page['sub_menu'] == 1):?>
                                            <a href="/adm/group/denied/<?=$id_group?>/<?= $page['id']?>" class="button no-margin small"><i class="fi-list"></i></a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>


                <div class="medium-9 small-12 columns">
                    <?php if(isset($sub_menu) && is_array($sub_menu) && !empty($sub_menu)):?>
                    <h2 class="float-left">Список подразделов</h2>
                    <div class="clearfix"></div>
                    <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th width="300">Раздел</th>
                            <th>slug</th>
                            <th width="100">Доступ</th>
                            <th width="50"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sub_menu as $sub):?>
                            <tr style="<?= ($sub['id'] == $sub_id) ? 'background: #66bb6a' : ''?>">
                                <td><?=$sub['name']?></td>
                                <td><?=$sub['slug']?></td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="name" value="<?= $sub['name']?>">
                                        <input type="hidden" name="slug" value="<?= $sub['slug']?>">
                                        <?php if(!in_array($sub['slug'], $new_array)):?>
                                            <input type="hidden" name="action" value="denied">
                                            <input type="submit" class="alert button tiny expanded" value="Denied">
                                        <?php else:?>
                                            <input type="hidden" name="action" value="success">
                                            <input type="submit" class="success button tiny expanded" value="Access">
                                        <?php endif;?>
                                    </form>
                                </td>
                                <td>
                                    <?php if($sub['sub_menu'] == 2):?>
                                        <a href="/adm/group/denied/<?=$id_group?>/<?= $sub['p_id']?>/<?= $sub['id']?>" class="button no-margin small"><i class="fi-list"></i></a>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php endif;?>
                </div>

                <div class="medium-3 small-12 columns">
                    <?php if(isset($sub_menu_button) && is_array($sub_menu_button) && !empty($sub_menu_button)):?>
                        <h2 class="float-left">Button</h2>
                        <div class="clearfix"></div>
                        <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="100px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($sub_menu_button as $button):?>
                                <tr>
                                    <td><?= $button['name']?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="name" value="<?= $button['name']?>">
                                            <input type="hidden" name="slug" value="<?= $button['slug']?>">
                                            <?php if(!in_array($button['slug'], $new_array)):?>
                                                <input type="hidden" name="action" value="denied">
                                                <input type="submit" class="alert button tiny expanded" value="Denied">
                                            <?php else:?>
                                                <input type="hidden" name="action" value="success">
                                                <input type="submit" class="success button tiny expanded" value="Access">
                                            <?php endif;?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    <?php endif;?>
                </div>

            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>