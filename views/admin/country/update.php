<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-6 medium-offset-3 small-12 columns">
            <h2>Добавить страну</h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post" class="form">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Полное название
                                    <input type="text" name="full_name" value="<?=$countryById['full_name']?>" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Краткое название
                                    <input type="text" name="short_name" value="<?=$countryById['short_name']?>" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" name="update_country" class="button small float-right" value="Сохранить">
                                <a href="/adm/users" class="button small info"> Назад</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>