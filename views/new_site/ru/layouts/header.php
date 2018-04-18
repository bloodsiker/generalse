<header>
    <nav class="navbar navbar-expand-lg navbar-light box-shadow scrolled">
        <div class="container  align-items-end">
            <a class="navbar-brand" href="/ru">
                <img class="site-logo" src="/template/new_site/img/logo.svg" alt="generalse">
            </a>
            <div class="d-flex align-items-center">

                <button class="navbar-toggler border-0" type="button" data-toggle="modal" data-target="#loginModal">
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                </button>
                <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto align-items-lg-center">

                    <li class="nav-item dropdown <?= Umbrella\components\Url::IsActive('/ru/about', 'active') ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">О компании</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="aboutDropdown">
                            <a class="dropdown-item" href="/ru/about/company-information">Профайл</a>
                            <a class="dropdown-item" href="/ru/about/geography">География</a>
                            <a class="dropdown-item" href="/ru/about/responsibility">Ответственность</a>
                            <a class="dropdown-item" href="/ru/about/certificates">Сертификаты</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown <?= Umbrella\components\Url::IsActive('/ru/services', 'active') ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Услуги</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="servicesDropdown">
                            <a class="dropdown-item" href="/ru/services/manufacturers">Производителям</a>
                            <a class="dropdown-item" href="/ru/services/retailers">Розничным сетям</a>
                            <a class="dropdown-item" href="/ru/services/repair-centers">Корпоративным клиентам</a>
                            <a class="dropdown-item" href="/ru/services/enterprises">Сервисным центрам</a>
                        </div>
                    </li>

                    <li class="nav-item d-none <?= Umbrella\components\Url::IsActive('/ru/recycling', 'active') ?>"><a class="nav-link" href="/ru/recycling">Переработка</a></li>
                    <li class="nav-item <?= Umbrella\components\Url::IsActive('/ru/news', 'active') ?>"><a class="nav-link" href="/ru/news">Новости</a></li>
                    <li class="nav-item <?= Umbrella\components\Url::IsActive('/ru/career', 'active') ?>"><a class="nav-link" href="/ru/career">Карьера</a></li>


                    <li class="nav-item <?= Umbrella\components\Url::IsActive('/ru/suppliers', 'active') ?>"><a class="nav-link" href="/ru/suppliers">Поставщикам</a></li>
                    <li class="nav-item <?= Umbrella\components\Url::IsActive('/ru/contacts', 'active') ?>"><a class="nav-link" href="/ru/contacts">Контакты</a></li>

                    <?php if(\Josantonius\Session\Session::get('user')):?>
                        <li class="nav-item"><a class="nav-link" href="/adm/crm/">Кабинет</a></li>
                    <?php else:?>
                        <li class="nav-item dropdown dropdown-not-hover ml-4  login-item-menu">
                            <a class="nav-link dropdown-toggle" href="#" id="careerDropdown" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Войти
                            </a>
                            <div style="border-radius: 0px;border: none;box-shadow: 0 0 7px rgba(0, 0, 0, 0.1);" class="dropdown-menu dropdown-menu-right p-3 hidden-md-down" aria-labelledby="careerDropdown">
                                <form action="/auth" method="post" id="form-auth" style="width: 250px;">
                                    <strong >Войти в Umbrella</strong>
                                    <div class="mt-3 form-group">
                                        <label for="login">Логин</label>
                                        <input type="text" class="form-control login_umbrella" name="login" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="login">Пароль</label>
                                        <input type="password" class="form-control password_umbrella" name="password">
                                    </div>
                                    <div class="d-flex">
                                        <input type="hidden" name="lang" value="ru">
                                        <input type="hidden" name="action" value="post_login">
                                        <button class="btn btn-white w-100 mr-2" type="button" data-toggle="modal" data-target="#registrationModal">Регистрация</button>
                                        <button class="btn btn-red" id="login_umbrella" type="submit">Войти</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    <?php endif;?>


                    <li class="nav-item dropdown ml-4 nav-langs">
                        <a class="nav-link dropdown-toggle" href="/lang/ru/change" id="servicesDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Рус</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="servicesDropdown">
                            <a class="dropdown-item" href="/lang/en/change">Eng</a>
                        </div>
                    </li>


                </ul>
            </div>
        </div>

    </nav>
</header>

