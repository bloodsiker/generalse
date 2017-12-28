<?php require_once views_path('admin/layouts/header.php') ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1 class="title-filter">Usage</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-3 text-left small-12 columns">
                        <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                    </div>

                    <div class="medium-4 medium-offset-5 text-right small-12 columns">
                        <?php if (Umbrella\app\AdminBase::checkDenied('kpi.usage', 'view')): ?>
                            <a class="button primary tool" data-open="usage-modal">USAGE</a>
                        <?php endif;?>

                        <?php if (Umbrella\app\AdminBase::checkDenied('adm.kpi', 'view')): ?>
                            <a href="/adm/kpi" class="button primary tool"> KPI</a>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>


        <div class="body-content checkout">
            <div class="row">
                <?php if (isset($listUsage) && !empty($listUsage)): ?>
                    <button class="button primary float-right" onclick="tableToExcel('goods_data', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                    <table id="goods_data">
                        <caption>The result of the sample for
                            <?= (isset($_GET['start']) && !empty($_GET['start'])) ? $_GET['start'] : date('Y-m-d') ?> &mdash;
                            <?= (isset($_GET['end']) && !empty($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>
                            <span id="count_refund" class="text-green">(<?php if (isset($listUsage)) echo count($listUsage) ?>)</span>
                        </caption>
                        <thead>
                        <tr>
                            <?php if($user->isAdmin() || $user->isManager()):?>
                                <th class="sort">Partner</th>
                            <?php endif; ?>
                            <th class="sort">Part Number</th>
                            <th class="sort">Description</th>
                            <th class="sort" width="100">Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listUsage)): ?>
                            <?php foreach ($listUsage as $usage): ?>
                                <tr class="goods">
                                    <?php if($user->isAdmin()  || $user->isManager()):?>
                                        <td><?=$usage['SERVICE_PROVIDE_NAME']?></td>
                                    <?php endif; ?>
                                    <td><?=$usage['Item_Product_ID']?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $usage['Item_Product_Desc'])?></td>
                                    <td><?=$usage['total']?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="thank_you_page">
                        <h3>No results</h3>
                    </div>
                <?php endif; ?>
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
            <?php if($user->isAdmin() || $user->isManager()):?>
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
            <?php elseif ($user->isPartner()):?>
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


