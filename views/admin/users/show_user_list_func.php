<table>
    <thead>
        <tr>
            <th>Action</th>
            <th width="70"></th>
        </tr>
    </thead>
    <tbody>

    <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
        <?php if (Umbrella\app\AdminBase::checkDenied('user.edit', 'view')): ?>
            <tr>
                <td>Редактировать информацию о пользователе</td>
                <td>
                    <a href="/adm/user/update/<?= $user_id?>" class="button no-margin small"><i class="fi-pencil"></i></a>
                </td>
            </tr>
        <?php endif; ?>

        <?php if (Umbrella\app\AdminBase::checkDenied('user.delete', 'view')): ?>
            <tr>
                <td>Удалить пользователя</td>
                <td>
                    <a href="/adm/user/delete/<?= $user_id?>" class="button no-margin small" onclick="return confirm('Вы уверены что хотите удалить пользователя?') ? true : false;"><i class="fi-x"></i></a>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (Umbrella\app\AdminBase::checkDenied('user.control', 'view')): ?>
        <tr>
            <td>Управление пользователями</td>
            <td>
                <a href="/adm/user/control/<?= $user_id?>" class="button no-margin small"><i class="fi-eye"></i></a>
            </td>
        </tr>
    <?php endif; ?>

    <?php if (Umbrella\app\AdminBase::checkDenied('user.denied', 'view')): ?>
        <tr>
            <td>Доступы пользователя к разделам</td>
            <td>
                <a href="/adm/user/denied/<?= $user_id?>" class="button no-margin small"><i class="fi-wrench"></i></a>
            </td>
        </tr>
    <?php endif; ?>

    <?php if (Umbrella\app\AdminBase::checkDenied('user.address', 'view')): ?>
        <tr>
            <td>Адреса доставок</td>
            <td>
                <a href="/adm/user/address/<?= $user_id?>" class="button no-margin small"><i class="fi-map"></i></a>
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
