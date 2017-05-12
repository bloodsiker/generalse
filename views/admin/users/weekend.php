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
                <div class="medium-5 medium-offset-2 small-12 columns">
                    <h2 class="float-left">Список выходных для <?=$userInfo['name_partner']?></h2>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Название праздника</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listWeekend)): ?>
                            <?php foreach ($listWeekend as $weekend): ?>
                                <tr>
                                    <td><?=$weekend['date_weekend']?></td>
                                    <td><?=$weekend['description']?></td>
                                    <td>
                                        <a href="/adm/user/weekend/update/<?=$weekend['id']?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                                        <a href="/adm/user/weekend/delete/<?=$weekend['id']?>" class="button no-margin small"
                                           onclick="return confirm('Вы уверены что хотите удалить дату?') ? true : false;"><i
                                                class="fi-x"></i></a></td>
                                </tr>
                            <?php endforeach;; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="medium-3 small-12 columns">
                    <h2>Добавить выходной</h2>
                    <form action="" method="post">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Дата
                                    <input type="text" id="date-start" name="date_weekend" class="hasDatepicker" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Название праздника
                                    <input type="text" name="description"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" name="add_weekend" class="button small float-right" value="Добавить">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>