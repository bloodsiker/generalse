<?php if(count($listDocuments) > 0):?>
    <table class="umbrella-table">
        <thead>
            <tr>
                <th></th>
                <th width="100"></th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array($listDocuments)):?>
            <?php foreach ($listDocuments as $document):?>
                <tr>
                    <td><?= $document['file_name']?></td>
                    <td><a href="<?= $document['file_path'] . $document['file_name']?>" style="color: #2ba6cb" download="">download</a></td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
        </tbody>
    </table>
<?php endif;?>