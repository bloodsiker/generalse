<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-12 small-12 columns text-center">
            <h2>Добавить пользователя</h2>
        </div>
        <div class="medium-12 small-12 columns">
            <form method="post" class="form" id="create-user" data-abide novalidate>
                <div class="row body-content">
                    <div class="medium-6 medium-offset-3 small-12 columns" id="register-umbrella" style="background: #EFEFEF; border: 1px solid #323e48; padding-bottom: 10px">
                        <h2 class="text-center">Информация для Umbrella</h2>
                        <input type="hidden" name="_token" value="<?=\Josantonius\Session\Session::get('_token')?>">
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Роль
                                    <select name="role" class="required" required>
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
                                    <select name="id_country" class="required" required>
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
                                    <input type="text" name="name_partner" class="required" autocomplete="off" required/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Логин
                                    <input type="text" name="login" id="login" autocomplete="off" class="required" required/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Email
                                    <input type="text" name="email" autocomplete="off" />
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Пароль
                                    <div class="input-group">
                                        <input type="password" id="user-password" name="password" class="required" autocomplete="off" required/>
                                        <span class="input-group-label switch-type" onclick="password_rand()">Сгенерировать</span>
                                        <span class="input-group-label switch-type" onclick="switch_type()"><i class="fi fi-eye"></i></span>
                                    </div>
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Перенаправление после авторизации
                                    <select name="login_url" class="required" required>
                                        <option value="adm/crm/orders">CRM/Orders</option>
                                        <option value="adm/crm/request">CRM/Request</option>
                                        <option value="adm/psr/ua">PSR/PSR UA</option>
                                        <option value="adm/ccc">CCC</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Группа
                                    <select name="id_group">
                                        <option value=""></option>
                                        <?php if (is_array($groupList)): ?>
                                            <?php foreach ($groupList as $group): ?>
                                                <option value="<?=$group['id']?>"><?=$group['group_name']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Отображение в KPI
                                    <select name="kpi_view" class="required" required>
                                        <option value="0">Не отображать</option>
                                        <option value="1">Отображать</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>



                    <div class="medium-6 small-12 columns hide" id="register-gm" style="background: #EFEFEF; border: 1px solid #323e48; padding-bottom: 10px">
                        <h2 class="text-center">Информация для GM</h2>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Имя на английском
                                    <input type="text" name="name_en" autocomplete="off"/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Адрес
                                    <textarea name="address" cols="30" rows="2"></textarea>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Адрес на английском
                                    <textarea name="address_en" cols="30" rows="2"></textarea>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Для ТТН
                                    <input type="text" name="for_ttn" autocomplete="off">
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Валюта
                                    <select name="curency_id">
                                        <option value=""></option>
                                        <?php if (is_array($currencyList)): ?>
                                            <?php foreach ($currencyList as $currency): ?>
                                                <option value="<?=$currency['number']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $currency['shortName'])?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Цены
                                    <select name="abcd_id">
                                        <option value=""></option>
                                        <?php if (is_array($ADBCPriceList)): ?>
                                            <?php foreach ($ADBCPriceList as $ADBCPrice): ?>
                                                <option value="<?= $ADBCPrice['number']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $ADBCPrice['priceName'])?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Признак для поляков
                                    <select name="to_electrolux">
                                        <option value=""></option>
                                        <option value="1">Да</option>
                                        <option value="0">Нет</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Признак для почты в бухгалтерию
                                    <select name="to_mail_send">
                                        <option value=""></option>
                                        <option value="1">Да</option>
                                        <option value="0">Нет</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Договор
                                    <input type="text" name="contract_number" autocomplete="off"/>
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Ответственный
                                    <select name="staff_id">
                                        <option value=""></option>
                                        <?php if (is_array($staffList)): ?>
                                            <?php foreach ($staffList as $staff): ?>
                                                <option value="<?= $staff['i_d']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $staff['displayName'])?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Местоположение
                                    <select name="stock_place_id">
                                        <option value=""></option>
                                        <?php if (is_array($stockPlaceList)): ?>
                                            <?php foreach ($stockPlaceList as $stockPlace): ?>
                                                <option value="<?= $stockPlace['stockPlaceID']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $stockPlace['stockPlaceName'])?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Телефон
                                    <input type="text" name="phone" autocomplete="off"/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Email
                                    <input type="text" name="gm_email" autocomplete="off"/>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Регион\Город
                                    <select name="region_id">
                                        <option value=""></option>
                                        <?php if (is_array($regionList)): ?>
                                            <?php foreach ($regionList as $region): ?>
                                                <option value="<?= $region['i_d']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $region['mname'])?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="medium-6 medium-offset-3 small-12 columns" id="register-user" style="background: #EFEFEF; border: 1px solid #323e48; padding-top: 10px">
                        <input type="hidden" name="add_user" value="true">
                        <a href="/adm/users" class="button primary tool small"><i class="fi-arrow-left"></i> Назад</a>
                        <button type="submit" id="add_user" class="button primary tool small">Создать</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>