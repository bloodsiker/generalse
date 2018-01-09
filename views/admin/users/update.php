<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-6 medium-offset-3 small-12 columns">
            <h2>Редактировать данные пользователя <?=$userInfo['name_partner']?></h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?=\Josantonius\Session\Session::get('_token')?>">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Роль
                                    <select name="role" class="required" required>
                                        <?php if (is_array($roleList)): ?>
                                            <?php foreach ($roleList as $role): ?>
                                                <option value="<?=$role['id_role']?>" <?php if($userInfo['id_role'] == $role['id_role']) echo 'selected'?>><?=$role['name_role']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Страна
                                    <select name="id_country" class="required" required>
                                        <?php if (is_array($countryList)): ?>
                                            <?php foreach ($countryList as $country): ?>
                                                <option value="<?=$country['id_country']?>" <?php if($userInfo['id_country'] == $country['id_country']) echo 'selected'?>><?=$country['short_name'] . " - " . $country['full_name']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Имя
                                    <input type="text" name="name_partner" value="<?=$userInfo['name_partner']?>" placeholder="Имя" <?php if($userInfo['id_role'] == 2) echo 'disabled' ?> class="required" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Логин
                                    <input type="text" name="login" value="<?=$userInfo['login']?>" class="required" required/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Email
                                    <input type="text" name="email" value="<?=$userInfo['email']?>" />
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Перенаправление после авторизации
                                    <select name="login_url" class="required" required>
                                        <option value="adm/crm/" <?php if($userInfo['login_url'] == 'adm/crm/') echo 'selected'?>>CRM</option>
                                        <option value="adm/crm/orders" <?php if($userInfo['login_url'] == 'adm/crm/orders') echo 'selected'?>>CRM/Orders</option>
                                        <option value="adm/crm/request" <?php if($userInfo['login_url'] == 'adm/crm/request') echo 'selected'?>>CRM/Request</option>
                                        <option value="adm/psr/ua" <?php if($userInfo['login_url'] == 'adm/psr/ua') echo 'selected'?>>PSR/PSR UA</option>
                                        <option value="adm/ccc" <?php if($userInfo['login_url'] == 'adm/ccc') echo 'selected'?>>CCC</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Отображение в KPI
                                    <select name="kpi_view" class="required" required>
                                        <option value="0" <?php if($userInfo['kpi_view'] == 0) echo 'selected'?>>Не отображать</option>
                                        <option value="1" <?php if($userInfo['kpi_view'] == 1) echo 'selected'?>>Отображать</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Доступ к проектам</label>
                                <div style="border: 2px solid #323e48; padding: 5px">
                                    <input id="umbrella" type="checkbox" <?= (in_array('umbrella', $userProjects) ? 'checked' : false) ?> name="project[]" value="umbrella">
                                    <label for="umbrella">Umbrella</label>
                                    <br>
                                    <input id="hr" type="checkbox" <?= (in_array('hr', $userProjects) ? 'checked' : false) ?> name="project[]" value="hr">
                                    <label for="hr">HR</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" name="update" class="button small float-right" value="Сохранить">
                                <a href="/adm/users" class="button small info"> Назад</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row body-content" style="background: #EFEFEF; margin-top: 30px ">
                <div class="medium-12 small-12 columns">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?=\Josantonius\Session\Session::get('_token')?>">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Пароль
                                    <div class="input-group">
                                        <input type="password" id="user-password" name="password" class="required" autocomplete="off" required/>
                                        <span class="input-group-label switch-type" onclick="switch_type()"><i class="fi fi-eye"></i></span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" name="update_password" class="button small float-right" value="Сохранить">
                                <a href="/adm/users" class="button small info"> Назад</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>