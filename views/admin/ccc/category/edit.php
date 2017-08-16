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
                <div class="medium-6 medium-offset-3 small-12 columns">
                    <form method="post">
                        <input type="hidden" name="edit_category" value="true">

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Customer
                                    <select name="customer">
                                        <option value="electrolux" <?php if($categoryInfo['customer'] == 'electrolux') echo 'selected'?>>Electrolux</option>
                                        <option value="lenovo" <?php if($categoryInfo['customer'] == 'lenovo') echo 'selected'?>>Lenovo</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Название
                                    <input type="text" name="name" value="<?= $categoryInfo['name']?>" placeholder="Название" />
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Статус
                                    <select name="enabled">
                                        <option value="0" <?php if($categoryInfo['enabled'] == 0) echo 'selected'?>>Включена</option>
                                        <option value="1" <?php if($categoryInfo['enabled'] == 1) echo 'selected'?>>Выключена</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <?php if($categoryInfo['p_id'] != 0):?>
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Категория
                                        <select name="p_id">
                                            <?php if(is_array($listCustomer)):?>
                                                <?php foreach ($listCustomer as $customer):?>
                                                    <optgroup style="background: #525151; color: #fff" label="<?= ucfirst($customer['customer'])?>">
                                                        <?php $listCategory = Umbrella\models\ccc\KnowledgeCatalog::getAllCategoriesCustomerAdmin(0, $customer['customer'])?>
                                                        <?php if(is_array($listCategory)):?>
                                                            <?php foreach ($listCategory as $category):?>
                                                                <option style="background: #fff;" <?php if($category['id'] == $categoryInfo['p_id']) echo 'selected'?> value="<?= $category['id']?>"><?= $category['name']?></option>
                                                            <?php endforeach;?>
                                                        <?php endif;?>
                                                    </optgroup>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        <?php else:?>
                            <input type="hidden" name="p_id" value="<?= $categoryInfo['p_id']?>">
                        <?php endif;?>

                        <?php if($categoryInfo['p_id'] == 0):?>
                            <div class="medium-12 small-12 columns">
                                <div class="row">
                                    <div class="medium-12 small-12 columns">
                                        <input id="child" name="child" type="checkbox" <?php if($categoryInfo['child'] == 1) echo 'checked'?>><label for="child">Основной раздел(Имеет подкатегории)</label>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>

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
