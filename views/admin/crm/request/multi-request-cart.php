<?php $multiRequestCart = \Josantonius\Session\Session::get('multi_request_cart')?>
<?php if(sizeof($multiRequestCart) > 0):?>
    <?php if(is_array($multiRequestCart)):?>
    <?php //var_dump($multiRequestCart)?>
    <?php //\Josantonius\Session\Session::destroy('multi_request_cart')?>
        <?php foreach ($multiRequestCart as $key => $cart):?>
            <table class="umbrella-table">
                <tr>
                    <td width="30%">Part Number</td>
                    <td><?= $cart['part_number']?></td>
                </tr>
                <tr class="text-<?= $cart['part_quantity'] <= $cart['stock_count'] ? 'green' : 'red'?>">
                    <td width="30%">Quantity</td>
                    <td><?= $cart['part_quantity']?></td>
                </tr>
                <tr class="text-green">
                    <td width="30%">Quantity in stock</td>
                    <td><?= $cart['stock_count']?></td>
                </tr>
                <tr>
                    <td width="30%">Part description</td>
                    <td><?= \Umbrella\components\Decoder::strToUtf($cart['goods_name'])?></td>
                </tr>
                <tr>
                    <td width="30%">Stocks</td>
                    <td><?= $cart['stock_name']?></td>
                </tr>
                <tr>
                    <td width="30%">Period of the request(days)</td>
                    <td><?= $cart['period']?></td>
                </tr>
                <tr>
                    <td width="30%">Note</td>
                    <td><?= $cart['note1']?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right">
                        <button data-trash-id="<?= $key?>" class="delete delete-request-with-cart"><i class="fi fi-trash"></i> Delete</button>
                    </td>
                </tr>
            </table>
        <?php endforeach;?>

        <div class="row align-top">
            <div class="medium-6 small-6 columns">
                <button type="submit" id="clear-multi-cart" class="button primary">Clear cart</button>
            </div>
            <div class="medium-6 small-6 columns">
                <button type="submit" id="send-multi-cart" class="button primary">Send</button>
            </div>
        </div>

    <?php endif;?>
<?php else:?>
<table class="umbrella-table">
    <tr>
        <td>The cart is empty</td>
    </tr>
</table>
<?php endif;?>
