<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Lithographer</h1>
            </div>
            <div class="medium-12 small-12 top-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-3 small-12 columns">
                        <button class="button primary tool" data-open="add-content"><i class="fi-plus"></i> Add</button>
                        <a href="/adm/lithographer/list" class="button primary tool active-req"><i class="fi-pencil"></i> Edit</a>
                    </div>
                    <div class="medium-1 medium-offset-8 small-12 columns">
                        <a href="/adm/lithographer/list" class="button primary tool active-req"><i class="fi-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="medium-2 left-bar header-content small-12 columns">
        <ul class="menu">

            <?php require_once 'sidebar.php'; ?>

        </ul>
    </div>
    <div class="medium-10 small-12 columns container-litographer">
        <div class="row content-litographer">
            <div class="medium-12 small-12 columns">
                <div class="callout">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row align-bottom ">

                            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                <div class="medium-12 small-12 columns">
                                    <label style="color: #3C3C3C;">Who not sees</label>
                                    <select style="border: 1px solid #cacaca; color: #0a0a0a;" size="6" multiple name="privilege[]">
                                        <option value=""></option>
                                        <?php foreach ($listUsers as $partner):?>
                                            <option <?= (in_array($partner['id_user'], $listUserCloseView)) ? 'selected' : ''?> value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="medium-12 small-12 columns">
                                    <label style="color: #3C3C3C;">Published</label>
                                    <select style="border: 1px solid #cacaca; color: #0a0a0a;" name="published" style="color: #3C3C3C;">
                                        <option value="1" <?php if($article['published'] == 1) echo 'selected'?>>Published</option>
                                        <option value="0" <?php if($article['published'] == 0) echo 'selected'?>>Unpublished</option>
                                    </select>
                                </div>
                            <?php endif;?>

                            <div class="medium-12 small-12 columns">
                                <label style="color: #3C3C3C;">Title</label>
                                <input  type="text" style="color: #3C3C3C; border: 1px solid #cacaca;" name="title" value="<?=$article['title']?>" class="required" required>
                            </div>
                        <?php if($article['type_row'] != 'video'):?>
                            <div class="medium-12 small-12 columns">
                                <label style="color: #3C3C3C;">Description</label>
                            <textarea style="min-height: 100px; background-color: #fff; color: #000; border: 1px solid #cacaca"
                                      name="description"><?=$article['description']?></textarea>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label style="color: #3C3C3C;">Content</label>
                            <textarea style="min-height: 300px; background-color: #fff; color: #000;" id="edit"
                                  name="content"><?=$article['text']?></textarea>
                            </div>
                        <?php endif;?>
                            <div class="medium-12 small-12 columns">
                                <button name="edit_article" class="button primary">edit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="reveal small" id="video-modal" data-reveal data-close-on-click="true" data-animation-in="fade-in"
     data-animation-out="fade-out"></div>

<div class="reveal small" id="add-content" data-reveal data-close-on-click="true">
    <div class="row align-center">
        <div class="medium-12 text-center small-12 columns">
            <h1>Add content</h1>
        </div>
        <div class="large-12 medium-12 small-12 columns">

            <?php require_once 'forms.php'; ?>

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
