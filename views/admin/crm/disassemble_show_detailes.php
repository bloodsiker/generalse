<?php if(isset($comment) && $comment != ''):?>
    <div class="title-comments">
        Comments:
    </div>
    <div class="disassemble_comment">
        <?=$comment['note']?>
    </div>
<?php endif;?>

<?php if(is_array($data) && count($data) > 0):?>
<table>
    <thead>
    <tr>
        <th>PartNumber</th>
        <th>mName</th>
        <th>Stock</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item):?>
        <tr>
            <td><?=$item['part_number']?></td>
            <td><?=$item['mName']?></td>
            <td><?=$item['stock_name']?></td>
            <td><?=$item['quantity']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php endif;?>