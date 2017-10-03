
<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.ua', 'view')): ?>
    <li><a href="/adm/psr/ua" class="<?= Umbrella\components\Url::IsActive('/adm/psr/ua', 'active')  ?>">PSR UA</a></li>
<?php endif; ?>

