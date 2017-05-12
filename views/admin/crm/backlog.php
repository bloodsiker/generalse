<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Backlog Analysis Function</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-3 small-12 columns">
                                <button data-open="add-backlog-modal" class="button primary tool"><i class="fi-plus"></i>
                                    Check Backlog
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content checkout">
            <div class="row">

            </div>
        </div>

    </div>
</div>
<div class="reveal" id="add-backlog-modal" data-reveal>
    <form action="/adm/crm/export/backlog" id="add-backlog-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Check Backlog</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="medium-12 small-12 columns">
                                    <label><i class="fi-list"></i> Partner
                                        <select name="id_partner" class="required" required>
                                            <option value=""></option>
                                            <?php if(is_array($partnerList)):?>
                                                <?php foreach($partnerList as $partner):?>
                                                    <option value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_file_form" class="button primary">Attach</label>
                                <input type="file" id="upload_file_form" class="show-for-sr" name="excel_file" required>
                            </div>

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                        href="/upload/attach_backlog/Backlog_Analysis_Function.xlsx" style="color: #2ba6cb"
                                        download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="check_backlog" value="true">
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Check</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
