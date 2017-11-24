<?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>

    <ul class="tabs" data-tabs id="add-tabs">
        <li class="tabs-title is-active"><a href="#video-manuals" aria-selected="true">Video Manuals</a></li>
        <li class="tabs-title"><a href="#tips">Tips</a></li>
        <li class="tabs-title"><a href="#rules">Rules</a></li>
    </ul>
    <div class="tabs-content" data-tabs-content="add-tabs">
    <div class="tabs-panel is-active" id="video-manuals">
        <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
            <div class="row align-bottom ">
                <div class="medium-12 small-12 columns" >
                    <label>Who not sees</label>
                    <div class="litographer-not-see">
                        <?php foreach ($listUsers as $partner):?>
                            <div>
                                <input type="checkbox" id="v-user-<?=$partner['id_user']?>" name="privilege[]" value="<?=$partner['id_user']?>">
                                <label for="v-user-<?=$partner['id_user']?>"><?=$partner['name_partner']?></label>
                            </div>
                        <?php endforeach;?>
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
    <div class="tabs-panel" id="tips">
        <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
            <div class="row align-bottom ">
                <div class="medium-12 small-12 columns">
                    <label>Who not sees</label>
                    <div class="litographer-not-see">
                        <?php foreach ($listUsers as $partner):?>
                            <div>
                                <input type="checkbox" id="t-user-<?=$partner['id_user']?>" name="privilege[]" value="<?=$partner['id_user']?>">
                                <label for="t-user-<?=$partner['id_user']?>"><?=$partner['name_partner']?></label>
                            </div>
                        <?php endforeach;?>
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
                                    <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_tips"
                                              name="content"></textarea>
                </div>
                <input type="hidden" name="add_tips" value="true">
                <div class="medium-12 small-12 columns">
                    <button class="button primary">add</button>
                </div>
            </div>
        </form>
    </div>
    <div class="tabs-panel" id="rules">
        <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
            <div class="row align-bottom ">
                <div class="medium-12 small-12 columns">
                    <label>Who not sees</label>
                    <div class="litographer-not-see">
                        <?php foreach ($listUsers as $partner):?>
                            <div>
                                <input type="checkbox" id="r-user-<?=$partner['id_user']?>" name="privilege[]" value="<?=$partner['id_user']?>">
                                <label for="r-user-<?=$partner['id_user']?>"><?=$partner['name_partner']?></label>
                            </div>
                        <?php endforeach;?>
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
                                    <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_rules"
                                              name="content"></textarea>
                </div>
                <div class="medium-12 small-12 columns">
                    <button name="add_rules" class="button primary">add</button>
                </div>
            </div>
        </form>
    </div>
    </div>

<?php elseif($user->role == 'partner'):?>

    <ul class="tabs" data-tabs id="add-tabs">
        <li class="tabs-title is-active"><a href="#tips" aria-selected="true">Tips</a></li>
        <li class="tabs-title"><a href="#rules">Rules</a></li>
    </ul>
    <div class="tabs-content" data-tabs-content="add-tabs">
        <div class="tabs-panel is-active" id="tips">
            <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
                <div class="row align-bottom ">
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
                                    <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_tips"
                                              name="content"></textarea>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <button name="add_tips" class="button primary">add</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabs-panel" id="rules">
            <form action="/adm/lithographer/forms" method="post" enctype="multipart/form-data">
                <div class="row align-bottom ">
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
                                    <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_rules"
                                              name="content"></textarea>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <button name="add_rules" class="button primary">add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php endif;?>
