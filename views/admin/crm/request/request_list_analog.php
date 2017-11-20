<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>List of analogue part numbers</h1>
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
                            <div class="medium-9 small-12 columns">

                                <a href="/adm/crm/request" class="button primary tool"><i class="fi-arrow-left"></i> Back to request</a>

                                <button class="button primary tool" data-open="add-part-analog"><i class="fi-plus"></i> Add analog</button>

                                <button class="button primary tool" data-open="add-part-not-available"><i class="fi-plus"></i> Add not available</button>

                                <button class="button primary tool" data-open="import-analog-modal"><i class="fi-plus"></i> Import analog</button>

                                <button class="button primary tool" onclick="tableToExcel('goods_data', 'Request Table')" style="width: inherit;"><i class="fi-page-export"></i> Export to Excel</button>

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

                <table class="umbrella-table" id="goods_data">
                    <thead>
                    <tr>
                        <th>Part number</th>
                        <th>Part analog</th>
                        <th width="50"></th>
                        <th width="50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($listPartAnalog)):?>
                        <?php foreach($listPartAnalog as $part):?>
                            <tr class="goods" data-id="<?= $part['id']?>">
                                <td><span class="r_part"><?= $part['part_number']?></span></td>
                                <td><span class="r_analog"><?= $part['part_analog']?></span></td>
                                <td><a href="" class="button no-margin small edit-analog"><i class="fi-pencil"></i></a></td>
                                <td><a href="" class="button no-margin small delete-analog hide"><i class="fi-x"></i></a></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>




<div class="reveal" id="edit-analog" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Edit part number or analog</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part number </label>
                        <input type="text" id="r_pn" class="required" required autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part analog </label>
                        <input type="text" id="r_analog" class="required" required autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="button" id="send-pn-analog" class="button primary">Edit</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal" id="add-part-analog" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Add part analog</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part number </label>
                        <input type="text" name="r_part_number" class="required" required autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part analog </label>
                        <input type="text" name="r_part_analog" class="required" required autocomplete="off">
                    </div>
                </div>
            </div>
            <input type="hidden" name="add-analog" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Add</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal" id="add-part-not-available" data-reveal>
    <form action="#" method="post" class="form" novalidate="">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Add part not available</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Part number </label>
                        <input type="text" name="r_part_number" class="required" required autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Comment</label>
                        <input type="text" name="comment" autocomplete="off">
                    </div>
                </div>
            </div>
            <input type="hidden" name="add-not-available" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Add</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="import-analog-modal" data-reveal>
    <form action="" id="import-analog-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <div class="medium-12 small-12 columns">
                        <label>Type part number</label>
                        <select name="type_part" class="required" required>
                            <option value="analog">Analog</option>
                            <option value="available">Not available</option>
                        </select>
                    </div>

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
                                            href="/upload/attach_request/part_analog.xlsx" style="color: #2ba6cb"
                                            download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="import-excel-analog" value="true">
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
