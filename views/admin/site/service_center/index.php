<?php require_once ROOT . '/views/admin/layouts/header.php' ?>
<div class="row admin_head_menu">
    <div class="medium-10 small-12 columns">
        <ul class="admin_menu float-right">

            <?php require_once ROOT . '/views/admin/layouts/site_menu.php' ?>

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
            <div class="medium-12 small-12 columns">
                <h2 class="float-left">List service center</h2>
                    <a href="/adm/site/service-center/add" class="button small float-right"><i class="fi-plus"></i> Добавить</a>
                <div class="clearfix"></div>
                <table class="umbrella-table" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                    <tr>
                        <th width="50px">ID</th>
                        <th>Company name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th width="50"></th>
                        <th width="50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($allServiceCenter)): ?>
                        <?php foreach ($allServiceCenter as $service): ?>
                            <tr>
                                <td><?=$service['id']?></td>
                                <td><?=$service['company_name']?></td>
                                <td><?=$service['country_ru']?></td>
                                <td><?=$service['city_ru']?></td>
                                <td><?=$service['address_ru'] ?></td>
                                <td><?=$service['phone']?></td>
                                <td><a href="/adm/site/service-center/edit/<?=$service['id']?>" class="button no-margin small"><i class="fa fa-pencil"></i> </a></td>
                                <td>
                                    <a href="/adm/site/service-center/delete/<?=$service['id']?>" onclick="return confirm('вы уверены?') ? true : false;" class="button no-margin small"><i class="fa fa-trash"></i> </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>