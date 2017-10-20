<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>PSR UA</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/psr_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-10 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.create', 'view')): ?>
                                    <button class="button primary tool" id="add-psr"><i class="fi-plus"></i> Create</button>
                                <?php endif;?>

                                <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.add_declaration', 'view')): ?>
                                    <button class="button primary tool hide" id="add-psr-dec"><i class="fi-plus"></i> Add declaration number</button>
                                <?php endif;?>
                            </div>
                            <div class="medium-2 small-12 columns">
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

                 <table class="umbrella-table">
                     <thead>
                     <tr>
                         <th>ID</th>
                         <th>Partner</th>
                         <th>SN number</th>
                         <th>MTM</th>
                         <th>Device</th>
                         <th>Manufacture Date</th>
                         <th>Purchase Date</th>
                         <th>Defect description</th>
                         <th>Device condition</th>
                         <th>Complectation</th>
                     </tr>
                     </thead>
                     <tbody>
                     <?php if(is_array($listPsr)):?>
                         <?php foreach ($listPsr as $psr):?>
                             <tr class="goods" data-id="<?= $psr['id']?>">
                                 <td><?= $psr['id']?></td>
                                 <td><?= $psr['name_partner']?></td>
                                 <td><?= $psr['serial_number']?></td>
                                 <td><?= $psr['part_number']?></td>
                                 <td><?= $psr['device_name']?></td>
                                 <td><?= $psr['manufacture_date']?></td>
                                 <td><?= $psr['purchase_date']?></td>
                                 <td><?= $psr['defect_description']?></td>
                                 <td><?= $psr['device_condition']?></td>
                                 <td><?= $psr['complectation']?></td>

                             </tr>
                         <?php endforeach;?>
                     <?php endif;?>
                     </tbody>
                 </table>
             </div>
          </div>
    </div>
</div>

<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.create', 'view')): ?>
    <div class="reveal" id="add-psr-modal" data-reveal>
        <form action="#" id="add-psr-form" method="post" class="form" data-abide novalidate>
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Create PSR</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <div class="row align-bottom ">
                                <div class="medium-12 small-12 columns">
                                    <label>Serial Number</label>
                                    <input type="text" class="required" name="serial_number" required>
                                </div>
                                <div class="medium-12 small-12 columns">
                                    <label>MTM <span style="color: #4CAF50;" class="name-product"></span></label>
                                    <input type="text" class="required" name="mtm" required>
                                </div>
                                <input type="hidden" name="device_name" value="">
                                <div class="medium-5 small-12 columns">
                                    <label>Manufacture Date</label>
                                    <input type="text" class="required date" name="manufacture_date" required>
                                </div>
                                <div class="medium-5 small-12 columns">
                                    <label>Purchase Date</label>
                                    <input type="text" class="required date" name="purchase_date" required>
                                </div>
                                <div class="medium-2 small-12 columns">
                                    <label>Days</label>
                                    <input type="text" name="Days">
                                </div>
                                <div class="medium-12 small-12 columns">
                                    <span class="error-date" style="color: #ff635a; font-size: 14px">Ремонт невозможно зарегистрировать как ПСР, обратитесь к менеджеру</span>
                                </div>

                                <div class="medium-12 small-12 columns">
                                    <label>Defect description</label>
                                    <textarea name="defect_description" cols="30" rows="2" class="required" required></textarea>
                                </div>

                                <div class="medium-12 small-12 columns">
                                    <label>Device condition</label>
                                    <input type="text" class="required" name="device_condition" required>
                                </div>

                                <div class="medium-12 small-12 columns">
                                    <label>Complectation</label>
                                    <input type="text" class="required" name="complectation" required>
                                </div>

                                <div class="medium-12 small-12 columns">
                                    <label>Note</label>
                                    <textarea name="note" cols="30" rows="2"></textarea>
                                </div>

                                <div class="medium-12 small-12 columns">
                                    <label>Declaration number</label>
                                    <input type="text" name="declaration_number">
                                </div>

                                <input type="hidden" name="add_psr" value="true">
                            </div>
                        </div>


                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="medium-12 small-12 columns">
                                    <button type="submit" class="button primary">Send</button>
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
<?php endif; ?>




<div class="reveal" id="open-upload-psr" data-reveal>
    <form action="" id="psr-upload" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Upload warranty card</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <div class="medium-12 small-12 columns">
                        <div class="container-upload-file">

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_new_price" class="button primary">Attach</label>
                                <input type="file" id="upload_new_price" class="show-for-sr" name="attach_psr" required multiple>
                            </div>
                        </div>
                    </div>


                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <input type="hidden" name="psr_id" value="">
                            </div>
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Upload File</button>
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





<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
