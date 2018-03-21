<header>
    <nav class="navbar navbar-expand-lg navbar-light box-shadow scrolled">
        <div class="container  align-items-end">
            <a class="navbar-brand" href="/new/">
                <img class="site-logo" src="/template/new_site/img/logo.svg" alt="generalse">
            </a>
            <div class="d-flex align-items-center">
                <a class="border-0 lang active navbar-toggler" href="#"><img width="20px" src="/template/new_site/img/rus-flug.svg" alt=""></a>
                <a class="border-0 lang navbar-toggler " href="#"><img width="20px" src="/template/new_site/img/uk-flug.svg" alt=""></a>

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

                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">About company</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="aboutDropdown">
                            <a class="dropdown-item" href="/new/about/company-information">Profile</a>
                            <a class="dropdown-item" href="/new/about/geography">Geography</a>
                            <a class="dropdown-item" href="/new/about/responsibility">Responsibility</a>
                            <a class="dropdown-item" href="/new/about/certificates">Certificates</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Services</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="servicesDropdown">
                            <a class="dropdown-item" href="/new/services/manufacturers">Manufacturers</a>
                            <a class="dropdown-item" href="/new/services/retailers">Retail networks</a>
                            <a class="dropdown-item" href="/new/services/repair-centers">Corporate clients</a>
                            <a class="dropdown-item" href="/new/services/enterprises">Service Centers</a>
                        </div>
                    </li>

                    <li class="nav-item d-none"><a class="nav-link" href="#">Trade-In</a></li>
                    <li class="nav-item"><a class="nav-link" href="/new/news">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="/new/career">Career</a></li>


                    <li class="nav-item"><a class="nav-link" href="/new/suppliers">For Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="/new/contacts">Contacts</a></li>

                    <?php if(\Josantonius\Session\Session::get('user')):?>
                        <li class="nav-item"><a class="nav-link" href="/adm/crm/">Cabinet</a></li>
                    <?php else:?>
                        <li class="nav-item dropdown dropdown-not-hover ml-4  login-item-menu">
                            <a class="nav-link dropdown-toggle" href="#" id="careerDropdown" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Sign in
                            </a>
                            <div style="border-radius: 0px;border: none;box-shadow: 0 0 7px rgba(0, 0, 0, 0.1);" class="dropdown-menu dropdown-menu-right p-3 hidden-md-down" aria-labelledby="careerDropdown">
                                <form action="" method="post" id="form-auth" style="width: 250px;">
                                    <strong >Login to Umbrella Project</strong>
                                    <div class="mt-3 form-group">
                                        <label for="login">Login</label>
                                        <input type="text" class="form-control login_umbrella" name="login" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="login">Password</label>
                                        <input type="password" class="form-control password_umbrella" name="password">
                                    </div>
                                    <div class="d-flex">
                                        <input type="hidden" name="lang" value="en">
                                        <button class="btn btn-white w-100 mr-2" type="button" data-toggle="modal" data-target="#registrationModal">Registrations</button>
                                        <button class="btn btn-red" id="login_umbrella" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    <?php endif;?>

                    <li class="nav-item dropdown ml-4 nav-langs">
                        <a class="nav-link dropdown-toggle" href="/lang/en" id="servicesDropdown" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Eng</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="servicesDropdown">
                            <a class="dropdown-item" href="/lang/ru">Рус</a>
                        </div>
                    </li>


                </ul>
            </div>
        </div>

    </nav>
</header>

