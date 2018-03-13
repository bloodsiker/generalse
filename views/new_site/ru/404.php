<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">
        <img class="mw-100 d-none" src="/template/new_site/img/black-and-white-city-man-people.jpg" alt="">
        <div class="row pt-5 mb-lg-5 align-items-center">
            <div class="col-md-6">
                <div class="p-3">
                    <h4 class="alert-heading">Ошибка!</h4>
                    <p>Страница не найдена. Вы можете связаться с нами по адресу <a href="mailto: info@generalse.com">info@generalse.com</a> или воспользоваться формой.</p>
                    <form action="/ru/404/send" data-form="send" method="post" class="form mb-3">
                        <div class="form-group">
                            <label for="fio">Имя</label>
                            <input type="text" name="fio" class="form-control required" id="fio" placeholder="Имя">
                        </div>
                        <div class="form-group">
                            <label for="email">Адрес электронной почты</label>
                            <input type="email" name="email" class="form-control required" id="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="message">Сообщение</label>
                            <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                        </div>
                        <div class="text-right">
                            <input type="hidden" name="lang" value="ru">
                            <button type="submit" class="btn btn-red">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <img src="/template/new_site/img/404-error.png" alt="">
                </div>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

