<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Repairs</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/engineers_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-3 small-12 columns">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content">
            <div class="row">
                <div class="medium-6 small-12 columns">
                    <div id="repair_diagram"></div>
                </div>
                <div class="medium-6 small-12 columns">
                    <h2 id="repair_result"></h2>
                </div>
                <div class="medium-12 small-12 columns" id="show_repairs">

                </div>
            </div>
        </div>

    </div>
</div>
<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
