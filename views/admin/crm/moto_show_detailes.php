<?php if(is_array($listDocument) && count($listDocument) > 0):?>
    <table>
        <caption>Attach file</caption>
        <thead>
        <tr>
            <th>Name file</th>
            <th width="100">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($listDocument as $document):?>
            <tr>
                <td><?=$document['real_file_name']?></td>
                <td><a href="<?=$document['file_path'] . $document['file_name']?>" style="color: #2ba6cb" target="_blank" download>download</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif;?>

<?php if(is_array($data)):?>
<table>
    <caption>Add Parts</caption>
    <thead>
    <tr>
        <th>PartNumber</th>
        <th>Goods Name</th>
        <th>Serial Number</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item):?>
        <?php if($item['operation_type'] == 1):?>
            <tr>
                <td><?=$item['part_number']?></td>
                <td><?=iconv('WINDOWS-1251', 'UTF-8', $item['goods_name'])?></td>
                <td><?=$item['serial_number']?></td>
            </tr>
        <?php endif;?>
    <?php endforeach;?>
    </tbody>
</table>

<hr>
<table>
    <caption>Add Local Source</caption>
    <thead>
    <tr>
        <th>PartNumber</th>
        <th>Goods Name</th>
        <th>Price</th>
        <th>Serial Number</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item):?>
        <?php if($item['operation_type'] == 2):?>
            <tr>
                <td><?=$item['part_number']?></td>
                <td><?=iconv('WINDOWS-1251', 'UTF-8', $item['goods_name'])?></td>
                <td><?=$item['price']?></td>
                <td><?=$item['serial_number']?></td>
            </tr>
        <?php endif;?>
    <?php endforeach;?>
    </tbody>
</table>
<hr>
<table>
    <caption>Close Repair</caption>
    <thead>
    <tr>
        <th>Complete Date</th>
        <th>Repair Level</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item):?>
        <?php if($item['operation_type'] == 3):?>
            <tr>
                <td><?=$item['complete_date']?></td>
                <td><?=$item['repair_level']?></td>
            </tr>
        <?php endif;?>
    <?php endforeach;?>
    </tbody>
</table>
<?php endif;?>