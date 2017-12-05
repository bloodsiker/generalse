<?php if (isset($prices)): ?>
    <table class="umbrella-table" border="1">
        <tbody>
        <tr>
            <td>Part number</td>
            <td><?= $part_number ?></td>
        </tr>
        <tr>
            <td>Goods name</td>
            <td><?= $goods_name ?></td>
        </tr>
        </tbody>
    </table>
    <table class="umbrella-table" border="1">
        <thead>
        <tr>
            <th></th>
            <th>USD</th>
            <th>UAH</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Розница</td>
            <td><?= round($prices['rozn_new'], 2) ?></td>
            <td><?= round($prices['rozn_new'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>Розница б/у</td>
            <td><?= round($prices['rozn_used'], 2) ?></td>
            <td><?= round($prices['rozn_used'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>Партнер</td>
            <td><?= round($prices['partner_new'], 2) ?></td>
            <td><?= round($prices['partner_new'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>Партнер б/у</td>
            <td><?= round($prices['partner_used'], 2) ?></td>
            <td><?= round($prices['partner_used'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>Оптовик</td>
            <td><?= round($prices['opt_new'], 2) ?></td>
            <td><?= round($prices['opt_new'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>Оптовик б/у</td>
            <td><?= round($prices['opt_used'], 2) ?></td>
            <td><?= round($prices['opt_used'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>VIP</td>
            <td><?= round($prices['vip_new'], 2) ?></td>
            <td><?= round($prices['vip_new'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        <tr>
            <td>VIP б/у</td>
            <td><?= round($prices['vip_used'], 2) ?></td>
            <td><?= round($prices['vip_used'] * $currencyUsd['OutputRate'], 2) ?></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>