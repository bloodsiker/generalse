<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">
        <div class="row pt-5 mb-lg-5 justify-content-md-center">
            <div class="col-md-4 mt-3">
                <div class="text-center mb-2 block-error">
                    <?= \Josantonius\Session\Session::pull('error') ?>
                </div>
                <form action="/auth" method="post" style="border: 1px solid #000; padding: 20px">
                    <div class="text-center mb-4"><strong>Login to Umbrella Project</strong></div>
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" name="login" id="login">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group clearfix">
                        <input type="hidden" name="lang" value="en">
                        <input type="hidden" name="action" value="post_login">
                        <button type="submit" class="btn btn-red pull-right">Login</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

