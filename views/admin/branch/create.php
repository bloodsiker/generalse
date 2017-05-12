<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-6 medium-offset-3 small-12 columns">
            <h2>Добавить branch</h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post" class="form">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Name branch
                                    <input type="text" name="branch_name" required/>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="add_branch" value="true">
                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" class="button small float-right" value="Создать">
                                <a href="/adm/users" class="button small info"> Назад</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>