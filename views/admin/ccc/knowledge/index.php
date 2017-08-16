<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Древо знаний</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/ccc_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-3 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.category', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/category" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/category', 'active-req') ?>">Разделы</a>
                                <?php endif; ?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.article', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/articles" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/articles', 'active-req') ?>">Статьи</a>
                                <?php endif; ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-content checkout">
            <div class="row">
                <div class="medium-3 small-3 ccc-sidebar columns">
                    <h2>Разделы</h2>

                    <?php require_once ROOT . '/views/admin/ccc/include/sidebar.php'; ?>

                </div>
                <div class="medium-9 small-9 top-gray columns">

                </div>
            </div>
        </div>

        <?php require_once ROOT . '/views/admin/ccc/include/last_view_article.php'; ?>

    </div>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
