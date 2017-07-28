
<?php if (AdminBase::checkDenied('ccc.tree_knowledge', 'view')): ?>
    <li><a href="/adm/ccc/tree_knowledge" class="<?= Url::IsActive('/ccc/tree_knowledge', 'active')  ?>">Древо знаний</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('ccc.kpi_ccc', 'view')): ?>
    <li><a href="/adm/ccc/kpi" class="<?= Url::IsActive('/ccc/kpi', 'active') ?>">KPI CCC</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('ccc.crm', 'view')): ?>
    <li><a href="/adm/ccc/crm" class="<?= Url::IsActive('/ccc/crm', 'active') ?>">CRM</a></li>
<?php endif; ?>