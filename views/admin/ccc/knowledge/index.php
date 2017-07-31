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
                                <a href="" class="button primary tool">Разделы</a>
                                <a href="" class="button primary tool">Статьи</a>
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

                    <?php $tree = Umbrella\models\ccc\KnowledgeCatalog::form_tree(Umbrella\models\ccc\KnowledgeCatalog::getAllCategories())?>
                    <?= Umbrella\models\ccc\KnowledgeCatalog::build_tree($tree, 0)?>
                </div>
                <div class="medium-9 small-9 top-gray columns">
                    <h2>Древо знаний</h2>
                </div>
            </div>
        </div>

    </div>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
