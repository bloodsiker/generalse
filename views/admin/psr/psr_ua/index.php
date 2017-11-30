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
                            <div class="medium-9 small-12 columns">
                                <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.create', 'view')): ?>
                                    <button class="button primary tool" id="add-psr"><i class="fi-plus"></i> Create</button>
                                <?php endif;?>
                            </div>
                            <div class="medium-3 small-12 columns">
                                <form action="/adm/psr/s/" method="get" class="form" data-abide novalidate>
                                    <input type="text" class="required search-input" placeholder="Search..." name="search" required>
                                    <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
             <div class="row">
                 <div class="medium-12 small-12 columns hide" style="text-align: center">
                     <div class="green" style="margin: 0px auto 10px;">Извините, на данный момент зарегистрировать ПСР не возможно. Ведутся технические работы</div>
                 </div>
                 <?php if(isset($message_success) && !empty($message_success)):?>
                     <div class="medium-12 small-12 columns" style="text-align: center">
                         <div class="<?= $class ?>" style="margin: 0px auto 10px;"><?= $message_success ?></div>
                     </div>
                 <?php endif;?>
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
                             <th>Notes</th>
                             <th>Attach</th>
                             <th>Status</th>
                             <th>Declaration number</th>
                             <?php if($user->isAdmin()
                                 || $user->isManager()):?>
                                <th>SO number</th>
                             <?php endif;?>
                             <td>Date</td>
                         </tr>
                         </thead>
                         <tbody>
                         <?php //$listPsr = [];?>
                         <?php if(is_array($listPsr)):?>
                             <?php foreach ($listPsr as $psr):?>
                                 <tr class="goods" data-id="<?= $psr['id']?>">
                                     <td><?= $psr['id']?></td>
                                     <td><?= $psr['site_client_name']?></td>
                                     <td><?= $psr['serial_number']?></td>
                                     <td><?= $psr['part_number']?></td>
                                     <td><?= $psr['device_name']?></td>
                                     <td><?= $psr['manufacture_date']?></td>
                                     <td><?= $psr['purchase_date']?></td>
                                     <td><?= $psr['defect_description']?></td>
                                     <td><?= $psr['device_condition']?></td>
                                     <td><?= $psr['complectation']?></td>
                                     <td class="text-center">
                                         <?php if($psr['note'] != ' ' && $psr['note'] != null):?>
                                             <i class="fi-info has-tip [tip-top]" style="font-size: 16px;"
                                                data-tooltip aria-haspopup="true"
                                                data-show-on="small"
                                                data-click-open="true"
                                                title="<?= $psr['note']?>"></i>
                                         <?php endif;?>
                                     </td>
                                     <td class="text-center <?= $psr['count_file'] > 0 ? 'blue' : 'red' ?>">
                                         <button data-open="open-upload-psr" data-psr-id="<?= $psr['id'] ?>">
                                             <i class="fa fa-paperclip" aria-hidden="true"></i>
                                         </button>
                                     </td>
                                     <td class="edit-psr-status <?= \Umbrella\models\psr\Psr::getStatusRequest($psr['status_name'])?>">
                                         <?= $psr['status_name']?>
                                     </td>
                                     <td style="padding: 0!important;" class="block-container">
                                         <table style="margin-bottom: 0">
                                             <tr>
                                                 <td style="border-width: 0 0 1px 0">
                                                     <span class="psr-dec-number"><?= $psr['declaration_number']?></span>
                                                     <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.add_declaration', 'view')): ?>
                                                        <a href="" class="button button-hover edit-dec delete"><i class="fi-pencil"></i></a>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <td style="border-width: 0 0 0 0">
                                                     <span class="psr-dec-number-return"><?= $psr['declaration_number_return']?></span>
                                                     <?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.add_return_declaration', 'view')): ?>
                                                        <a href="" class="button button-hover edit-dec-return delete"><i class="fi-pencil"></i></a>
                                                     <?php endif?>
                                                 </td>
                                             </tr>
                                         </table>
                                     </td>
                                     <?php if($user->isAdmin()
                                     || $user->isManager()):?>
                                         <td class="order-tr-so"><?= $psr['so']?></td>
                                     <?php endif;?>
                                     <td><?= \Carbon\Carbon::parse($psr['created_at'])->format('Y-m-d')?></td>
                                 </tr>
                             <?php endforeach;?>
                         </tbody>
                     </table>
                 <?php endif;?>

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

<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.add_declaration', 'view')): ?>
    <div class="reveal" id="edit-dec" data-reveal>
        <form action="#" method="post" class="form" novalidate="">
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Edit declaration number</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label>Declaration number</label>
                            <input type="text" id="psr_dec" name="declaration_number" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="medium-12 small-12 columns">
                    <button type="button" id="send-psr-dec" class="button primary">Edit</button>
                </div>
            </div>
        </form>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>


<?php if (Umbrella\app\AdminBase::checkDenied('adm.psr.add_return_declaration', 'view')): ?>
    <div class="reveal" id="edit-dec-return" data-reveal>
        <form action="#" method="post" class="form" novalidate="">
            <div class="row align-bottom">
                <div class="medium-12 small-12 columns">
                    <h3>Edit return declaration number</h3>
                </div>
                <div class="medium-12 small-12 columns">
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <label>Declaration number</label>
                            <input type="text" id="psr_dec_return" name="declaration_number" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="medium-12 small-12 columns">
                    <button type="button" id="send-psr-dec-return" class="button primary">Edit</button>
                </div>
            </div>
        </form>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
