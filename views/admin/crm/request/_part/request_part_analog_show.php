<?php if(sizeof($analogPartInStocks) > 0): ?>
    <?php if(is_array($analogPartInStocks)): ?>
        <?php foreach ($analogPartInStocks as $part): ?>
            <table class="umbrella-table">
                <tbody>
                <tr>
                    <td width="30%">Part number</td>
                    <td><?= $part['PartNumber'] ?></td>
                </tr>
                <tr>
                    <td width="30%">Goods name</td>
                    <td><?= $part['mName'] ?></td>
                </tr>
                <tr>
                    <td width="30%">Stocks</td>
                    <td>
                        <?php if(is_array($part['stocks'])): ?>
                            <?php foreach ($part['stocks'] as $key => $stock): ?>
                                <span>- <?=  $key?></span><br>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>

                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <table>
        <tbody>
        <tr>
            <td class="text-center">
                Analog not found
            </td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
