<?php require_once views_path('admin/layouts/header.php') ?>

    <div class="row">
        <div class="medium-12 small-12 columns">
            <div class="row header-content">
                <div class="medium-12 small-12 top-gray columns">
                    <h1 class="title-filter">Containing data on  <?= $firstData?> — <?= $lastData?></h1>
                </div>
                <div class="medium-12 small-12 bottom-gray colmns">
                    <form action="/adm/result/" method="get" class="form" id="kpi" data-abide novalidate>
                        <div class="row align-bottom">
                            <div class="medium-2 text-left small-12 columns">
                                <label for="right-label">From date</label>
                                <input type="text"  id="date-start" placeholder="date" value="<?=$start?>" name="start">
                            </div>
                            <div class="medium-2 small-12 columns">
                                <label for="right-label">To date</label>
                                <input type="text" id="date-end" placeholder="date" value="<?=$end?>" name="end">
                            </div>
                            <div class="medium-3 small-12 columns">
                                <label>Partner
                                    <select name="name_partner">
                                        <option value="all">All partners</option>
                                        <?php if (is_array($listPartner)): ?>
                                            <?php foreach ($listPartner as $partner): ?>
                                                <option value="<?=$partner['name_partner']?>"><?=$partner['name_partner']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </label>
                            </div>
                            <div class="medium-2 small-12 columns">
                                <button type="submit" class="button primary">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row body-content">
                <div class="medium-12 small-12 columns">
                    <h2 class="text-center">Report from the <strong><?=$start?></strong> on <strong> <?=$end?></strong> all partners</h2>
                    <button class="button primary float-right" onclick="tableToExcel('table_kpi', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                    <div id="table_kpi">
                    <table class="table">
                        <thead>
                        <tr style="border-color: #fff">
                            <th rowspan="2"></th>
                            <th rowspan="2">SLA</th>
                            <th rowspan="2">Target</th>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <th><?= $partner['short_name'] ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <th><?= $partner['name_partner'] ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Order TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::Order_TAT ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $Order_TAT = $KPI->Order_TAT()?>
                                    <td class="<?= $KPI->controlTargetUp($Order_TAT, $KPI::Order_TAT) ?>"><?= $Order_TAT ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>Repair TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::Repair_TAT ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $Repair_TAT = $KPI->Repair_TAT()?>
                                    <td class="<?= $KPI->controlTargetUp($Repair_TAT, $KPI::Repair_TAT) ?>"><?= $Repair_TAT ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>SW repair TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::SW_repair_TAT ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $SW_Repair_TAT = $KPI->SW_Repair_TAT()?>
                                    <td class="<?= $KPI->controlTargetUp($SW_Repair_TAT, $KPI::SW_repair_TAT) ?>"><?= $SW_Repair_TAT ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>SO creation TAT</td>
                            <td>same business day</td>
                            <td><?=$KPI::SO_creation_TAT ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $SO_Creation_TAT = $KPI->SO_Creation_TAT()?>
                                    <td class="<?= $KPI->controlTargetUp($SO_Creation_TAT, $KPI::SO_creation_TAT) ?>"><?= $SO_Creation_TAT ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>L0 Rate</td>
                            <td></td>
                            <td><?=$KPI::L0_Rate ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $L0_Rate = $KPI->L0_Rate()?>
                                    <td class="<?= $KPI->controlTargetL0Rate($L0_Rate, $KPI::L0_Rate) ?>"><?= $L0_Rate ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>PPl</td>
                            <td></td>
                            <td><?=$KPI::PPl ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $PPl = $KPI->PPl()?>
                                    <td class="<?= $KPI->controlTargetPPl($PPl, $KPI::PPl) ?>"><?= $PPl ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>LongTail 14 days</td>
                            <td></td>
                            <td><?=$KPI::LongTail_14_days ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $LongTail_14_Days = $KPI->LongTail_14_Days()?>
                                    <td class="<?= $KPI->controlTargetUp($LongTail_14_Days, $KPI::LongTail_14_days) ?>"><?= $LongTail_14_Days ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>LongTail 21 days</td>
                            <td></td>
                            <td><?=$KPI::LongTail_21_days ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $LongTail_21_Days = $KPI->LongTail_21_Days()?>
                                    <td class="<?= $KPI->controlTargetDown($LongTail_21_Days, $KPI::LongTail_21_days) ?>"><?= $LongTail_21_Days ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>FTF 30 days</td>
                            <td></td>
                            <td><?=$KPI::FTP_30_days ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $FTP_30_DAYS = $KPI->FTP_30_DAYS()?>
                                    <td class="<?=$KPI->controlTargetDown($FTP_30_DAYS, $KPI::FTP_30_days)?>"><?= str_replace('.',',', $FTP_30_DAYS)?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>FTF 90 days</td>
                            <td></td>
                            <td><?=$KPI::FTP_90_days ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $FTP_90_DAYS = $KPI->FTP_90_DAYS()?>
                                    <td class="<?=$KPI->controlTargetDown($FTP_90_DAYS, $KPI::FTP_90_days)?>"><?= str_replace('.',',', $FTP_90_DAYS)?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>L2 Rate</td>
                            <td></td>
                            <td><?=$KPI::L2_Rate ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $L2_Rate = $KPI->L2_Rate()?>
                                    <td class="<?= $KPI->controlTargetUp($L2_Rate, $KPI::L2_Rate) ?>"><?= $L2_Rate ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>Refund Rate</td>
                            <td></td>
                            <td><?=$KPI::Refund_Rate ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $Refund_Rate = $KPI->Refund_Rate()?>
                                    <td class="<?= $KPI->controlTargetDown($Refund_Rate, $KPI::Refund_Rate) ?>"><?= $Refund_Rate ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td>LS rate</td>
                            <td></td>
                            <td><?=$KPI::LS_Rate ?>%</td>
                            <?php if (is_array($listPartner)): ?>
                                <?php foreach ($listPartner as $partner): ?>
                                    <?php $KPI = new Umbrella\components\KPI($partner['name_partner'], $start, $end);?>
                                    <?php $LS_Rate = $KPI->LS_Rate()?>
                                    <td class="<?= $KPI->controlTargetUp($LS_Rate, $KPI::LS_Rate) ?>"><?= $LS_Rate ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 100px"></div>

    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table);
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>

<?php require_once views_path('admin/layouts/footer.php') ?>
