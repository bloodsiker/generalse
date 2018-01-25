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
                            <button class="button primary tool" onclick="tableToExcel('table-to-excel', 'W3C Example Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                        <?php endif;?>
                    </div>

                     <div class="medium-3 small-12 columns form">
                         <form action="/adm/crm/stocks/s/" method="get" class="form" data-abide novalidate>
                             <input type="text" class="required search-input" placeholder="Search..." name="search" required>
                             <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                         </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <table class="umbrella-table" id="table-to-excel">
					<?php if(isset($_POST['stock']) && count($_POST['stock']) > 1):?>
                        <caption>Stocks is <span class="text-green">
                                <?=implode(', ', \Umbrella\models\crm\Stocks::replaceArrayNameStockInResultTable($_POST['stock'], $user->getRole()))?>
                            </span> is <span class="text-green"><?=(isset($allGoodsByPartner)) ? count($allGoodsByPartner) : 0?></span>  units
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
                        <th>Partner</th>
                        <th>Part Number</th>
                        <th class="sort">Description</th>
                        <th class="sort">Stock name</th>
                        <th>Quantity</th>
                        <th>
                            Sub type
                            <select style="font-size: 12px;height: 28px;padding: 2px 20px" id="filterSuptype" onchange="filterSubtype(event)">
                                <option value="">Not selected</option>
                            </select>
                        </th>
                        <th>Serial Number</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($allGoodsByPartner)): ?>
                        <?php foreach ($allGoodsByPartner as $goods): ?>
                            <tr class="goods"
                                ondblclick="getPricesProduct(
                                        <?= $goods['goods_name_id']?>,
                                        '<?= $goods['part_number']?>',
                                        '<?= $goods['goods_name']?>',
                                        '<?= $goods['site_account_id']?>')">
                                <td><?= $goods['site_client_name']?></td>
                                <td><?= $goods['part_number']?></td>
                                <td><?= $goods['goods_name']?></td>
                                <td><?= $goods['stock_name']?></td>
                                <td><?=$goods['quantity']?></td>
                                <td class="subtype_td"><?= $goods['subtype_name']?></td>
                                <td><?= $goods['serial_number']?></td>
                                <td><?= $goods['price']?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<div class="reveal large" id="stock-filter" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Stock filter</h3>
        </div>
        <form action="/adm/crm/stocks/" method="POST" id="stock_filter">
            <div class="row align-top" style="margin-left: 0; margin-right: 0;">
                <div class="medium-8 small-12 columns">

                    <h4 style="color: #fff">Stocks</h4>
                    <div class="row align-bottom" style="background: #323e48; padding: 10px 0; margin-right: 0;">
                        <?php if(is_array($list_stock)):?>
                            <?php foreach($list_stock as $stock):?>
                                <div class="medium-4 small-4 columns">
                                    <?php $checked = Umbrella\models\crm\Stocks::checkStocks(isset($_POST['stock']) ? $_POST['stock'] : [], $stock)?>
                                    <input type="checkbox" <?=($checked ? 'checked' : '')?> onclick="checkColor(event)" id="<?=$stock ?>" name="stock[]" value="<?=$stock ?>">
                                    <label for="<?=$stock ?>" style="color: <?= ($checked ? 'green' : '')?>;"><?=$stock ?></label><br>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="medium-4 small-12 columns">

                    <h4 style="color: #fff">Sub type</h4>
                    <div class="row align-bottom" style="background: #323e48; padding: 10px 0; margin-bottom: 10px">

                            <div id="container-sub-type">
                                <?php if(is_array($listSubType)):?>
                                    <?php foreach ($listSubType as $type):?>
                                        <div>
                                            <input id="type-<?= $type['id']?>" type="checkbox" onclick="checkColor(event)" name="sub_type[]" value="<?= $type['shortName']?>">
                                            <label for="type-<?= $type['id']?>"><?= $type['shortName']?></label>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>

                    </div>
                </div>
            </div>

            <div class="medium-12 small-12 columns">
                <h4 style="color: #fff">Partners</h4>
                <?php if($user->role == 'administrator' || $user->role == 'fin-administrator'):?>
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
                                                    <?php $checked = Umbrella\models\crm\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
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
                                                <?php $checked = Umbrella\models\crm\Stocks::checkUser(isset($_POST['id_partner']) ? $_POST['id_partner'] : [], $partner['id_user'])?>
                                                <?php $checkUser = $user->getId() == $partner['id_user'] ? true : false?>
                                                <input type="checkbox" <?= ($checked || $checkUser) ? 'checked' : '' ?> onclick="checkColor(event)" id="id-<?=$partner['id_user'] ?>" name="id_partner[]" value="<?=$partner['id_user'] ?>">
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
                        <button type="submit" id="apply-stock-filter" class="button primary"><i class="fi-filter"></i> Apply</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php if($user->isAdmin() || $user->isManager()): ?>
    <div class="reveal" id="show-prices-modal" data-reveal>
        <div class="row align-top">
            <div class="medium-12 small-12 columns">
                <h3>Prices</h3>
            </div>
            <div class="medium-12 small-12 columns" id="container-prices">

            </div>
        </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif;  ?>


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
