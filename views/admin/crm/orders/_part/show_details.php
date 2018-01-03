<?php if($user->isPartner()): ?>
    <table class="umbrella-table" style="width: 25%">
        <tbody>
        <?php if(!empty($order['delivery_declaration'])): ?>
            <tr>
                <td><b>Declaration number</b></td>
                <td><?= $order['delivery_declaration'] ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <table class="umbrella-table">
        <thead>
        <tr>
            <th>PartNumber</th>
            <th>Goods Name</th>
            <th>Service Order</th>
            <th>Stock name</th>
            <th>Quantity</th>
            <th class="text-center">Price uah</th>
            <?php if($user->getGroupName() == 'Electrolux'):?>
                <th class="text-center">Price euro</th>
            <?php endif?>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($ordersElements as $element): ?>
            <tr>
                <td><?= $element['part_number'] ?></td>
                <td><?= $element['goods_name'] ?></td>
                <td><?= $element['so_number'] ?></td>
                <td><?= $element['stock_name'] ?></td>
                <td><?= $element['quantity'] ?></td>
                <td class="text-center"><?= $element['price'] ?></td>
                <?php if($user->getGroupName() == 'Electrolux'):?>
                    <td class="text-center"><?= $element['price_euro'] ?></td>
                <?php endif?>
            </tr>
        <?php endforeach;; ?>
        </tbody>
    </table>

<?php elseif ($user->isAdmin() || $user->isManager()): ?>

    <table class="umbrella-table" style="width: 25%">
        <tbody>
        <tr>
            <td><b>User currency</b></td>
            <td><?= $userClient['ShortName'] ?></td>
        </tr>
        <tr>
            <td><b>User price</b></td>
            <td><?= $userClient['PriceName'] ?></td>
        </tr>
        <?php if($userClient['ShortName'] == 'uah'): ?>
            <tr>
                <td><b>Sum order price UAH:</b></td>
                <td><?= round($sumPriceUah, 2) ?></td>
            </tr>
            <tr>
                <td><b>Sum order price USD:</b></td>
                <td><?= round($sumPriceUsd, 2) ?></td>
            </tr>
            <tr>
                <td><b>Sum order price EURO:</b></td>
                <td><?= round($sumPriceEuro, 2) ?></td>
            </tr>
        <?php elseif ($userClient['ShortName'] == 'usd'): ?>
            <tr>
                <td><b>Sum order price UAH:</b></td>
                <td><?= round($sumPriceUah, 2) ?></td>
            </tr>
            <tr>
                <td><b>Sum order price USD:</b></td>
                <td><?= round($sumPriceUsd, 2) ?></td>
            </tr>
        <?php elseif ($userClient['ShortName'] == 'euro'): ?>
            <tr>
                <td><b>Sum order price UAH:</b></td>
                <td><?= round($sumPriceUah, 2) ?></td>
            </tr>
            <tr>
                <td><b>Sum order price EURO:</b></td>
                <td><?= round($sumPriceEuro, 2) ?></td>
            </tr>
        <?php elseif ($userClient['ShortName'] == 'gel'): ?>
            <tr>
                <td><b>Sum order price UAH:</b></td>
                <td><?= round($sumPriceUah, 2) ?></td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($order['delivery_declaration'])): ?>
            <tr>
                <td><b>Declaration number</b></td>
                <td><?= $order['delivery_declaration'] ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <table class="umbrella-table">
        <thead>
        <tr>
            <th>PartNumber</th>
            <th>Goods Name</th>
            <th>Service Order</th>
            <th>Stock name</th>
            <th>Quantity</th>
            <?php if($userClient['ShortName'] == 'uah'): ?>
                <th class="text-center">Price UAH </th>
                <th class="text-center">Price USD</th>
                <th class="text-center">Price EURO</th>
            <?php elseif ($userClient['ShortName'] == 'usd'): ?>
                <th class="text-center">Price UAH </th>
                <th class="text-center">Price USD</th>
            <?php elseif ($userClient['ShortName'] == 'euro'): ?>
                <th class="text-center">Price UAH </th>
                <th class="text-center">Price EURO</th>
            <?php elseif ($userClient['ShortName'] == 'gel'): ?>
                <th class="text-center">Price UAH </th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($ordersElements as $element): ?>
            <tr>
                <td><?= $element['part_number'] ?></td>
                <td><?= $element['goods_name'] ?></td>
                <td><?= $element['so_number'] ?></td>
                <td><?= $element['stock_name'] ?></td>
                <td><?= $element['quantity'] ?></td>
                <?php if($userClient['ShortName'] == 'uah'): ?>
                    <td class="text-center"><?= $element['price_uah'] ?></td>
                    <td class="text-center"><?= $element['price_usd'] ?></td>
                    <td class="text-center"><?= $element['price_euro'] ?></td>
                <?php elseif ($userClient['ShortName'] == 'usd'): ?>
                    <td class="text-center"><?= $element['price_uah'] ?></td>
                    <td class="text-center"><?= $element['price_usd'] ?></td>
                <?php elseif ($userClient['ShortName'] == 'euro'): ?>
                    <td class="text-center"><?= $element['price_uah'] ?></td>
                    <td class="text-center"><?= $element['price_euro'] ?></td>
                <?php elseif ($userClient['ShortName'] == 'gel'): ?>
                    <td class="text-center"><?= $element['price'] ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach;; ?>
        </tbody>
    </table>
<?php endif; ?>
