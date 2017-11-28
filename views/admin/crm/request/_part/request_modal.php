<div class="reveal" id="add-request-modal" data-reveal>
    <form action="" id="add-request-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>New request</h3>
            </div>
            <div class="medium-10 small-10 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <span style="color: orange;" class="pn-analog"></span>
                <input type="text" class="required" name="part_number" onkeyup="checkCurrPartNumber(this)" autocomplete="off" required>
            </div>

            <div class="medium-2 small-2 columns">
                <label>Count</label>
                <input type="text" class="required" name="part_quantity" value="1" onkeyup="validCount(this)" autocomplete="off" required>
            </div>

            <div class="medium-6 small-6 columns">
                <label>Price</label>
                <input type="text" name="price" disabled>
            </div>

            <div class="medium-6 small-6 columns group-analog hide">
                <label>Price analog</label>
                <input type="text" name="analog-price" disabled>
            </div>

            <div class="medium-12 small-12 columns">
                <label>SO Number/Note</label>
                <input type="text" name="so_number" autocomplete="off">
            </div>


            <div class="medium-12 small-12 columns">
                <label>Part description RUS</label>
                <input type="text" name="pn_name_rus" autocomplete="off">
            </div>


            <div class="medium-12 small-12 columns">
                <label>Type</label>
                <select name="order_type_id" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php foreach ($order_type as $type):?>
                        <option value="<?= $type['id']?>"><?= iconv('WINDOWS-1251', 'UTF-8', $type['name'])?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Delivery address</label>
                <select name="note" class="required" required>
                    <option value="" selected disabled>none</option>
                    <?php if(is_array($delivery_address)):?>
                        <?php foreach ($delivery_address as $address):?>
                            <option value="<?= $address?>"><?= $address?></option>
                        <?php endforeach;?>
                    <?php endif; ?>
                    <option value="other_address">Write another address</option>
                </select>
                <input type="hidden" name="your_address" placeholder="Enter your address">
            </div>

            <div class="medium-12 small-12 columns">
                <label>Flash on PNC</label>
                <input type="text" name="note1" autocomplete="off">
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