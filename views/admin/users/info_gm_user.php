<table class="umbrella-table" border="1">
    <thead>
        <tr>
            <th width="35%"></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Название</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['site_client_name'])?></td>
        </tr>
        <tr>
            <td>Название английское</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['name_en'])?></td>
        </tr>
        <tr>
            <td>Адрес</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['address'])?></td>
        </tr>
        <tr>
            <td>Адрес английский</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['address_en'])?></td>
        </tr>
        <tr>
            <td>Для ТТН</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['for_ttn'])?></td>
        </tr>
        <tr>
            <td>Валюта</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['ShortName'])?></td>
        </tr>
        <tr>
            <td>Цена</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['PriceName'])?></td>
        </tr>
        <tr>
            <td>Признак для поляков</td>
            <td><?= $userInfo['to_electrolux'] == 0 ? 'Нет' : 'Да'?></td>
        </tr>
        <tr>
            <td>Признак для почты в бухгалтерию </td>
            <td><?= $userInfo['to_mail_send'] == 0 ? 'Нет' : 'Да'?></td>
        </tr>
        <tr>
            <td>Договор</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['contract_number'])?></td>
        </tr>
        <tr>
            <td>Ответственный</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['DisplayName'])?></td>
        </tr>
        <tr>
            <td>Местоположение</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['StockPlaceName'])?></td>
        </tr>
        <tr>
            <td>Телефон</td>
            <td><?= $userInfo['phone']?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $userInfo['email']?></td>
        </tr>
        <tr>
            <td>Регион\Город</td>
            <td><?= iconv('WINDOWS-1251', 'UTF-8', $userInfo['mName'])?></td>
        </tr>
    </tbody>
</table>
