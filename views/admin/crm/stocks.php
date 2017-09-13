<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Stocks</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">

                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>

                        </ul>
                    </div>
                </div>
                <div class="row align-justify align-bottom">
                    <div class="medium-9 small-12 columns">
                        <button data-open="stock-filter" class="button primary tool"><i class="fi-filter"></i>
                            Filter
                        </button>

                        <?php if(isset($allGoodsByPartner) && count($allGoodsByPartner) > 0):?>
                            <button class="button primary tool" onclick="tableToExcel('goods_data', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                        <?php endif;?>
                    </div>

                     <div class="medium-3 small-12 columns form">
                        <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <table class="umbrella-table" id="goods_data">
					<?php if(isset($_POST['stock']) && count($_POST['stock']) > 1):?>
                        <caption>Stocks is <span class="text-green">
                                <?=implode(', ', $_POST['stock'])?></span> is <span class="text-green"><?=(isset($allGoodsByPartner)) ? count($allGoodsByPartner) : 0?></span>  units
                        </caption>
                    <?php elseif(isset($_POST['stock']) && count($_POST['stock']) == 1):?>
                        <caption>Stocks is <span class="text-green">
                                <?=$_POST['stock'][0] ?></span> is <span class="text-green"><?=(isset($allGoodsByPartner)) ? count($allGoodsByPartner) : 0?></span>  units
                        </caption>
                    <?php else:?>
                        <caption>Stock <span class="text-green"><?=(isset($_GET['stock']) ? $_GET['stock'] : '')?></span> is <span class="text-green"><?=(isset($allGoodsByPartner)) ? count($allGoodsByPartner) : 0?></span>  units</caption>
                    <?php endif;?>

                    <thead>
                    <tr>
                        <th class="sort">Partner</th>
                        <th class="sort">Part Number</th>
                        <th class="sort">Description</th>
                        <th class="sort">Stock name</th>
                        <th class="sort">Quantity</th>
                        <th class="sort">Sub type</th>
                        <th class="sort">Serial Number</th>
                        <th class="sort">Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($allGoodsByPartner)): ?>
                        <?php foreach ($allGoodsByPartner as $goods): ?>
                            <tr class="goods">
                                <td><?=$goods['site_client_name']?></td>
                                <td><?=$goods['part_number']?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $goods['goods_name'])?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $goods['stock_name'])?></td>
                                <td><?=$goods['quantity']?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $goods['subtype_name'])?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $goods['serial_number'])?></td>
                                <td><?=round($goods['price'], 2)?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<div class="reveal small" id="stock-filter" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Stock filter</h3>
        </div>
        <div class="medium-12 small-12 columns">
            <form action="" method="POST" id="stock_filter">

                <h4 style="color: #fff">Stocks</h4>
                <div class="row align-bottom" style="background: #323e48; padding-top: 10px; margin-bottom: 10px">
                    <?php if(is_array($new_stock)):?>
                        <?php foreach($new_stock as $new_arr):?>
                            <div class="medium-4 small-4 columns">
                                <?php foreach($new_arr as $stock):?>
                                    <?php $checked = Umbrella\models\Stocks::checkStocks(isset($_POST['stock']) ? $_POST['stock'] : [], $stock)?>
                                    <input type="checkbox" <?=($checked ? 'checked' : '')?> onclick="checkColor(event)" id="<?=$stock ?>" name="stock[]" value="<?=$stock ?>">
                                    <label for="<?=$stock ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$stock ?></label><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <h4 style="color: #fff">Partners</h4>
                <div class="row align-bottom" style="background: #323e48; padding-top: 10px">
                    <?php if(is_array($new_partner)):?>
                        <?php foreach($new_partner as $new_arr):?>
                            <div class="medium-4 small-4 columns">
                                <?php foreach($new_arr as $partner):?>
                                    <?php $checked = Umbrella\models\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                    <input type="checkbox" <?= ($checked ? 'checked' : '')?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
                                    <label for="id-<?=$partner['id_user'] ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$partner['name_partner'] ?></label><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="row align-bottom" style="padding-top: 10px; margin-top: 10px">
                    <div class="medium-3 small-3 medium-offset-9 columns">
                        <button type="submit" id="apply-stock-filter" class="button primary"><i class="fi-filter"></i> Apply</button>
                    </div>
                </div>
            </form>
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
