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
                <div class="medium-12 small-12 top-gray columns" style="margin-bottom: 15px">
                    <button data-open="add-category" class="button no-margin small"><i class="fi-plus"></i> Добавить статью</button>
                </div>
                <div class="medium-12 small-12 top-gray columns">
                    <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Published</th>
                            <th>Date create</th>
                            <th>Date update</th>
                            <th width="50"></th>
                            <th width="50"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listArticles)):?>
                            <?php foreach ($listArticles as $article):?>
                                <tr>
                                    <td><?= $article['id']?></td>
                                    <td><?= ucfirst($article['customer'])?></td>
                                    <td><?= $article['name']?></td>
                                    <td><?= $article['title']?></td>
                                    <td><?= $article['name_partner']?></td>
                                    <td><?= $article['published'] == 1 ? 'Опубликована' : 'Не обуликована'?></td>
                                    <td><?= $article['created_at']?></td>
                                    <td><?= $article['updated_at']?></td>
                                    <td>
                                        <a href="/adm/ccc/tree_knowledge/article/edit/<?= $article['id']?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                                    </td>
                                    <td>
                                        <a href="/adm/ccc/tree_knowledge/article/delete/<?= $article['id']?>" class="button no-margin small" onclick="return confirm('Вы уверены что хотите удалить статью?') ? true : false;">
                                            <i class="fi-x"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="reveal small" id="add-category" data-reveal>
    <form action="" method="post" class="form" >
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Добавить статью</h3>
            </div>


            <div class="medium-12 small-12 columns">
                <label>Раздел</label>
                <select name="id_category" class="required" required>
                    <?= $renderOptions?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Title</label>
                        <input type="text" name="title" autocomplete="off" class="required" required>
                    </div>
                </div>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Публикация</label>
                <select name="published" class="required" required>
                    <option value="1">Опубликовать</option>
                    <option value="0">Не публиковать</option>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Description</label>
                <textarea style="min-height: 70px; background-color: #fff; color: #000;"
                          name="description"></textarea>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Content</label>
                <textarea style="min-height: 200px; background-color: #fff; color: #000;" id="ck_rules"
                          name="text"></textarea>
            </div>

            <input type="hidden" name="add-article" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Добавить</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
