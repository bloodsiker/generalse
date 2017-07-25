
<?php if (AdminBase::checkDenied('crm.purchase', 'view')): ?>
    <li><a href="/adm/crm/purchase" class="<?= Url::IsActive('/crm/purchase', 'active')  ?>">Purchase</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.orders', 'view')): ?>
    <li><a href="/adm/crm/orders" class="<?= Url::IsActive('/crm/orders', 'active') ?>">Order</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.returns', 'view')): ?>
    <li><a href="/adm/crm/returns" class="<?= Url::IsActive('/crm/returns', 'active') ?>">Return</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.stocks', 'view')): ?>
    <li><a href="/adm/crm/stocks" class="<?= Url::IsActive('/crm/stocks', 'active') ?>">Stocks</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.disassembly', 'view')): ?>
    <li><a href="/adm/crm/disassembly" class="<?= Url::IsActive('/crm/disassembly', 'active') ?>">Disassembly</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.moto', 'view')): ?>
    <li><a href="/adm/crm/moto" class="<?= Url::IsActive('/crm/moto', 'active') ?>">Motorola</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.psr', 'view')): ?>
    <li><a href="/adm/crm/psr" class="<?= Url::IsActive('/crm/psr', 'active') ?>">PSR</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.backlog', 'view')): ?>
    <li><a href="/adm/crm/backlog" class="<?= Url::IsActive('/crm/backlog', 'active') ?>">Backlog Analysis Function</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.supply', 'view')): ?>
    <li><a href="/adm/crm/supply" class="<?= Url::IsActive('/crm/supply', 'active') ?>">Supply</a></li>
<?php endif; ?>


<?php if (AdminBase::checkDenied('crm.request', 'view')): ?>
    <li><a href="/adm/crm/request" class="<?= Url::IsActive('/crm/request', 'active') ?>">Request</a></li>
<?php endif; ?>

<?php if (AdminBase::checkDenied('crm.other.request', 'view')): ?>
    <li><a href="/adm/crm/other-request" class="<?= Url::IsActive('/crm/other-request', 'active') ?>">Lenovo Request</a></li>
<?php endif; ?>