<div class="reveal large" id="open-removed-request" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Deleted requests</h3>
        </div>
        <div class="medium-12 small-12 columns">

            <table class="umbrella-table">
                <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Partner</th>
                    <th>Part Number</th>
                    <th>Part Description</th>
                    <th>SO Number</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Date create</th>
                    <th>Date delete</th>
                    <th>User</th>
                    <th width="70"></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($listRemovedRequest)):?>
                    <?php foreach ($listRemovedRequest as $removedRequest):?>
                        <tr>
                            <td><?= $removedRequest['id']?></td>
                            <td><?= $removedRequest['site_client_name']?></td>
                            <td><?= $removedRequest['part_number']?></td>
                            <td><?= $removedRequest['goods_name']?></td>
                            <td><?= $removedRequest['so_number']?></td>
                            <td><?= round($removedRequest['price'], 2)?></td>
                            <td><?= $removedRequest['type_name']?></td>
                            <td><?= Umbrella\components\Functions::formatDate($removedRequest['created_on'])?></td>
                            <td><?= $removedRequest['deleted_on']?></td>
                            <td><?= $removedRequest['user_name']?></td>
                            <td><button data-reqid="<?= $removedRequest['id']?>" class="delete restored">restore</button></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>

        </div>
    </div>

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>