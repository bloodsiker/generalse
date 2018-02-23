<div class="reveal" id="add-checkout-modal" data-reveal>
    <form action="" id="add-checkout-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New checkout</h3>
            </div>
            <?php if($user->isAdmin()):?>

                <div class="medium-12 small-12 columns">
                    <label>Partner</label>
                    <select name="id_partner" id="id_partner" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php if(is_array($partnerList)):?>
                            <?php foreach($partnerList as $partner):?>
                                <option value="<?=$partner['id_user']?>"><?=$partner['name_partner']?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>

            <?php elseif ($user->isManager() || $user->isPartner()):?>

                <div class="medium-12 small-12 columns">
                    <label><i class="fi-list"></i> Partner</label>
                    <select name='id_partner' id='id_partner' class='required' required>
                        <?php $user->renderSelectControlUsers($user->getId());?>
                    </select>
                </div>

            <?php endif;?>

            <div class="medium-12 small-12 columns">
                <label>Stock</label>
                <select name="stock" id="stock" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($user->renderSelectStocks($user->getId(), 'order') as $stock):?>
                        <option value="<?= $stock?>"><?= $stock?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Service Order</label>
                <input type="text" class="required" name="service_order" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <input type="text" class="required" name="part_number" required>
            </div>
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
                <label>Quantity <span style="color: #4CAF50;" class="quantity-product"></span></label>
                <input type="number" value="1" min="1" max="50" class="required" name="quantity" required>
            </div>
            <div class="medium-12 small-12 columns hide">
                <label>Note</label>
                <textarea rows="3" name=""></textarea>
            </div>

            <?php if(is_array($delivery_address) && !empty($delivery_address)):?>
                <div class="medium-12 small-12 columns">
                    <label>Delivery address</label>
                    <select name="note" id="note" class="required" required>
                        <option value="" selected disabled>none</option>
                        <?php foreach ($delivery_address as $address):?>
                            <option value="<?= $address['address']?>" <?= $address['is_default'] == 1 ? 'selected' : null ?>><?= $address['address']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>