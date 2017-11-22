<!doctype html>
<html class="no-js" lang="ru" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GS Umbrella</title>
    <link rel="stylesheet" href="/template/admin/css/foundation.min.css">
    <link rel="stylesheet" href="/template/admin/css/app.css">
    <link rel="stylesheet" href="/template/admin/css/fonts.css">
    <link rel="stylesheet" href="/template/admin/fonts/foundation-icons/foundation-icons.css">
    <link rel="stylesheet" href="/template/admin/css/style.css?v.2.0.2">
    <link rel="stylesheet" href="/template/admin/font-awesome/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.0.0/video-js.min.css" rel="stylesheet">
    <?php

    if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/ccc')) echo "<link rel='stylesheet' href='/template/admin/css/ccc_style.css'>";
    ?>
    <style>
        .yellow {
            background: #FFFF6D;
        }

        .red {
            background: #c1433c;
            color: #fff;
        }

        .green {
            background: #66bb6a;
            color: #fff;
        }
    </style>
</head>

<body>
<header>
    <div id="wait" class="hide">
        <div id="container-wait">
            <h1>Please wait</h1>
            <img src="/template/admin/img/wait.svg" alt="">
        </div>
    </div>
    <div class="header-user-menu">
        <div class="row">
            <div class="medium-12 small-12 column text-right">
                <?php if($user->getUserBlockedGM() == 'tomorrow'):?>
                    <a href="/adm/risks" class="text-red">Внимание!</a>
                <?php endif;?>
                <span class="user-name"><?=$user->getName()?></span>
            </div>
        </div>
    </div>

    <div class="row align-middle">
        <div class="medium-2 small-12 columns">
            <a href="/">
                <img src="/template/admin/img/logo.svg" alt="GS Umbrella">
            </a>
        </div>
        <div class="medium-8 small-12 columns">
            <ul class="menu align-right">
                <?php if (Umbrella\app\AdminBase::checkDenied('adm.dashboard', 'view')): ?>
                    <li><a href="/adm/dashboard" class="<?= Umbrella\components\Url::IsActive('/dashboard', 'active') ?>">Dashboard</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.users', 'view')): ?>
                    <li><a href="/adm/users" class="<?= Umbrella\components\Url::IsActive('/user', 'active') ?>">Users</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.kpi', 'view')): ?>
                    <li><a href="/adm/kpi" class="<?= Umbrella\components\Url::IsActive(['/adm/kpi', '/adm/result'], 'active') ?>">KPI</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.refund_request', 'view')): ?>
                    <li><a href="/adm/refund_request/registration" class="<?=Umbrella\components\Url::IsActive('/refund_request', 'active') ?>">Refund Request</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.crm', 'view')): ?>
                    <li><a href="/adm/crm/" class="<?= Umbrella\components\Url::IsActive('/crm/', 'active') ?>">CRM</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.lithographer', 'view')): ?>
                    <li><a href="/adm/lithographer/video" class="<?= Umbrella\components\Url::IsActive('/lithographer', 'active')?>">Lithographer</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.ccc', 'view')): ?>
                    <li><a href="/adm/ccc"  class="<?= Umbrella\components\Url::IsActive('/ccc', 'active')?>">CCC</a></li>
                <?php endif; ?>

                <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr', 'view')): ?>
                    <li><a href="/adm/psr/ua"  class="<?= Umbrella\components\Url::IsActive('/adm/psr', 'active')?>">PSR</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="medium-2 small-12 text-right columns">
            <a class="exit-link" href="/adm/logout">Exit <i class="fi-play"></i></a>
        </div>
    </div>
</header>

<a href="" class="link-innovation hide">News</a>

<?php $listInnovation = $user->checkNewInnovation()?>
<?php if(is_array($listInnovation)):?>
<div class="new-changes">
    <div class="container-changes">
        <div class="container-inner">
            <button type="button" class="close close-changes float-right" data-dismiss="modal" aria-label="Close"><span>×</span>
            </button>
            <h4>Список изменений в Umbrella</h4>
            <div>
                <?php foreach ($listInnovation as $innovation):?>
                    <div class="list-changes">
                        <time class="float-right">Дата: <?= \Umbrella\components\Functions::formatDate($innovation['created_at'])?></time>
                        <?= $innovation['new_content']?>
                        <button class="view-ok click-view-ok float-right" data-innovation-id="<?= $innovation['id']?>"><i class="fi-check"></i> Ознакомлен(а)</button>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>