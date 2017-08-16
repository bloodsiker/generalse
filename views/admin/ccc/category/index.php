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
                    <button data-open="add-category" class="button no-margin small"><i class="fi-plus"></i> Добавить раздел</button>
                    <button data-open="add-sub-category" class="button no-margin small"><i class="fi-plus"></i> Добавить подраздел</button>
                </div>
                <div class="medium-5 small-5 top-gray columns">
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th width="50"></th>
                            <th width="50"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listCategory)):?>
                            <?php foreach ($listCategory as $category):?>
                                <tr style="<?= ($category['id'] == $id_category) ? 'background: #66bb6a' : ''?>">
                                    <td><?= ucfirst($category['customer'])?></td>
                                    <td><?= $category['name']?></td>
                                    <td>
                                        <?= $category['enabled'] == 0 ? 'Включена' : 'Отключена'?>
                                    </td>
                                    <td>
                                        <a href="/adm/ccc/tree_knowledge/category/edit/<?= $category['id']?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                                    </td>
                                    <td>
                                        <a href="/adm/ccc/tree_knowledge/category/<?= $category['id']?>" class="button no-margin small"><i class="fi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>

                <?php if(isset($listSubCategory)):?>
                    <div class="medium-5 small-5 top-gray columns">
                        <table border="1" cellspacing="0" cellpadding="5">
                            <thead>
                            <tr>
                                <th>Customer</th>
                                <th>SubCategory</th>
                                <th>Status</th>
                                <th width="50"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($listSubCategory)):?>
                                <?php foreach ($listSubCategory as $categorySub):?>
                                    <tr style="">
                                        <td><?= ucfirst($categorySub['customer'])?></td>
                                        <td><?= $categorySub['name']?></td>
                                        <td>
                                            <?= $categorySub['enabled'] == 0 ? 'Включена' : 'Отключена'?>
                                        </td>
                                        <td>
                                            <a href="/adm/ccc/tree_knowledge/category/edit/<?= $categorySub['id']?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                <?php endif;?>
            </div>
        </div>

    </div>
</div>

<div class="reveal" id="add-category" data-reveal>
    <form action="" method="post" class="form" >
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Добавить раздел</h3>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Customer</label>
                <select name="customer" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($listCustomer as $customer):?>
                        <option value="<?= $customer['customer']?>"><?= ucfirst($customer['customer'])?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Название</label>
                        <input type="text" name="name" autocomplete="off" class="required" required>
                    </div>
                </div>
            </div>

            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <input id="child" name="child" type="checkbox"><label for="child">Основной раздел(Имеет подкатегории)</label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="p_id" value="0">
            <input type="hidden" name="add-category" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Добавить</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="add-sub-category" data-reveal>
    <form action="" method="post" class="form" >
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Добавить подраздел</h3>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Раздел</label>
                <select name="p_id" class="required" required>
                    <?php if(is_array($listCustomer)):?>
                        <?php foreach ($listCustomer as $customer):?>
                            <optgroup style="background: #525151; color: #fff" label="<?= ucfirst($customer['customer'])?>">
                                <?php $listCategory = Umbrella\models\ccc\KnowledgeCatalog::getAllCategoriesCustomerAdmin(0, $customer['customer'])?>
                                <?php if(is_array($listCategory)):?>
                                    <?php foreach ($listCategory as $category):?>
                                        <option style="background: #fff;" value="<?= $category['id']?>"><?= $category['name']?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </optgroup>
                        <?php endforeach;?>
                    <?php endif;?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Name</label>
                        <input type="text" name="name" autocomplete="off" class="required" required>
                    </div>
                </div>
            </div>
            <input type="hidden" name="add-sub-category" value="true">
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
