<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Disassembly</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-4 medium-offset-8 small-12 columns">
								<input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
                <form action="/adm/crm/disassembly_list/" method="get">
                    <div class="row align-bottom">
                        <div class="medium-2 text-left small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                            <input type="text" id="date-start" name="start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" required>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                            <input type="text" id="date-end" name="end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>">
                        </div>
                        <div class="medium-1 small-12 columns">
                            <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                        </div>
                        <div class="medium-5 medium-offset-2 small-12 columns text-right">
                            <a class="button primary tool" id="export-button"><i class="fi-page-export"></i> Export to Excel</a>
                            <a href="/adm/crm/disassembly" class="button primary tool">send request</a>
                            <a href="/adm/crm/disassembly_list" class="button primary tool active-req">list disassembly</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <table class="umbrella-table" id="goods_data">
                    <caption>Last recording on
                        <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : Umbrella\components\Functions::addDays(date('Y-m-d'), '-30 days') ?> &mdash;
                        <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                        <span id="count_refund" class="text-green">(<?php if (isset($listDisassembly)) echo count($listDisassembly) ?>)</span>
                    </caption>
                    <thead>
                    <tr>
                        <th width="70">ID</th>
                        <th>Partner</th>
                        <th>Device</th>
                        <th>Part Number</th>
                        <th>Serial Number</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Date create</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if (is_array($listDisassembly)): ?>
                        <?php foreach ($listDisassembly as $dis): ?>
                            <tr class="goods" data-siteid="<?=$dis['site_id']?>">
								<?php $status = Umbrella\models\Disassembly::checkStatusRequestMSSQL($dis['site_id']);
                                $id_gs = iconv('WINDOWS-1251', 'UTF-8', $status['decompile_id'])?>
                                <td><?=$id_gs?></td>
                                <td><?=$dis['name_partner']?></td>
                                <td><?=$dis['dev_name']?></td>
                                <td><?=$dis['part_number']?></td>
                                <td><?=$dis['serial_number']?></td>
                                <td><?=$dis['stockName']?></td>
                                <?php $status = iconv('WINDOWS-1251', 'UTF-8', $status['status_name'])?>
                                <td class="<?= Umbrella\models\Disassembly::getStatusRequest($status)?>">
                                    <?= ($status == NULL) ? 'Expect' : $status ?>
                                </td>
                                <td><?= Umbrella\components\Functions::formatDate($dis['date_create'])?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="reveal" id="export-modal" data-reveal>
    <form action="/adm/crm/export/disassembly/" id="" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Generate report</h3>
            </div>
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
                <button type="submit" class="button primary">Generate</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal large" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Spare parts</h3>
        </div>
        <div class="medium-12 small-12 columns" id="container-details">

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
