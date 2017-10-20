
<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.ua', 'view')): ?>
    <li><a href="/adm/psr/ua" class="<?= Umbrella\components\Url::IsActive('/adm/psr/ua', 'active')  ?>">PSR UA</a></li>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.activity', 'view')): ?>
    <li><a href="/adm/psr/activity" class="<?= Umbrella\components\Url::IsActive('/adm/psr/activity', 'active')  ?>">Activity</a></li>
<?php endif; ?>
