<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Lithographer</h1>
            </div>
            <div class="medium-12 small-12 top-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-3 small-12 columns">
                        <button class="button primary tool" data-open="add-content"><i class="fi-plus"></i> Add</button>
                        <a href="/adm/lithographer/list" class="button primary tool active-req"><i class="fi-pencil"></i> Edit</a>
                    </div>
                    <div class="medium-3 medium-offset-6 small-12 columns form">
                        <input type="text" class="search-input" id="goods_search" placeholder="Search..." name="search">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="medium-2 left-bar header-content small-12 columns">
        <ul class="menu">

            <?php require_once 'sidebar.php'; ?>

        </ul>
    </div>
    <div class="medium-10 small-12 columns container-litographer">
        <div class="row content-litographer">
            <div class="medium-12 small-12 columns">
                <div class="callout">
                    <table id="goods_data">
                        <thead>
                        <tr>
                            <th width="50px">ID</th>
                            <th>Section</th>
                            <th>Title</th>
                            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                <th>Author</th>
                            <?php endif;?>
                            <th>Published</th>
                            <th style="text-align: center" colspan="3" width="150px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listLithographer)): ?>
                            <?php foreach ($listLithographer as $article): ?>
                                <tr class="goods">
                                    <td><?=$article['id']?></td>
                                    <td><?=$article['type_row']?></td>
                                    <td><?=$article['title']?></td>
                                    <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                                        <td><?=$article['name_partner']?></td>
                                    <?php endif;?>
                                    <td style="text-align: center" class="<?= Umbrella\models\Lithographer::getClassPublished($article['published'])?>"><?= Umbrella\models\Lithographer::getPublished($article['published'])?></td>
                                    <td style="text-align: center">
                                        <?php if($article['type_row'] != 'video'):?>
                                        <a href="/adm/lithographer/<?=$article['type_row']?>/<?=$article['id']?>"><i class="fi-eye"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td style="text-align: center">
                                        <?php if($user->role == 'partner' && $article['published'] == 0):?>
                                            <a href="/adm/lithographer/edit/<?=$article['id']?>"><i class="fi-pencil"></i></a>
                                        <?php elseif(($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager')):?>
                                            <a href="/adm/lithographer/edit/<?=$article['id']?>"><i class="fi-pencil"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td style="text-align: center">
                                        <?php if($user->role == 'partner' && $article['published'] == 0):?>
                                            <a href="/adm/lithographer/delete/<?=$article['id']?>"
                                               onclick="return confirm('Вы уверены что хотите удалить?') ? true : false;"><i
                                                    class="fi-x"></i></a>
                                        <?php elseif(($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager')):?>
                                            <a href="/adm/lithographer/delete/<?=$article['id']?>"
                                               onclick="return confirm('Вы уверены что хотите удалить?') ? true : false;"><i
                                                    class="fi-x"></i></a>
                                        <?php endif;?>
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
</div>
<div class="reveal small" id="video-modal" data-reveal data-close-on-click="true" data-animation-in="fade-in"
     data-animation-out="fade-out"></div>


<div class="reveal small" id="add-content" data-reveal data-close-on-click="true">
    <div class="row align-center">
        <div class="medium-12 text-center small-12 columns">
            <h1>Add content</h1>
        </div>
        <div class="large-12 medium-12 small-12 columns">

            <?php require_once 'forms.php'; ?>

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
