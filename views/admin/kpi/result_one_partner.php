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
                            <?php if($user->isAdmin() || $user->isManager()):?>
                                <div class="medium-3 small-12 columns">
                                    <label>Partner
                                        <select name="name_partner">
                                            <option value="all">All partners</option>
                                            <?php if (is_array($listPartner)): ?>
                                                <?php foreach ($listPartner as $partner): ?>
                                                    <option value="<?=$partner['name_partner']?>" <?= ($name_partner == $partner['name_partner']) ? 'selected' : ''?>><?=$partner['name_partner']?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </label>
                                </div>
                            <?php endif;?>
                            <div class="medium-2 small-12 columns">
                                <button type="submit" class="button primary">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row body-content">
                <div class="medium-12 small-12 columns">
                    <h2 class="text-center">Report from the <strong><?=$start?></strong> on <strong> <?=$end?></strong> partner <span class="lead">«<?=$name_partner?>»</span> </h2>
                    <button class="button primary float-right" onclick="tableToExcel('table_kpi', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                    <div id="table_kpi">
                    <table class="table" data-start="<?=$start?>" data-end="<?=$end?>">
                        <thead>
                        <tr style="border-color: #fff">
                            <th></th>
                            <th>SLA</th>
                            <th>Target</th>
                            <th><?=$name_partner?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php $Order_TAT = $KPI->Order_TAT()?>
                            <td>Order TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::Order_TAT ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="Order TAT"
                                class="<?=$KPI->controlTargetUp($Order_TAT, $KPI::Order_TAT)?>">
                                <?=$Order_TAT?>
                            </td>
                        </tr>
                        <tr>
                            <?php $Repair_TAT = $KPI->Repair_TAT()?>
                            <td>Repair TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::Repair_TAT ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="Repair TAT"
                                class="<?=$KPI->controlTargetUp($Repair_TAT, $KPI::Repair_TAT)?>">
                                <?=$Repair_TAT?>
                            </td>
                        </tr>
                        <tr>
                            <?php $SW_Repair_TAT = $KPI->SW_Repair_TAT()?>
                            <td>SW repair TAT</td>
                            <td>1 business day</td>
                            <td><?=$KPI::SW_repair_TAT ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="SW repair TAT"
                                class="<?=$KPI->controlTargetUp($SW_Repair_TAT, $KPI::SW_repair_TAT)?>">
                                <?=$SW_Repair_TAT?>
                            </td>
                        </tr>
                        <tr>
                            <?php $SO_Creation_TAT = $KPI->SO_Creation_TAT()?>
                            <td>SO creation TAT</td>
                            <td>same business day</td>
                            <td><?=$KPI::SO_creation_TAT ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="SO creation TAT"
                                class="<?=$KPI->controlTargetUp($SO_Creation_TAT, $KPI::SO_creation_TAT)?>">
                                <?=$SO_Creation_TAT?>
                            </td>
                        </tr>
                        <tr>
                            <?php $L0_Rate = $KPI->L0_Rate()?>
                            <td>L0 Rate</td>
                            <td></td>
                            <td><?=$KPI::L0_Rate ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="L0 Rate"
                                class="<?=$KPI->controlTargetL0Rate($L0_Rate, $KPI::L0_Rate)?>">
                                <?=$L0_Rate?>
                            </td>
                        </tr>
                        <tr>
                            <?php $PPl = $KPI->PPl()?>
                            <td>PPl</td>
                            <td></td>
                            <td><?=$KPI::PPl ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="PPl"
                                class="<?=$KPI->controlTargetPPl($PPl, $KPI::PPl)?>">
                                <?=$PPl?>
                            </td>
                        </tr>
                        <tr>
                            <?php $LongTail_14_Days = $KPI->LongTail_14_Days()?>
                            <td>LongTail 14 days</td>
                            <td></td>
                            <td><?=$KPI::LongTail_14_days ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="LongTail 14 days"
                                class="<?=$KPI->controlTargetUp($LongTail_14_Days, $KPI::LongTail_14_days)?>">
                                <?=$LongTail_14_Days?>
                            </td>
                        </tr>
                        <tr>
                            <?php $LongTail_21_Days = $KPI->LongTail_21_Days()?>
                            <td>LongTail 21 days</td>
                            <td></td>
                            <td><?=$KPI::LongTail_21_days ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="LongTail 21 days"
                                class="<?=$KPI->controlTargetDown($LongTail_21_Days, $KPI::LongTail_21_days)?>">
                                <?=$LongTail_21_Days?>
                            </td>
                        </tr>
                        <tr>
                            <?php $FTP_30_DAYS = $KPI->FTP_30_DAYS()?>
                            <td>FTF 30 days</td>
                            <td></td>
                            <td><?=$KPI::FTP_30_days ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="FTF 30 days"
                                class="<?=$KPI->controlTargetDown($FTP_30_DAYS, $KPI::FTP_30_days)?>">
                                <?= str_replace('.',',', $FTP_30_DAYS)?>
                            </td>
                        </tr>
                        <tr>
                            <?php $FTP_90_DAYS = $KPI->FTP_90_DAYS()?>
                            <td>FTF 90 days</td>
                            <td></td>
                            <td><?=$KPI::FTP_90_days ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="FTF 90 days"
                                class="<?=$KPI->controlTargetDown($FTP_90_DAYS, $KPI::FTP_90_days)?>">
                                <?= str_replace('.',',', $FTP_90_DAYS)?>
                            </td>
                        </tr>
                        <tr>
                            <?php $L2_Rate = $KPI->L2_Rate()?>
                            <td>L2 Rate</td>
                            <td></td>
                            <td><?=$KPI::L2_Rate ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="FTF 90 days"
                                class="<?=$KPI->controlTargetUp($L2_Rate, $KPI::L2_Rate)?>">
                                <?=$L2_Rate?>
                            </td>
                        </tr>
                        <tr>
                            <?php $Refund_Rate = $KPI->Refund_Rate()?>
                            <td>Refund Rate</td>
                            <td></td>
                            <td><?=$KPI::Refund_Rate ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="Refund Rate"
                                class="<?=$KPI->controlTargetDown($Refund_Rate, $KPI::Refund_Rate)?>">
                                <?=$Refund_Rate?>
                            </td>
                        </tr>
                        <?php if($user->isAdmin() || $user->isManager()):?>
                        <tr>
                            <?php $LS_Rate = $KPI->LS_Rate()?>
                            <td>LS rate</td>
                            <td></td>
                            <td><?=$KPI::LS_Rate ?>%</td>
                            <td
                                data-partner="<?=$name_partner?>"
                                data-kpi="LS rate"
                                class="<?=$KPI->controlTargetUp($LS_Rate, $KPI::LS_Rate)?>">
                                <?=$LS_Rate?>
                            </td>
                        </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 100px"></div>

    <div class="reveal large" id="show-problem" data-reveal>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Purchase goods</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <table>
                    <thead>
                    <tr>
                        <th>SO Number</th>
                        <th>SO Creation Date</th>
                        <th>Serial Number</th>
                        <th>SO Complete Date</th>
                        <th>Item Product ID</th>
                        <th>Item Product Desc</th>
                        <th>IRIS 1 - Repair</th>
                        <th>Unit Received Date</th>
                        <th>Part Order Date</th>
                        <th>Part Delivery Date</th>
                        <th>Customer Email</th>
                    </tr>
                    </thead>
                    <tbody id="container-details">

                    </tbody>
                </table>
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

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