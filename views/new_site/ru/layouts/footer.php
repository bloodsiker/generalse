
<footer class="pt-4 pb-2">
    <div class="container">
        <div class="row align-justify">
            <div class="col-md-4">
                <h5><b>General Services Inc. – International
                        Parts Logistics, Repair & Recycling</b></h5>
                <p>
                    General Services Ukraine LLC<br>
                    1-3, Severosuretskaya street, <br>
                    04116, Kiev, Ukraine<br>
                    0 800 501 279 <br>
                    044 338 25 59 <br>
                    <a href="../../../../index.php">es@generalse.com</a>
                </p>
            </div>
            <div class="col-md-3">
                <h5><b>Services</b></h5>
                <ul class="mb-3">
                    <li><a href="#">Parts logistics</a></li>
                    <li><a href="#">Repair services</a></li>
                    <li><a href="#">Recycling</a></li>
                    <li><a href="#">Sell your device</a></li>
                    <li><a href="#">Contact Center</a></li>
                    <li><a href="#">Shop online</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>
                    <b>General Services Europe</b>
                </h5>
                <p>
                    Estonia <br>
                    4, Narva mnt, Tallin <br>
                    10117, Estonia <br>
                    <a href="../../../../index.php">ee@generalse.com</a>
                </p>
            </div>
            <div class="col-md-2">

                <h5>
                    <b>Connect with us!</b>
                </h5>
                <p>Stay in touch with our most useful
                    Insights personally for you</p>
                <div class="socialMenu">

                    <div class="social-menu">

                        <ul class="nav">

                            <li class="menu-item">
                                <a target="_blank" href="https://www.facebook.com/generalservicesua/?timeline_context_item_type=intro_card_work&timeline_context_item_source=100001806222604&pnref=lhc">
                                    <img style="background-color: #fff;" src="/template/new_site/img/icon-facebook.svg" width="30px" alt="">
                                </a>
                            </li>

                            <li class="menu-item">
                                <a target="_blank" href="https://www.linkedin.com/company/general-services-europe/">
                                    <img src="/template/new_site/img/icon-linkedin.svg" width="30px" alt="">
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">Copyright © 2018 General Services LLC</div>
    </div>


</footer>


<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Отправить заявку на регистрацию аккаунта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sign-up-form" action="#" method="post">
                    <div class="form-group">
                        <label>Страна</label>
                        <input type="text" class="form-control" name="country">
                    </div>
                    <div class="form-group">
                        <label>ФИО</label>
                        <input type="text" class="form-control" name="fio" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Наименование компании</label>
                        <input type="text" class="form-control" required name="company">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" required name="email">
                    </div>
                    <div class="form-group">
                        <label>Логин</label>
                        <input type="text" class="form-control" required name="login">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" name="phone" placeholder="(099) 999-99-99" class="phone required form-control" required="" autocomplete="off" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label>Адрес</label>
                        <input type="text" name="address" class="form-control" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Какие интересуют группы товаров</label>
                        <input type="text" name="group_products" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Комментарий</label>
                        <textarea name="message" class="form-control" cols="30" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-red float-right">Отправить</button>

                    <input type="hidden" name="sign_up" value="true">

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign in</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" >
                    <strong >Login to Umbrella Project</strong>
                    <div class="mt-3 form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" name="login">
                    </div>
                    <div class="form-group">
                        <label for="login">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-white w-100 mr-2" type="button" data-toggle="modal" data-target="#registrationModal">Registrations</button>
                        <button class="btn btn-red" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox-plus-jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>

<script src="/template/new_site/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-animateNumber/0.0.14/jquery.animateNumber.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.europe.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.world.js"></script>
<script src="/template/new_site/js/jquery.vmap.sampledata.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lettering.js/0.7.0/jquery.lettering.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/textillate/0.4.0/jquery.textillate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>

<script src="/template/new_site/js/app.js"></script>

<script src="/template/new_site/js/app.js"></script>

</body>
</html>