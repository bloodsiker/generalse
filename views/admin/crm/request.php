<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Request</h1>
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
                            <div class="medium-5 small-12 columns">
                                <?php if (AdminBase::checkDenied('crm.request.send', 'view')): ?>
                                    <button class="button primary tool" id="add-request-button"><i class="fi-plus"></i> Request</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.request.import', 'view')): ?>
                                    <button data-open="add-request-import-modal" class="button primary tool"><i class="fi-plus"></i> Import request</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.request.export', 'view')): ?>
                                    <button class="button primary tool" onclick="tableToExcel('goods_data', 'Request Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>
                                <?php endif;?>

                                <?php if (AdminBase::checkDenied('crm.request.price', 'view')): ?>
                                    <button class="button primary tool" id="price-button"><i class="fi-plus"></i> Price</button>
                                <?php endif;?>
                            </div>
                            <div class="medium-4  small-12 columns">
                                <form action="/adm/crm/request/" method="get" id="kpi" class="form hide">
                                    <div class="row align-bottom">
                                        <div class="medium-4 text-left small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                            <input type="text" id="date-start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" name="start" required>
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                            <input type="text" id="date-end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>" name="end">
                                        </div>
                                        <div class="medium-4 small-12 columns">
                                            <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="medium-3 small-12 columns form">
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <?php if(isset($request_message) && $request_message != ''):?>
                    <div class="alert-success" style="margin: 0px auto 10px;"><?=$request_message?></div>
                <?php endif;?>
                <?php if($user->role == 'partner' && $user->name_partner != 'GS Electrolux'):?>
                <table id="goods_data">
                    <thead>
                    <tr>
                        <th class="sort">Part Number</th>
                        <th class="sort">Part Description</th>
                        <th class="sort">SO Number</th>
                        <th class="sort">Status</th>
                        <th class="sort">Date create</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listCheckOrders)):?>
                        <?php foreach($listCheckOrders as $order):?>
                            <tr class="goods <?= (Functions::calcDiffSec($order['date_create']) < 120) ? 'check_lenovo_ok' : ''?>">
                                <td><?= $order['part_number']?></td>
                                <td><?= $order['part_description']?></td>
                                <td><?= $order['so_number']?></td>
                                <td><?= $order['status_name']?></td>
                                <td><?= Functions::formatDate($order['date_create'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
                <?php elseif($user->role == 'administrator'
                    || $user->role == 'administrator-fin'
                    || $user->role == 'manager'
                    || $user->name_partner == 'GS Electrolux'):?>
                    <table id="goods_data">
                        <thead>
                        <tr>
                            <th class="sort">Partner</th>
                            <th class="sort">Part Number</th>
                            <th class="sort">Part Description</th>
                            <th class="sort">SO Number</th>
                            <th class="sort">Status</th>
                            <th class="sort">Date create</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($listCheckOrders)):?>
                            <?php foreach($listCheckOrders as $order):?>
                                <tr class="goods <?= (Functions::calcDiffSec($order['date_create']) < 120) ? 'check_lenovo_ok' : ''?>">
                                    <td><?= $order['name_partner']?></td>
                                    <td><?= $order['part_number']?></td>
                                    <td><?= $order['part_description']?></td>
                                    <td><?= $order['so_number']?></td>
                                    <td><?= $order['status_name']?></td>
                                    <td><?= Functions::formatDate($order['date_create'])?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


<div class="reveal" id="add-request-modal" data-reveal>
    <form action="" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New request</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>SO Number</label>
                <input type="text" class="required" name="so_number" required>
            </div>
            <input type="hidden" name="add_request" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="price-modal" data-reveal>
    <form action="" id="price-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Price</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Price </label>
                <input type="text" class="required" name="price" disabled>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="add-request-import-modal" data-reveal>
    <form action="/adm/crm/request/import" id="add-request-import-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import request</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_file_form" class="button primary">Attach</label>
                                <input type="file" id="upload_file_form" class="show-for-sr" name="excel_file" required>
                            </div>

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                            href="/upload/attach_request/request_import.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="import_request" value="true">
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
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


<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
