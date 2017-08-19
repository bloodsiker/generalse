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
                            <div class="medium-9 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.category', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/category" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/category', 'active-req') ?>">Разделы</a>
                                <?php endif; ?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.article', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/articles" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/articles', 'active-req') ?>">Статьи</a>
                                <?php endif; ?>
                            </div>

                            <div class="medium-3 small-12 columns">
                                <form action="/adm/ccc/tree_knowledge/customer-<?= $customer?>/s/" method="get" class="form">
                                    <input type="text" class="search-input" placeholder="Search..." name="search">
                                    <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-content checkout">
            <div class="row">
                <div class="medium-3 small-3 ccc-sidebar">
                    <h2>Разделы</h2>

                    <?php require_once ROOT . '/views/admin/ccc/include/sidebar.php'; ?>

                </div>
                <div class="medium-9 small-9 top-gray columns">
                    <h2 class="ccc-title">Результат поиска по запросу: <?= (isset($_GET['search'])) ? $_GET['search'] : null?></h2>
                    <?php if(is_array($listSearch)):?>
                        <?php foreach ($listSearch as $article):?>
                            <div class="callout">
                                <?php if(!empty($article['updated_at'])):?>
                                    <?php if((Umbrella\components\Functions::calcDiffSec($article['updated_at']) < 172800)):?>
                                        <h5 class="article_updated_at">Статья обновлена! <?= $article['updated_at']?></h5>
                                    <?php endif;?>
                                <?php endif;?>
                                <h4><strong><?= $article['title']?></strong></h4>
                                <a href="/adm/ccc/tree_knowledge/customer-<?= $article['customer']?>/<?= $article['slug']?>"><?= $article['name']?></a>
                                <br>
                                <?= $article['description']?>
                                <br>
                                <br>
                                <a href="/adm/ccc/tree_knowledge/customer-<?= $article['customer']?>/<?= $article['slug']?>/article-<?= $article['id']?>">Читать дальше...</a>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>
        </div>

        <?php require_once ROOT . '/views/admin/ccc/include/last_view_article.php'; ?>

    </div>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
