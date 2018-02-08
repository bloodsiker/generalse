<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">

        <h1 class="mb-5 mt-6">Career</h1>

        <div class="row mt-5 mb-5">
            <?php if(is_array($all_vacancy)): ?>
                <?php foreach ($all_vacancy as $vacancy): ?>
                    <div class="col-md-4 p-3">
                        <div class="card h-100 justify-content-lg-between">
                            <div class="card-block p-3">
                                <h4 class="card-title"><a href="/new/career/<?= $vacancy['slug'] ?>"><?= $vacancy['title'] ?></a></h4>

                                <ul class="list-group list-group-flush career-box-list">
                                    <li class="list-group-item justify-content-lg-between d-flex">
                                        <div class="text-danger w-100">Location:</div>
                                        <div><?= $vacancy['location'] ?></div>
                                    </li>
                                    <li class="list-group-item justify-content-lg-between d-flex">
                                        <div class="text-danger w-100">Department:</div>
                                        <div class="text-right"><?= $vacancy['department'] ?></div>
                                    </li>
                                    <li class="list-group-item justify-content-lg-between d-flex">
                                        <div class="text-danger w-100">Employment type:</div>
                                        <div class="text-right"><?= $vacancy['employment'] ?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-right">
                                <a href="/new/career/<?= $vacancy['slug'] ?>" class="btn btn-sm btn-red">read more</a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

