<?php if($user->isPartner()): ?>
    <table class="umbrella-table">
        <thead>
        <tr>
            <th>PartNumber</th>
            <th>Goods Name</th>
            <th>Service Order</th>
            <th>Stock name</th>
            <th>Quantity</th>
            <th>Price</th>
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
                <td><?= $element['price'] ?></td>
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
        <?php if($userClient['ShortName'] == 'usd' || $userClient['ShortName'] == 'uah'): ?>
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
            <?php if($userClient['ShortName'] == 'usd' || $userClient['ShortName'] == 'uah'): ?>
                <th>Price UAH </th>
                <th>Price USD</th>
            <?php elseif ($userClient['ShortName'] == 'euro'): ?>
                <th>Price UAH </th>
                <th>Price EURO</th>
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
                <?php if($userClient['ShortName'] == 'usd' || $userClient['ShortName'] == 'uah'): ?>
                    <td><?= $element['price_uah'] ?></td>
                    <td><?= $element['price_usd'] ?></td>
                <?php elseif ($userClient['ShortName'] == 'euro'): ?>
                    <td><?= $element['price_uah'] ?></td>
                    <td><?= $element['price_euro'] ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach;; ?>
        </tbody>
    </table>
<?php endif; ?>
