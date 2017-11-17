<?php
$multiRequestCart = \Josantonius\Session\Session::get('multi_request_cart');
$totalCount = 0;
if(is_array($multiRequestCart)){
    foreach ($multiRequestCart as $product){
        $totalCount += $product['part_quantity'] * $product['price'];
    }
}

?>

<?php if(sizeof($multiRequestCart) > 0):?>
    <table class="umbrella-table">
        <tr>
            <td>Общая стоимость:</td>
            <td class="text-right"><?= number_format($totalCount, 2, '.', ' ')?> <?=$user->getInfoUserGM()['ShortName']?></td>
        </tr>
    </table>

    <?php if(is_array($multiRequestCart)):?>
    <?php //var_dump($multiRequestCart)?>
    <?php //\Josantonius\Session\Session::destroy('multi_request_cart')?>
        <?php foreach ($multiRequestCart as $key => $cart):?>
            <table class="umbrella-table">
                <tr>
                    <td width="30%">Парт номер</td>
                    <td><?= $cart['part_number']?></td>
                </tr>
                <tr class="text-<?= $cart['part_quantity'] <= $cart['stock_count'] ? 'green' : 'red'?>">
                    <td width="30%">Количетсво</td>
                    <td><?= $cart['part_quantity']?></td>
                </tr>
                <tr class="text-green">
                    <td width="30%">Количество на складе</td>
                    <td><?= $cart['stock_count']?></td>
                </tr>
                <tr>
                    <td width="30%">Описание парт номера</td>
                    <td><?= \Umbrella\components\Decoder::strToUtf($cart['goods_name'])?></td>
                </tr>
                <tr>
                    <td width="30%">Склад</td>
                    <td><?= $cart['stock_name']?></td>
                </tr>
                <tr>
                    <td width="30%">Цена</td>
                    <td>
                        <?php $sumPrice = number_format($cart['price'] * $cart['part_quantity'], 2, '.', ' ')?>

                        <?= round($cart['price'], 2)?> x <?= $cart['part_quantity']?> = <?= $sumPrice?>
                        <?=$user->getInfoUserGM()['ShortName']?>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Срок действия запроса (дней)</td>
                    <td><?= $cart['period']?></td>
                </tr>
                <tr>
                    <td width="30%">Заметки</td>
                    <td><?= $cart['note1']?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right">
                        <button data-trash-id="<?= $key?>" class="delete delete-request-with-cart">Удалить</button>
                    </td>
                </tr>
            </table>
        <?php endforeach;?>

        <div class="row align-top">
            <div class="medium-6 small-6 columns">
                <button type="submit" id="clear-multi-cart" class="button primary">Очистить корзину</button>
            </div>
            <div class="medium-6 small-6 columns">
                <button type="submit" id="send-multi-cart" class="button primary">Отправить</button>
            </div>
        </div>

    <?php endif;?>
<?php else:?>
<table class="umbrella-table">
    <tr>
        <td>Ваша корзина пуста</td>
    </tr>
</table>
<?php endif;?>
