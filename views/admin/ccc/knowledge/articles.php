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
                <div class="medium-3 small-3 ccc-sidebar">
                    <h2>Разделы</h2>

                    <?php $tree = Umbrella\models\ccc\KnowledgeCatalog::form_tree(Umbrella\models\ccc\KnowledgeCatalog::getAllCategories())?>
                    <?= Umbrella\models\ccc\KnowledgeCatalog::build_tree($tree, 0, $id_category)?>
                </div>
                <div class="medium-9 small-9 top-gray columns">
                    <h2>Холодильники</h2>
                    <div class="callout">
                        <h4><strong>FOUNDATION FOR EMAILS</strong></h4>
                        <p>We know building HTML emails is hard, especially responsive emails. That's why we created Foundation for Emails. Get away from complex table markup and inconsistent results. Use Foundation for Emails to spend less time coding emails, and more time
                            on other things, like building amazing products.</p>

                        <button data-toggle="read-more-content" href="#">SHOW MORE <i class="fa fa-plus"></i></button>
                        <div class="read-more-content" id="read-more-content" data-toggler data-animate="hinge-in-from-top slide-out-right">
                            <h5>Spend Less Time Coding, Testing, and Preparing:</h5>
                            <ul>
                                <li>Responsive Grid for Any Layout</li>
                                <li>Common UI Patterns to Build Faster</li>
                                <li>Make stylish emails fast with Sass</li>
                                <li>Inky: A New Templating Language</li>
                                <li>The ZURB Email Stack will make you an email pro</li>
                                <li>Emails that work in all of the major clients, even Outlook</li>
                                <li>Inlining CSS <strike>is</strike> was a pain</li>
                            </ul>
                        </div>
                    </div>

                    <div class="callout">
                        <h4><strong>FOUNDATION FOR EMAILS</strong></h4>
                        <p>We know building HTML emails is hard, especially responsive emails. That's why we created Foundation for Emails. Get away from complex table markup and inconsistent results. Use Foundation for Emails to spend less time coding emails, and more time
                            on other things, like building amazing products.</p>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
