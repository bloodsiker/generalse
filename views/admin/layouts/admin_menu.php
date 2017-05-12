<?php if (AdminBase::checkDenied('user.logs', 'view')): ?>
    <li><a href="/adm/user/logs" class="<?= Url::IsActive('/logs', 'active') ?>">Логи пользователей</a></li>
<?php endif;?>