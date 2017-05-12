<?php if(isset($data) && is_array($data)):?>
    <?php foreach ($data as $item):?>
        <tr>
            <td><?=$item['SO_NUMBER']?></td>
            <td><?=$item['SO_CREATION_DATE']?></td>
            <td><?=$item['Serial_Number']?></td>
            <td><?=$item['Service_Complete_Date']?></td>
            <td><?=$item['Item_Product_ID']?></td>
            <td><?=$item['Item_Product_Desc']?></td>
            <td><?=$item['IRIS_1_Repair']?></td>
            <td><?=$item['Unit_Received_Date']?></td>
            <td><?=$item['Part_Order_Date']?></td>
            <td><?=$item['Part_Delivery_Date']?></td>
            <td><?=$item['Customer_Email']?></td>
        </tr>
    <?php endforeach;?>
<?php endif;?>