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
                        <div class="row">

                            <?php if($user->isAdmin() || $user->isManager()):?>
                                <div class="medium-6 small-6 columns">
                                    <label style="color: #3C3C3C;">Who not sees</label>
                                    <div class="litographer-not-see" style="border: 1px solid #cacaca;">
                                        <div style="">
                                            <?php foreach ($userInGroup as $groups):?>
                                                <div class="parent-block">
                                                    <div  class="dark" style="padding-left: 10px; border: 1px solid #ddd">
                                                        <input class="select-group" type="checkbox"  id="group-<?= $groups['group_id']?>">
                                                        <label for="group-<?= $groups['group_id']?>"><?= $groups['group_name']?></label>
                                                    </div>
                                                    <div class="child-block show"  style="margin-left: 25px; display: none">
                                                        <?php foreach($groups['users'] as $userC):?>
                                                            <input class="children-input-group" type="checkbox" <?= (in_array($userC['id_user'], $listUserCloseView)) ? 'checked' : ''?> id="id-<?=$userC['id_user'] ?>" name="privilege[]" value="<?=$userC['id_user'] ?>">
                                                            <label style="color: #0a0a0a;"  class="check" for="id-<?=$userC['id_user'] ?>" ><?=$userC['name_partner'] ?></label><br>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="medium-6 small-6 columns">
                                    <label style="color: #3C3C3C;">Documents</label>
                                    <button type="button" data-open="open-upload-file" class="button primary">Attach file</button>
                                    <div style="max-height: 200px; overflow-y: scroll">
                                        <table class="umbrella-table">
                                            <thead>
                                            <tr>
                                                <th>Document</th>
                                                <th width="25"><i class="fa fa-download"></i></th>
                                                <th width="25"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(is_array($files)): ?>
                                                <?php foreach ($files as $file): ?>
                                                <tr>
                                                    <td><a href="<?= $file['file_path'] . $file['file_name'] ?>" download=""><?= $file['file_name_real'] ?></a></td>
                                                    <td class="text-center"><?= $file['count'] ?></td>
                                                    <td><button><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
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

<div class="reveal" id="open-upload-file" data-reveal>
    <form action="" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Upload file</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_document" class="button primary">Attach</label>
                                <input type="file" id="upload_document" class="show-for-sr" name="file">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?=  $article['id'] ?>">
                    <input type="hidden" name="upload_document" value="true">

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">

                            </div>
                            <div class="medium-6 small-12 columns">
                                <input type="submit" class="button primary" value="Upload File to Server">

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
