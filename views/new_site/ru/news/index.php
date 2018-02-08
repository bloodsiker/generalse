<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">

        <h1 class="mb-5 mt-6">Новости General Services</h1>

        <?php if(is_array($all_news)): ?>
            <?php foreach ($all_news as $new): ?>
                <section>
                    <div class="row ">
                        <div class="col-md-4">
                            <img class="mw-100" src="<?= $new['image'] ?>" alt="<?= $new['title'] ?>">
                        </div>
                        <div class="col-md-8">
                            <h2><a href="/ru/new/news/<?= $new['slug'] ?>"><?= $new['title'] ?></a></h2>
                            <div class="text-muted mb-2"><?= $new['created_at'] ?></div>
                            <p><?= $new['description'] ?></p>
                            <a href="/ru/new/news/<?= $new['slug'] ?>" class="btn btn-red text-right">Читать далее...</a>
                            <div class="social-menu pull-right">
                                <ul class="nav">

                                    <li class="menu-item">
                                        <a target="_blank" href="#">
                                            <img src="/template/new_site/img/icon-facebook.svg" width="30px" alt="">
                                        </a>
                                    </li>

                                    <li class="menu-item">
                                        <a target="_blank" href="#">
                                            <img src="/template/new_site/img/icon-linkedin.svg" width="30px" alt="">
                                        </a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    </div>
                    <hr class="mt-5 mb-5">
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

