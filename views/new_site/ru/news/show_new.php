<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>


    <main class="container">
        <section>
            <h1><?= $info_news['title'] ?></h1>
            <div class="text-muted mb-2"><?= $info_news['created_at'] ?></div>
            <div class="new-content">
                <img style="width: 400px;" class="mb-4 ml-4" align="right" src="<?= $info_news['image'] ?>" alt="<?= $info_news['title'] ?>">
                <?= $info_news['text'] ?>
            </div>

            <div class="social-menu pull-right">

                <ul class="nav">

                    <li class="menu-item">
                        <a target="_blank" href="#">
                            <img src="../img/icon-facebook.svg" width="30px" alt="">
                        </a>
                    </li>
                    <li class="menu-item">
                        <a target="_blank" href="#">
                            <img src="../img/icon-linkedin.svg" width="30px" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </section>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

