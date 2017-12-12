<table class="umbrella-table" border="1">
    <thead>
    <tr>
        <th width="30%">ID</th>
        <th><?= $psrInfo['id'] ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Partner</td>
        <td><?= $psrInfo['site_client_name'] ?></td>
    </tr>
    <tr>
        <td>Serial number</td>
        <td><?= $psrInfo['serial_number'] ?></td>
    </tr>
    <tr>
        <td>MTM</td>
        <td><?= $psrInfo['part_number'] ?></td>
    </tr>
    <tr>
        <td>Device</td>
        <td><?= $psrInfo['device_name'] ?></td>
    </tr>
    <tr>
        <td>Manufacture date</td>
        <td><?= $psrInfo['manufacture_date'] ?></td>
    </tr>
    <tr>
        <td>Purchase date</td>
        <td><?= $psrInfo['purchase_date'] ?></td>
    </tr>
    <tr>
        <td>Defect description</td>
        <td><?= $psrInfo['defect_description'] ?></td>
    </tr>
    <tr>
        <td>Device condition</td>
        <td><?= $psrInfo['device_condition'] ?></td>
    </tr>
    <tr>
        <td>Complectation</td>
        <td><?= $psrInfo['complectation'] ?></td>
    </tr>
    <tr>
        <td>Note</td>
        <td><?= $psrInfo['note'] ?></td>
    </tr>
    <tr>
        <td>Declaration</td>
        <td><?= $psrInfo['declaration_number'] ?></td>
    </tr>
    <tr>
        <td>Declaration return</td>
        <td><?= $psrInfo['declaration_number_return'] ?></td>
    </tr>
    <tr>
        <td>Status</td>
        <td><?= $psrInfo['status_name'] ?></td>
    </tr>
    <tr>
        <td>Date create</td>
        <td><?= $psrInfo['created_at'] ?></td>
    </tr>
    </tbody>
</table>
