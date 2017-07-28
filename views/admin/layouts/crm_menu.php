
<?php if (Umbrella\app\AdminBase::checkDenied('crm.purchase', 'view')): ?>
    <li><a href="/adm/crm/purchase" class="<?= Umbrella\components\Url::IsActive('/crm/purchase', 'active')  ?>">Purchase</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.orders', 'view')): ?>
    <li><a href="/adm/crm/orders" class="<?= Umbrella\components\Url::IsActive('/crm/orders', 'active') ?>">Order</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.returns', 'view')): ?>
    <li><a href="/adm/crm/returns" class="<?= Umbrella\components\Url::IsActive('/crm/returns', 'active') ?>">Return</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.stocks', 'view')): ?>
    <li><a href="/adm/crm/stocks" class="<?= Umbrella\components\Url::IsActive('/crm/stocks', 'active') ?>">Stocks</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.disassembly', 'view')): ?>
    <li><a href="/adm/crm/disassembly" class="<?= Umbrella\components\Url::IsActive('/crm/disassembly', 'active') ?>">Disassembly</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.moto', 'view')): ?>
    <li><a href="/adm/crm/moto" class="<?= Umbrella\components\Url::IsActive('/crm/moto', 'active') ?>">Motorola</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.psr', 'view')): ?>
    <li><a href="/adm/crm/psr" class="<?= Umbrella\components\Url::IsActive('/crm/psr', 'active') ?>">PSR</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.backlog', 'view')): ?>
    <li><a href="/adm/crm/backlog" class="<?= Umbrella\components\Url::IsActive('/crm/backlog', 'active') ?>">Backlog Analysis Function</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.supply', 'view')): ?>
    <li><a href="/adm/crm/supply" class="<?= Umbrella\components\Url::IsActive('/crm/supply', 'active') ?>">Supply</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('crm.request', 'view')): ?>
    <li><a href="/adm/crm/request" class="<?= Umbrella\components\Url::IsActive('/crm/request', 'active') ?>">Request</a></li>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('crm.other.request', 'view')): ?>
    <li><a href="/adm/crm/other-request" class="<?= Umbrella\components\Url::IsActive('/crm/other-request', 'active') ?>">Lenovo Request</a></li>
<?php endif; ?>