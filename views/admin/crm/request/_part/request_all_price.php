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
                            <?php if($user->role == 'administrator' || $user->role == 'manager'):?>
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
                            <?php elseif ($user->role == 'partner'):?>
                                <li>
                                    <a href="<?= $user->linkUrlDownloadAllPrice()?>" download>
                                        <span style="color: orange"><?= $user->linkNameDownloadAllPrice()?></span>
                                    </a>
                                    <span class="date-upload-price">new upload date: <?= $user->lastUploadDateAllPrice()?></span>
                                </li>
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