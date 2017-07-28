<?php if (Umbrella\app\AdminBase::checkDenied('user.logs', 'view')): ?>
    <li><a href="/adm/user/logs" class="<?= Umbrella\components\Url::IsActive('/logs', 'active') ?>">Логи пользователей</a></li>
<?php endif;?>