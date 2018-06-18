
<footer class="pt-4 pb-2">
    <div class="container">
        <div class="row align-justify">
            <div class="col-md-4">
                <h5><b>General Services Europe - an international service provider</b></h5>
                <ul>
                    <li><a href="/storage/policy.pdf">﻿Policy</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5><b>Services</b></h5>
                <ul class="mb-3">
                    <li><a href="/services/manufacturers">Spare parts logistics</a></li>
                    <li><a href="/services/repair-centers">Repair of equipment</a></li>
                    <li><a href="/recycling">Recycling</a></li>
                    <li><a href="/services/enterprises">Purchase of equipment</a></li>
                    <li><a href="/services/manufacturers">Call center services</a></li>
                    <li><a href="http://pex.com.ua/" target="_blank">Online store</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>
                    <b>General Services Europe</b>
                </h5>
                <p>
                    4, Narva road,<br>
                    Tallinn, Estonia<br>
                    Tel: 044-338-25-59<br>
                    <a href="mailto:hq@generalse.com">hq@generalse.com</a>
                </p>
            </div>
            <div class="col-md-2">

                <h5>
                    <b>Follow us!</b>
                </h5>
                <p>Keep abreast of our new ideas created especially for you.</p>
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

        <div class="text-center">General Services Europe. 2018</div>
    </div>

</footer>

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send a request for account registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="sign-up-form" data-form="send" action="/sign_up" method="post">
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" name="country">
                    </div>
                    <div class="form-group">
                        <label>First Name and Last Name</label>
                        <input type="text" class="form-control required" name="fio" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Company name</label>
                        <input type="text" class="form-control required" name="company">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control required" name="email">
                    </div>
                    <div class="form-group">
                        <label>Login</label>
                        <input type="text" class="form-control required" name="login">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="(099) 999-99-99" class="phone required form-control" autocomplete="off" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control required" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>What are the groups of products</label>
                        <input type="text" name="group_products" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="message" class="form-control" cols="30" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="lang" value="en">
                    <input type="hidden" name="sign_up" value="true">
                    <button type="submit" id="btn-sign-up" class="btn btn-red float-right">Отправить</button>
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
                <form action="/auth" method="post">
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
                        <input type="hidden" name="lang" value="en">
                        <input type="hidden" name="action" value="post_login">
                        <button class="btn btn-white w-100 mr-2" type="button" data-toggle="modal" data-target="#registrationModal">Registrations</button>
                        <button class="btn btn-red" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="thank" tabindex="-1" role="dialog" aria-labelledby="thank" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 30px; text-align: center">
                    <h5>The request has been sent!
                        <br> Expect, you will be contacted in the near future
                    </h5>
                </div>
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
<script src="/template/new_site/js/jquery.vmap.sampledata.js?v.1.0.1"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lettering.js/0.7.0/jquery.lettering.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/textillate/0.4.0/jquery.textillate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="/template/new_site/js/object.js"></script>
<script src="/template/new_site/js/app.js?v.1.5.2"></script>

</body>
</html>