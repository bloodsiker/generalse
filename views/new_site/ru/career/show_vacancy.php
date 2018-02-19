<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">

        <h1 class="mb-5 mt-6"><?= $info_vacancy['title'] ?></h1>

        <div class="row mt-5 mb-5">
            <div class="col-md-4 p-3">
                <div class="card" style="width: 20rem;">
                    <div class="card-block p-3">
                        <ul class="list-group list-group-flush career-box-list">
                            <li class="list-group-item justify-content-lg-between d-flex">
                                <div class="text-danger w-100">Стана:</div>
                                <div><?= $info_vacancy['location'] ?></div>
                            </li>
                            <li class="list-group-item justify-content-lg-between d-flex">
                                <div class="text-danger w-100">Отдел:</div>
                                <div class="text-right"><?= $info_vacancy['department'] ?></div>
                            </li>
                            <li class="list-group-item justify-content-lg-between d-flex">
                                <div class="text-danger w-100">Тип занятости:</div>
                                <div class="text-right"><?= $info_vacancy['employment'] ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <?= $info_vacancy['text'] ?>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

