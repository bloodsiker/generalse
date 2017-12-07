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
        <tr>
            <td><b>Sum order price UAH:</b></td>
            <td><?= round($sumPriceUah, 2) ?></td>
        </tr>
        <tr>
            <td><b>Sum order price USD:</b></td>
            <td><?= round($sumPriceUsd, 2) ?></td>
        </tr>
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
            <th>Price UAH </th>
            <th>Price USD</th>
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
                <td><?= $element['price_uah'] ?></td>
                <td><?= $element['price_usd'] ?></td>
            </tr>
        <?php endforeach;; ?>
        </tbody>
    </table>
<?php endif; ?>
