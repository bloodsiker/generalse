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

            <div class="social-news pull-right d-none">
                <div class="pull-left mr-2">Поделиться</div>
            </div>
        </section>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

