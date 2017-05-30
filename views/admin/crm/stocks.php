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
                    <div class="medium-6 small-12 columns">
                        <?php if($user->role == 'partner'):?>
                        <form action="/adm/crm/stocks/" method="get" class="form">
                            <label>Stocks</label>
                            <select name="stock" onchange="this.form.submit()" id="stock" class="required" required>
                                <option value="" <?=(isset($_GET['stock']) && $_GET['stock'] == '') ? 'selected' : ''?>></option>
                                <?php foreach ($user->renderSelectStocks($user->id_user, 'stocks') as $stock):?>
                                    <option value="<?= $stock?>" <?=(isset($_GET['stock']) && $_GET['stock'] == $stock) ? 'selected' : ''?>><?= $stock?></option>
                                <?php endforeach;?>
                            </select>
                        </form>
                        <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                            <form action="/adm/crm/stocks/" method="get" class="form">
                                <div class="row align-bottom">
                                    <div class="medium-4 small-12 columns">
                                        <label><i class="fi-list"></i> Partner
                                            <select name="id_partner">
                                                <option value="all">All partners</option>
                                                <?php if(is_array($partnerList)):?>
                                                    <?php foreach($partnerList as $partner):?>
                                                        <option <?php echo (isset($id_partner) && $id_partner == $partner['id_user']) ? 'selected' : '' ?> value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="medium-4 small-12 columns">
                                    <label>Stocks</label>
                                    <select name="stock" id="stock" class="required" required>
                                        <option value="all" <?=(isset($_GET['stock']) && $_GET['stock'] == 'all') ? 'selected' : ''?>>All</option>
                                        <?php foreach ($user->renderSelectStocks($user->id_user, 'stocks') as $stock):?>
                                            <option value="<?= $stock?>" <?=(isset($_GET['stock']) && $_GET['stock'] == $stock) ? 'selected' : ''?>><?= $stock?></option>
                                        <?php endforeach;?>
                                    </select>
                                    </div>
                                    <div class="medium-4 small-12 columns">
                                        <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                    </div>
                                </div>
                            </form>
                        <?php endif;?>
                    </div>
                    <div class="medium-3 small-12 columns">
                        <?php if(isset($allGoodsByPartner) && count($allGoodsByPartner) > 0):?>
                            <button class="button primary" onclick="tableToExcel('goods_data', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
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
                <table id="goods_data">
					<?php if(isset($_GET['stock']) && $_GET['stock'] == 'all'):?>
                        <caption>Stocks is <span class="text-green"><?=count($allGoodsByPartner)?></span> units</caption>
                    <?php else:?>
                        <caption>Stock <span class="text-green"><?=(isset($_GET['stock']) ? $_GET['stock'] : '')?></span> is <span class="text-green"><?=(isset($allGoodsByPartner)) ? count($allGoodsByPartner) : 0?></span>  units</caption>
                    <?php endif;?>

                    <?php if($user->role == 'partner'):?>
                        <thead>
                        <tr>
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
                    <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
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
                    <?php endif;?>

                </table>
            </div>
        </div>
    </div>
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
