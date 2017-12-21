<div class="reveal" id="download-all-price" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Download all prices in excel file</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <div class="row">

                <div class="medium-12 small-12 columns">
                    <div class="row" style="color: #fff">
                        <ul>
                            <?php if($user->isAdmin() || $user->isManager()):?>
                                <li>
                                    <a href="<?= $user->linkUrlDownloadAllPrice(2, 'Партнер')?>" download>
                                        <span style="color: orange"><?= $user->linkNameDownloadAllPrice(2, 'Партнер')?></span>
                                    </a>
                                    <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(2, 'Партнер')?></span>
                                </li>
                                <li>
                                    <a href="<?= $user->linkUrlDownloadAllPrice(2, 'Оптовик')?>" download>
                                        <span style="color: orange"><?= $user->linkNameDownloadAllPrice(2, 'Оптовик')?></span>
                                    </a>
                                    <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(2, 'Оптовик')?></span>
                                </li>
                                <li>
                                    <a href="<?= $user->linkUrlDownloadAllPrice(4, 'Партнер GE')?>" download>
                                        <span style="color: orange"><?= $user->linkNameDownloadAllPrice(4, 'Партнер GE')?></span>
                                    </a>
                                    <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice(4, 'Партнер GE')?></span>
                                </li>
                                <li>
                                    <a href="/adm/crm/request/all__ukraine_price" id="all_price">
                                        <span style="color: orange">Скачать все цены</span>
                                        <span class="download_wait" style="color: #40e240"></span>
                                    </a>
                                </li>
                            <?php elseif ($user->isPartner()):?>
                                <?php if($user->getGroupName() == 'Lenovo ПСР'
                                    || $user->getGroupName() == 'Lenovo'
                                    || $user->getGroupName() == 'UKRAINE OOW'): ?>
                                <li>
                                    <a href="/adm/crm/request/all__ukraine_price" id="all_price">
                                        <span style="color: orange">All Ulraine price</span>
                                        <span class="download_wait" style="color: #40e240"></span>
                                    </a>
                                </li>
                                <?php else: ?>
                                    <li>
                                        <a href="<?= $user->linkUrlDownloadAllPrice()?>" download>
                                            <span style="color: orange"><?= $user->linkNameDownloadAllPrice()?></span>
                                        </a>
                                        <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice()?></span>
                                    </li>
                                <?php endif;?>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>