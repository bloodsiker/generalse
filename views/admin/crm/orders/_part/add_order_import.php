<div class="reveal" id="add-order-import-modal" data-reveal>
    <form action="/adm/crm/orders" id="orders-excel-send" method="post" enctype="multipart/form-data" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import orders</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <?php if($user->isAdmin()):?>

                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner</label>
                            <select name="id_partner" id="id_partner_one" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php if(is_array($partnerList)):?>
                                    <?php foreach($partnerList as $partner):?>
                                        <option <?php echo (isset($id_partner) && $id_partner == $partner['id_user']) ? 'selected' : '' ?> value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>

                    <?php elseif ($user->isManager() || $user->isPartner()):?>

                        <div class="medium-12 small-12 columns">
                            <label><i class="fi-list"></i> Partner</label>
                            <select name="id_partner" id="id_partner_one" class="required" required>
                                <?php $user->renderSelectControlUsers($user->getId());?>
                            </select>
                        </div>

                    <?php endif;?>


                    <div class="medium-12 small-12 columns">
                        <label><i class="fi-list"></i> Stock
                            <select name="stock" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php foreach ($user->renderSelectStocks($user->getId(), 'order') as $stock):?>
                                    <option value="<?= $stock?>"><?= $stock?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>

                    <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                        <div class="medium-12 small-12 columns">
                            <label>Delivery address</label>
                            <select name="notes" id="notes" class="required" required>
                                <option value="" selected disabled>none</option>
                                <?php foreach ($delivery_address as $address):?>
                                    <option value="<?= $address['address']?>" <?= $address['is_default'] == 1 ? 'selected' : null ?>><?= $address['address']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="medium-12 small-12 columns">
                        <label>Type</label>
                        <select name="order_type_id" class="required" required>
                            <option value="" selected disabled>none</option>
                            <?php foreach ($order_type as $type):?>
                                <option value="<?= $type['id']?>"><?= $type['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>


                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="exampleFileUpload" class="button primary">Attach</label>
                                <input type="file" id="exampleFileUpload" class="show-for-sr" name="excel_file" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="send_excel_file" value="true">


                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                        href="/upload/attach_order/orders_import.xls" style="color: #2ba6cb"
                                        download="">download</a> a template file to import
                                </div>
                            </div>
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