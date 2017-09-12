<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-12 small-12 columns">
            <div class="row body-content">
                <div class="medium-8 small-12 columns">
                    <h2 class="float-left">Список адресов для пользователя <?= $selectUser->name_partner ?></h2>
                    <a href="/adm/users" style="margin-bottom: 0" class="button small float-right">Назад</a>
                    <div class="clearfix"></div>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                        <tr>
                            <th>Address</th>
                            <th width="100">Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listAddress)):?>
                            <?php foreach ($listAddress as $address):?>
                                <tr>
                                    <td><span class="user-address"><?= $address['address']?></span></td>
                                    <td>
                                        <button data-id="<?= $address['id']?>" class="button no-margin small edit-address"><i class="fi-pencil"></i></button>
                                        <a href="/adm/user/address/delete/<?= $address['id']?>" class="button no-margin small" onclick="return confirm('Вы уверены что хотите удалить адрес?') ? true : false;"><i class="fi-x"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>

                <div class="medium-4 small-12 columns">
                    <h2 class="float-left">Добавить адрес</h2>
                    <div class="clearfix"></div>
                    <form action="" method="post">
                        <input type="text" name="address" autocomplete="off">
                        <input type="hidden" name="add_user_address" value="true">
                        <button type="submit" style="margin-top: 15px" class="button small float-right">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="reveal" id="edit-user-address" data-reveal>
        <form action="#" method="post" class="form" novalidate="">
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Edit address</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label>Address</label>
                            <input type="text" id="user_address" name="address" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="medium-12 small-12 columns">
                    <button type="button" id="send-user-address" class="button primary">Edit</button>
                </div>
            </div>
        </form>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>