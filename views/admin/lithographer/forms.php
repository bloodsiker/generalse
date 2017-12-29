<?php if($user->isAdmin() || $user->isManager()):?>

    <ul class="tabs" data-tabs id="add-tabs">
        <li class="tabs-title is-active"><a href="#video-manuals" aria-selected="true">Video Manuals</a></li>
        <li class="tabs-title"><a href="#articles">Articles</a></li>
    </ul>
    <div class="tabs-content" data-tabs-content="add-tabs">
        <div class="tabs-panel is-active" id="video-manuals">
            <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
                <div class="row align-bottom ">
                    <div class="medium-12 small-12 columns" >
                        <label>Who not sees</label>
                        <div class="litographer-not-see">
                            <div style="height: 400px; overflow-y: scroll">
                                <?php foreach ($userInGroup as $groups):?>
                                    <div class="parent-block">
                                        <div class="dark"  style="padding-left: 10px; border: 1px solid #ddd">
                                            <input class="select-group" type="checkbox"  id="group-<?= $groups['group_id']?>">
                                            <label for="group-<?= $groups['group_id']?>"><?= $groups['group_name']?></label>
                                        </div>
                                        <div class="child-block show"  style="margin-left: 25px; display: none">
                                            <?php foreach($groups['users'] as $userC):?>
                                                <input class="children-input-group" type="checkbox" id="id-<?=$userC['id_user'] ?>" name="privilege[]" value="<?=$userC['id_user'] ?>">
                                                <label  class="check" for="id-<?=$userC['id_user'] ?>" ><?=$userC['name_partner'] ?></label><br>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Published</label>
                        <select name="published">
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </select>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label>Title</label>
                        <input type="text" name="title" class="required" required>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label for="exampleFileUpload" class="button primary">Attach</label>
                        <input type="file" id="exampleFileUpload" class="show-for-sr" name="upload_video" required>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <button name="add_video" class="button primary">add</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabs-panel" id="articles">
            <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
                <div class="row align-bottom ">
                    <div class="medium-12 small-12 columns">
                        <label>Category</label>
                        <select name="type_row">
                            <option value="tips">Tips</option>
                            <option value="rules">Rules</option>
                            <option value="documents">Documents</option>
                        </select>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Who not sees</label>
                        <div class="litographer-not-see">
                            <div style="height: 400px; overflow-y: scroll">
                                <?php foreach ($userInGroup as $groups):?>
                                    <div class="parent-block">
                                        <div  class="dark" style="padding-left: 10px; border: 1px solid #ddd">
                                            <input class="select-group" type="checkbox"  id="group-<?= $groups['group_id']?>">
                                            <label for="group-<?= $groups['group_id']?>"><?= $groups['group_name']?></label>
                                        </div>
                                        <div class="child-block show"  style="margin-left: 25px; display: none">
                                            <?php foreach($groups['users'] as $userC):?>
                                                <input class="children-input-group" type="checkbox" id="id-<?=$userC['id_user'] ?>" name="privilege[]" value="<?=$userC['id_user'] ?>">
                                                <label  class="check" for="id-<?=$userC['id_user'] ?>" ><?=$userC['name_partner'] ?></label><br>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Published</label>
                        <select name="published">
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </select>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Title</label>
                        <input type="text" name="title" class="required" required>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Description</label>
                                        <textarea style="min-height: 100px; background-color: #fff; color: #000;"
                                                  name="description"></textarea>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Content</label>
                                        <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_article"
                                                  name="content"></textarea>
                    </div>
                    <input type="hidden" name="add_new" value="true">
                    <div class="medium-12 small-12 columns">
                        <button class="button primary">add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php elseif($user->isPartner()):?>

    <ul class="tabs" data-tabs id="add-tabs">
        <li class="tabs-title is-active"><a href="#tips" aria-selected="true">Articles</a></li>
    </ul>
    <div class="tabs-content" data-tabs-content="add-tabs">
        <div class="tabs-panel is-active" id="tips">
            <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
                <div class="row align-bottom ">
                    <div class="medium-12 small-12 columns">
                        <label>Category</label>
                        <select name="type_row">
                            <option value="tips">Tips</option>
                            <option value="rules">Rules</option>
                            <option value="documents">Documents</option>
                        </select>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Title</label>
                        <input type="text" name="title" class="required" required>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Description</label>
                                    <textarea style="min-height: 100px; background-color: #fff; color: #000;"
                                              name="description"></textarea>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Content</label>
                                    <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_article"
                                              name="content"></textarea>
                    </div>
                    <input type="hidden" name="add_new" value="true">
                    <div class="medium-12 small-12 columns">
                        <button class="button primary">add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php endif;?>
