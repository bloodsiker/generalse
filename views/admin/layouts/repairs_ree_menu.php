
<?php if (Umbrella\app\AdminBase::checkDenied('adm.repairs_ree.crm', 'view')): ?>
    <li><a href="/adm/repairs_ree/crm" class="<?= Umbrella\components\Url::IsActive('/adm/repairs_ree/crm', 'active') ?>">CRM</a></li>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('adm.repairs_ree.mds', 'view')): ?>
    <li><a href="/adm/repairs_ree/mds" class="<?= Umbrella\components\Url::IsActive('/adm/repairs_ree/mds', 'active') ?>">MDS</a></li>
<?php endif; ?>
