<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Customer Care Center (CCC)</h1>
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
                                <button class="button primary tool" id="add-create-button"><i class="fi-plus"></i>
                                    Create
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
