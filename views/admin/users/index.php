<?php require_once ROOT . '/views/admin/layouts/header.php' ?>
<div class="row admin_head_menu">
    <div class="medium-10 small-12 columns">
        <ul class="admin_menu float-right">

            <?php require_once ROOT . '/views/admin/layouts/admin_menu.php' ?>

        </ul>
    </div>
</div>
<div class="row">
    <?php if(isset($message) && !empty($message)):?>
        <div class="medium-12 small-12 columns" style="text-align: center">
            <div class="alert-success" style="margin: 20px auto 0;"><?=$message?></div>
        </div>
    <?php endif;?>
    <div class="medium-12 small-12 columns">
        <div class="row body-content">
            <div class="medium-9 small-12 columns">
                <h2 class="float-left">List users</h2>
                <form action="" method="post" class="float-left" style="margin-left: 20px">
                    <select name="group" onchange="this.form.submit()">
                        <option value="all" <?=(isset($_REQUEST['group']) && $_REQUEST['group'] == 'all') ? 'selected' : ''?>>All</option>
                        <?php if(is_array($groupList)):?>
                            <?php foreach ($groupList as $group):?>
                                <option value="<?= $group['id']?>" <?=(isset($_REQUEST['group']) && $_REQUEST['group'] == $group['id']) ? 'selected' : ''?>><?= $group['group_name']?></option>
                            <?php endforeach;?>
                        <?php endif; ?>
                    </select>
                </form>
                <?php if (Umbrella\app\AdminBase::checkDenied('user.add', 'view')): ?>
                    <a href="/adm/user/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                <?php endif;?>
                <div class="clearfix"></div>
                <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th width="50px">ID</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Login</th>
                        <th>Role</th>
                        <th>Group</th>
                        <th>Activity</th>
                        <th width="50">Action</th>
                        <th width="50">Info GM</th>
                        <th width="50">Lock</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($listUsers)): ?>
                        <?php foreach ($listUsers as $userI): ?>
                            <tr data-userid="<?=$userI['id_user']?>">
                                <td><?=$userI['id_user']?></td>
                                <td><?=$userI['name_partner']?></td>
                                <td><?=$userI['short_name'] . " - " . $userI['full_name']?></td>
                                <td><?=$userI['login']?></td>
                                <td><?=$userI['name_role']?></td>
                                <td><?=$userI['group_name']?></td>
                                <td><?=$userI['date_active']?></td>
                                <td>
                                    <button  class="button no-margin small list-user-func">
                                        <i class="fi-list"></i>
                                    </button>
                                </td>
                                <td>
                                    <?php if($userI['role'] == 'partner'):?>
                                        <button class="button no-margin small info-gm-user dark">
                                            <i class="fi-info"></i>
                                        </button>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if (Umbrella\app\AdminBase::checkDenied('user.lock', 'view')): ?>
                                        <button data-lock="<?= $userI['is_active']?>" data-userid="<?=$userI['id_user']?>"  class="button no-margin small user-lock <?= $userI['is_active'] == 1 ? 'green' : 'red'?>">
                                            <i class="fi-<?= $userI['is_active'] == 1 ? 'unlock' : 'lock'?>"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="medium-3 small-12 columns">
                <h2 class="float-left">Group</h2>
                <?php if (Umbrella\app\AdminBase::checkDenied('group.add', 'view')): ?>
                    <a href="/adm/group/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                <?php endif;?>
                <div class="clearfix"></div>
                <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th>Group</th>
                        <th width="90px">Count user</th>
                        <th width="90px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($groupList)): ?>
                        <?php foreach ($groupList as $group): ?>
                            <tr>
                                <td><?= $group['group_name']?></td>
                                <td><?= $group['count_user']?></td>
                                <td>
                                <?php if (Umbrella\app\AdminBase::checkDenied('group.view', 'view')): ?>
                                    <a href="/adm/group/<?= $group['id'] ?>" class="button no-margin small"><i
                                                class="fi-eye"></i></a>
                                <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

                <div style="margin-bottom: 50px"></div>
                <h2 class="float-left">Country</h2>
                <?php if (Umbrella\app\AdminBase::checkDenied('country.add', 'view')): ?>
                    <a href="/adm/country/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                <?php endif;?>
                <div class="clearfix"></div>
                <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th>Страна</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($countryList)): ?>
                        <?php foreach ($countryList as $country): ?>
                            <tr>
                                <td><?= $country['short_name'] . " - " . $country['full_name'] ?></td>
                                <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                    <td>
                                        <?php if ($user->role == 'administrator' || $user->role == 'administrator-fin'): ?>
                                            <?php if (Umbrella\app\AdminBase::checkDenied('country.delete', 'view')): ?>
                                                <a href="/adm/country/delete/<?= $country['id_country'] ?>" class="button no-margin small hide"
                                               onclick="return confirm('Вы уверены что хотите удалить страну?') ? true : false;"><i
                                                        class="fi-x"></i></a>
                                            <?php endif; ?>

                                            <?php if (Umbrella\app\AdminBase::checkDenied('country.edit', 'view')): ?>
                                                <a href="/adm/country/update/<?= $country['id_country'] ?>" class="button no-margin small"><i
                                                        class="fi-pencil"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="reveal medium" id="show-list-user-func" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>List user action</h3>
        </div>
        <div class="medium-12 small-12 columns" id="container-details">

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal medium" id="info-gm-user" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Info GM user</h3>
        </div>
        <div class="medium-12 small-12 columns" id="container-user-details">

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>