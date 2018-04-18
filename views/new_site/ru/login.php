<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">
        <div class="row pt-5 mb-lg-5 justify-content-md-center">
            <div class="col-md-4 mt-4">
                <div class="text-center mb-2 block-error">
                    <?= \Josantonius\Session\Session::pull('error') ?>
                </div>
                <form action="/auth" method="post" style="border: 1px solid #000; padding: 20px">
                    <div class="text-center mb-3"><strong>Войти в Umbrella</strong></div>
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" name="login" id="login">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group clearfix">
                        <input type="hidden" name="lang" value="ru">
                        <input type="hidden" name="action" value="post_login">
                        <button type="submit" class="btn btn-red pull-right">Войти</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

