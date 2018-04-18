<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">

        <h1 class="mb-5 mt-6">News of General Services</h1>

        <?php if(is_array($all_news)): ?>
            <?php foreach ($all_news as $new): ?>
                <section>
                    <div class="row ">
                        <div class="col-md-4">
                            <a href="/news/<?= $new['slug'] ?>">
                                <img class="mw-100" src="<?= $new['image'] ?>" alt="<?= $new['title'] ?>">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <h2><a href="/news/<?= $new['slug'] ?>"><?= $new['title'] ?></a></h2>
                            <div class="text-muted mb-2"><?= $new['created_at'] ?></div>
                            <p><?= $new['description'] ?></p>
                            <a href="/news/<?= $new['slug'] ?>" class="btn btn-red text-right">read more</a>
                            <div class="social-menu pull-right">

                            </div>
                        </div>
                    </div>
                    <hr class="mt-5 mb-5">
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

