<div class="expanded button-group">
    <a href="/adm/ccc/tree_knowledge/customer-electrolux"
       class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/customer-electrolux', 'active-req') ?>">Electrolux</a>

    <a href="/adm/ccc/tree_knowledge/customer-lenovo"
       class="button primary tool <?= Umbrella\components\Url::IsActive('/tree_knowledge/customer-lenovo', 'active-req') ?>">Lenovo</a>
</div>

<?php $tree = Umbrella\models\ccc\KnowledgeCatalog::form_tree(Umbrella\models\ccc\KnowledgeCatalog::getAllCategories($customer))?>
<?= Umbrella\models\ccc\KnowledgeCatalog::build_tree($tree, 0, $id_category = false)?>