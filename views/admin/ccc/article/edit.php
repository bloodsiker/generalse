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
                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.category', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/category" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/category', 'active-req') ?>">Разделы</a>
                                <?php endif; ?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('ccc.tree_knowledge.article', 'view')): ?>
                                    <a href="/adm/ccc/tree_knowledge/articles" class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/articles', 'active-req') ?>">Статьи</a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-content checkout">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <form method="post">

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Раздел</label>
                                <select name="id_category" class="required" required>
                                    <?= $renderOptions?>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Title
                                    <input type="text" name="title" value="<?= $article['title']?>" class="required" required />
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Статус
                                    <select name="published" class="required" required>
                                        <option value="1" <?php if($article['published'] == 1) echo 'selected'?>>Опубликована</option>
                                        <option value="0" <?php if($article['published'] == 0) echo 'selected'?>>Не опубликована</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Description</label>
                                <textarea style="min-height: 70px; background-color: #fff; color: #000;"
                                          name="description"><?= $article['description']?></textarea>
                            </div>
                        </div>


                        <div class="row">
                            <div class="large-12 columns">
                                <label>Content</label>
                                <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_rules"
                                          name="text"><?= $article['text']?></textarea>
                            </div>
                        </div>


                        <input type="hidden" name="edit-article" value="true">
                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" class="button small float-right" value="Сохранить">
                                <button onclick="window.history.go(-1); return false;" class="button small info"> Назад</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
