<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-6 medium-offset-3 small-12 columns">
            <h2>Добавить пользователя</h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?=$_SESSION['_token']?>">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Роль
                                    <select name="role">
                                        <?php if (is_array($roleList)): ?>
                                            <?php foreach ($roleList as $role): ?>
                                                <option value="<?=$role['id_role']?>"><?=$role['name_role']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Страна
                                    <select name="id_country">
                                        <?php if (is_array($countryList)): ?>
                                            <?php foreach ($countryList as $country): ?>
                                                <option value="<?=$country['id_country']?>"><?=$country['short_name'] . " - " . $country['full_name']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Имя
                                    <input type="text" name="name_partner" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Логин
                                    <input type="text" name="login" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Пароль
                                    <input type="password" name="password" required/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Отображение в KPI
                                    <select name="kpi_view">
                                        <option value="0">Не отображать</option>
                                        <option value="1">Отображать</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" name="add_user" class="button small float-right" value="Создать">
                                <a href="/adm/users" class="button small info"> Назад</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>