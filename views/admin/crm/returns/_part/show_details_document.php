<?php if(!empty($return['document']) && $return['document'] != ' '): ?>
    <table class="umbrella-table" border="1">
        <tbody>
        <tr>
            <td><?= $fileName ?></td>
            <td width="100"><a href="<?= $return['document'] ?>" style="color: #2ba6cb" download="">download</a></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
