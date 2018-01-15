
<?php if (Umbrella\app\AdminBase::checkDenied('adm.engineers.dashboard', 'view')): ?>
    <li><a href="/adm/engineers/dashboard" class="<?= Umbrella\components\Url::IsActive('/adm/engineers/dashboard', 'active') ?>">Dashboard</a></li>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('adm.engineers.repairs', 'view')): ?>
    <li><a href="/adm/engineers/repairs" class="<?= Umbrella\components\Url::IsActive('/adm/engineers/repairs', 'active') ?>">Repairs</a></li>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('adm.engineers.returns', 'view')): ?>
    <li><a href="/adm/engineers/returns" class="<?= Umbrella\components\Url::IsActive('/adm/engineers/returns', 'active') ?>">Returns</a></li>
<?php endif; ?>

<?php if (Umbrella\app\AdminBase::checkDenied('adm.engineers.disassembly', 'view')): ?>
    <li><a href="/adm/engineers/disassembly" class="<?= Umbrella\components\Url::IsActive('/engineers/disassembly', 'active') ?>">Disassembly</a></li>
<?php endif; ?>
