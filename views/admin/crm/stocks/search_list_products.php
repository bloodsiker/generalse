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
                        <a href="/adm/crm/stocks/" class="button primary tool">Stocks</a>
                    </div>

                     <div class="medium-3 small-12 columns form">
                         <form action="/adm/crm/stocks/list_products/s/" method="get" class="form" data-abide novalidate>
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
                    <caption>
                        Search result for <?= $search = iconv('WINDOWS-1251', 'UTF-8', $search)?>
                        <span id="count_refund" class="text-green">
                            (<?php if (isset($listProduct)) echo count($listProduct) ?>)
                        </span>
                    </caption>
                    <thead>
                    <tr>
                        <th>Part Number</th>
                        <th class="sort">Description</th>
                        <th>
                            Sub type
                            <select style="font-size: 12px;height: 28px;padding: 2px 20px" id="filterSuptype" onchange="filterSubtype(event)">
                                <option value="">Not selected</option>
                            </select>
                        </th>
                        <th>Classifier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($listProduct)): ?>
                        <?php foreach ($listProduct as $goods): ?>
                            <tr class="goods">
                                <td><?= \Umbrella\components\Functions::replaceSearchResultUtf($search, $goods['PartNumber'])?></td>
                                <td><?= \Umbrella\components\Functions::replaceSearchResultUtf($search, $goods['mName'])?></td>
                                <td class="subtype_td"><?= $goods['subType']?></td>
                                <td><?= $goods['classifier']?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
