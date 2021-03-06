<footer id="footer">
    <div class="row align-justify">
        <div class="medium-3 small-12 columns">
            <p>
                <b>BUSINESS OFFICES:</b>
            </p>
            <p>
                Scotland <br>
                272 Bath street, Glasgow, <br>
                G2 4JR, United Kingdom <br>
                <a href="mailTo: hq@generalse.com">hq@generalse.com</a>
            </p>
            <p>
                Estonia <br>
                4, Narva mnt, Tallin <br>
                10117, Estonia <br>
                <a href="mailTo: ee@generalse.com">ee@generalse.com</a>
            </p>
        </div>
        <div class="medium-4 small-12 columns">
            <p>
                <b>OUR COMPANIES:</b>
            </p>
            <p>
                General Services Ukraine LLC<br>
                1, Severo-Syiretskaya street, <br>
                Kiev, Ukraine<br>
                0 800 501 279 <br>
                044 338 25 59 <br>
                <a href="mailTo: es@generalse.com">es@generalse.com</a>
            </p>
            <p>
                General Services Georgia LLC <br>
                6, Marjanishvili street <br>
                Georgia, Tbilisi, GE-0102<br>
                <a href="mailTo: es@generalse.com">es@generalse.com</a>
            </p>
        </div>
        <div class="medium-3  small-12 columns">
            <p>
                <b>SERVICE COMPANIES CIS:</b>
            </p>
            <p>
                Belarus: <br>
                <a href="http://microdom.by/" target="_blank">Device Care</a> <br>
                <a href="http://service-lenovo.by" target="_blank">General Centre</a></p>
            <p>
                Georgia:<br>
                <a href="" onclick="return false">Servisa ICT</a><br>
                <a href="http://goletiani.ge/" target="_blank">Goletiani</a>
            </p>
            <p>
                Moldova:<br>
                <a href="http://www.ultraservice.md" target="_blank">ULTRASERVICE SRL </a><br>
                <a href="http://accent-service.md/" target="_blank">Accent Electronic Service </a>
            </p>
            <p>
                Armenia:<br>
                <a href="http://www.remzone.ru/" target="_blank">Remzone</a><br>
                <a href="https://www.fine.am/" target="_blank">Fine Service LLC</a>

            </p>
        </div>
    </div>
    <div class="row">
        <div class="medium-12 small-12 columns">
            <p class="copyright">Website designed by <a href="/">General Services Europe<a></p>
        </div>
    </div>
</footer>


<div class="reveal" id="senq" data-reveal>
  <h3>Thank you for contacting us.</h3>
  <p class="lead">We have received your enquiry and will respond to you within 24 hours. </p>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<div class="reveal" id="sign-up" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h5>Отправить заявку на регистрацию аккаунта</h5>
        </div>
        <div class="medium-12 small-12 columns">
            <form action="" id="sign-up-form" method="post" class="form" data-abide novalidate>
                <div class="row align-bottom">

                    <div class="medium-12 small-12 columns">
                        <label>Страна</label>
                        <input type="text" name="country">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>ФИО</label>
                        <input type="text" name="fio" class="required" required autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Наименование компании</label>
                        <input type="text" name="company" class="required" required autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Email</label>
                        <input type="email" name="email" class="required" required>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Логин</label>
                        <input type="text" name="login" class="required" required autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Телефон</label>
                        <input type="text" name="phone" placeholder="(099)999-99-99" class="phone required" required autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Адрес</label>
                        <input type="text" name="address" class="required" required autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Какие интересуют группы товаров</label>
                        <input type="text" name="group_products" autocomplete="off">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Комментарий</label>
                        <textarea name="message" cols="30" rows="2"></textarea>
                    </div>

                    <input type="hidden" name="sign_up" value="true">
                    <div class="medium-12 small-12 columns">
                        <button type="submit" class="button primary float-right">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<script src="/template/site/js/vendor/jquery.js"></script>
<script src="/template/site/js/vendor/what-input.js"></script>
<script src="/template/site/js/vendor/foundation.min.js"></script>
<script src="/template/site/js/jquery.mask.min.js"></script>
<script src="/template/admin/js/app.js"></script>
<script src="/template/site/js/parallax.min.js"></script>
<script src="/template/site/js/object.js"></script>
<script src="/template/site/js/main.js?v.1.5.6"></script>
<script src="http://mynameismatthieu.com/WOW/dist/wow.min.js"></script>

<script src="/template/site/js/html2canvas.js"></script>

<script>
    $(document).ready(function(){
        $('.phone').mask('(000) 000-00-00');
    });
</script>
<script>
    new WOW().init();
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89816712-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>