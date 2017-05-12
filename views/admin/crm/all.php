<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Disassembly</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-5 small-12 columns">

                            </div>
                            <div class="medium-2 medium-offset-3 small-12 columns">
                                <a href="/adm/crm/disassembly" class="button primary">send request</a>
                            </div>
                            <div class="medium-2 small-12 columns">
                                <a href="/adm/crm/disassembly_list" class="button primary active-req">list disassembly</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <th>part</th>
                        <th>serial Number</th>
                        <th>goods Number</th>
                        <th>Stock</th>
                        <th>goods name</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if (is_array($listDisassembly)): ?>
                        <?php foreach ($listDisassembly as $dis): ?>
                            <tr>
                                <td><?=$dis['part_number']?></td>
                                <td><?=$dis['serial_number']?></td>
                                <td><?=$dis['goods_part']?></td>
                                <td><?=$dis['stock_name']?></td>
                                <td><?=iconv('WINDOWS-1251', 'UTF-8', $dis['goods_name'])?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
