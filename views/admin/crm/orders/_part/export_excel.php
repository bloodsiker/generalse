<div class="reveal large" id="export-modal" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Generate report</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <form action="/adm/crm/export/orders/" method="POST" id="form-generate-excel" data-abide>

                <h4 style="color: #fff">Between date</h4>
                <div class="row align-bottom" style="background: #323e48; padding-top: 10px; margin-bottom: 10px">
                    <div class="medium-6 small-6 columns">
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
                    <div class="medium-3 small-3 columns">
                        <label>Status</label>
                        <select name="status_name" id="status_name">
                            <option value="">none</option>
                            <option value="В обработке">В обработке</option>
                            <option value="Предварительный">Предварительный</option>
                            <option value="Отказано">Отказано</option>
                            <option value="Выдан">Выдан</option>
                            <option value="Резерв">Резерв</option>
                        </select>
                    </div>
                    <div class="medium-3 small-3 columns">
                        <label>Type</label>
                        <select name="order_type_id">
                            <option value="">none</option>
                            <option value="1">Гарантия</option>
                            <option value="2">Негарантия</option>
                        </select>
                    </div>
                </div>

                <h4 style="color: #fff">Partners</h4>
                <?php if($user->isAdmin()):?>
                    <div class="row align-bottom" style="background: #323e48; padding-top: 10px">
                        <div class="medium-12 small-12 columns">
                            <ul class="tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-tabs id="deeplinked-tabs">
                                <?php foreach ($userInGroup as $groups):?>
                                    <li class="tabs-title">
                                        <a href="#group-<?= $groups['group_id']?>" aria-selected="true"><?= $groups['group_name']?></a>
                                    </li>
                                <?php endforeach;?>
                            </ul>

                            <div class="tabs-content" data-tabs-content="deeplinked-tabs" style="background: #323e48; margin-bottom: 10px">
                                <?php foreach ($userInGroup as $groups):?>
                                    <div class="tabs-panel" id="group-<?= $groups['group_id']?>">
                                        <div class="row">
                                            <div class="medium-12 small-12 columns">
                                                <span>
                                                    <input type="checkbox" onclick="checkAllCheckbox(event, '#group-<?= $groups['group_id']?>')" id="id-<?= $groups['group_id']?>-all">
                                                    <label class="check all" for="id-<?= $groups['group_id']?>-all">Выбрать всех</label>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php foreach($groups['users'] as $partner):?>
                                                <div class="medium-4 small-4 columns">
                                                <span>
                                                    <?php $checked = Umbrella\models\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                                    <input type="checkbox" <?= ($checked ? 'checked' : '')?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                    <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$partner['name_partner'] ?></label><br>
                                                </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="row align-bottom" style="background: #323e48; padding-top: 10px">
                        <div class="medium-12 small-12 columns">
                            <input type="text" id="search" placeholder="Search" autocomplete="off">
                        </div>
                        <div class="medium-12 small-12 columns">
                            <span>
                                <input type="checkbox" onclick="checkAllCheckbox(event)" id="id-all">
                                <label class="check all" for="id-all" >Выбрать всех</label>
                            </span>
                        </div>
                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <?php if(is_array($partnerList)):?>
                                    <?php foreach($partnerList as $partner):?>
                                        <div class="medium-4 small-4 columns">
                                            <span>
                                                <?php $checked = Umbrella\models\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                                <?php $checkUser = $user->id_user == $partner['id_user'] ? true : false?>
                                                <input type="checkbox" <?= ($checked || $checkUser) ? 'checked' : ''?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                                <label  class="check" for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked || $checkUser) ? 'green' : ''?>;"><?=$partner['name_partner'] ?></label><br>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row align-bottom" style="padding-top: 10px; margin-top: 10px">
                    <div class="medium-3 small-3 medium-offset-9 columns">
                        <button type="submit" id="apply-stock-filter" class="button primary">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>