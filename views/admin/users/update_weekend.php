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
                <div class="medium-4 medium-offset-4 small-12 columns">
                    <h2>Редактировать выходной</h2>

                        <form action="" method="post">
                            <input type="hidden" name="id_user" value="<?=$weekendInfo['id_user']?>">
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Дата
                                        <input type="text" id="date-start" name="date_weekend" class="hasDatepicker" value="<?=$weekendInfo['date_weekend']?>" required/>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Название праздника
                                        <input type="text" name="description" value="<?=$weekendInfo['description']?>"/>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <input type="submit" name="update_weekend" class="button small float-right" value="Сохранить">
                                    <a href="" onclick="history.back(); return false;" class="button small info"> Назад</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>