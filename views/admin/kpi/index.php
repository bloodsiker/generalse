<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1 class="title-filter">Containing data on  <strong><?= $firstData['Service_Complete_Date']?> — <?= $lastData['Service_Complete_Date']?></strong></h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <form action="/adm/result/" method="get" id="kpi" class="form" data-abide novalidate>
                    <div class="row align-bottom">
                        <div class="medium-2 text-left small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                            <input type="text"  id="date-start"  name="start" required>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                            <input type="text" id="date-end" name="end">
                        </div>
                        <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                        <div class="medium-2 small-12 columns">
                            <label><i class="fi-torso-business"></i> Partner
                                <select name="name_partner">
                                    <option value="all">All partners</option>
                                    <?php if (is_array($listPartner)): ?>
                                        <?php foreach ($listPartner as $partner): ?>
                                            <?php if($partner['name_partner'] != 'GS Test' && $partner['name_partner'] != 'GS Electrolux'  && $partner['name_partner'] != 'GS Electrolux'):?>
                                            <option value="<?=$partner['name_partner']?>"><?=$partner['name_partner']?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </label>
                        </div>
                        <?php endif;?>
                        <div class="medium-2 small-12 columns">
                            <button type="submit" class="button primary">Show</button>
                        </div>

                        <?php if($user->role == 'partner'):?>
                            <div class="medium-2 small-12 columns">

                            </div>
                        <?php endif;?>

                        <div class="medium-4 text-right small-12 columns">
                            <?php if (AdminBase::checkDenied('kpi.usage', 'view')): ?>
                                <a class="button primary tool" data-open="usage-modal">USAGE</a>
                            <?php endif;?>

                            <?php if (AdminBase::checkDenied('kpi.import', 'view')): ?>
                                <?php if($user->role == 'administrator' || $user->role == 'administrator-fin'):?>
                                    <a href="/adm/kpi/import" class="button primary tool"><i class="fi-page-export"></i> Import</a>
                                <?php endif;?>
                            <?php endif;?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="reveal" id="usage-modal" data-reveal>
    <form action="/adm/kpi/usage/" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Usage</h3>
            </div>
            <?php if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner
                                <select name="id_partner" class="required" required>
                                    <option value="all">All</option>
                                    <?php if(is_array($listPartner)):?>
                                        <?php foreach($listPartner as $partner):?>
                                            <option value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            <?php elseif ($user->role == 'partner'):?>
                <div class="medium-12 small-12 columns">
                    <label><i class="fi-list"></i> Partner</label>
                    <select name="id_partner" class="required" required>
                        <?php $user->renderSelectControlUsers($user->id_user);?>
                    </select>
                </div>
            <?php endif;?>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <label>From Date</label>
                        <input type="text" class="required date" name="start" required>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label>To Date</label>
                        <input type="text" class="required date" name="end" required>
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Show</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>


