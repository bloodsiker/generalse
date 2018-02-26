<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">
        <img class="mw-100 d-none" src="/template/new_site/img/black-and-white-city-man-people.jpg" alt="">
        <div class="row pt-5 mb-lg-5">
            <div class="col-md-6">
                <h1 class="mt-4">Поставщикам</h1>
                <p>General Services заинтерсован в сотрудничестве с производителями и поставщиками различных товарных групп, которые могут обеспечить нужный ассортимент, и продукция которых соответствует необходимому уровню качества. </p>
                <p>Нас интересуют поставщики и производители следующей оригинальной продукции для ноутбуков, смартфонов и планшетов, любых торговых марок:</p>
                <ul>
                    <li>Дисплейных модулей для смартфонов</li>
                    <li>Матрицы дисплеев для ноутбуков</li>
                    <li>Материнские платы для смартфонов, планшетов, ноутбуков</li>
                    <li>Накопители HDD и SSD</li>
                    <li>Модули оперативной памяти RAM</li>
                    <li>Клавиатуры</li>
                    <li>Аккумуляторные батареи для смартфонов</li>
                    <li>Аккумуляторные батареи для ноутбуков</li>
                    <li>Проводные зарядные устройства для смартфонов и ноутбуков</li>
                    <li>Беспроводные зарядные устройства для смартфонов</li>
                    <li>Корпусные детали для смартфонов и ноутбуков</li>
                </ul>
                <p>Если ваша компания является производителем или поставщиком одной из указанных товарных групп, заполните запрос в Форме Поставщика на данной станице, и мы ответим вам в случае наличия интереса.</p>
            </div>
            <div class="col-md-6">
                <form action="" method="post" enctype="multipart/form-data" id="form-suppliers" class="form mb-3">
                    <div class="form-group">
                        <label for="name">Имя и фамилия</label>
                        <input type="text" class="form-control" name="fio" id="name" aria-describedby="name" placeholder="Имя и фамилия">
                        <small id="name" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="company">Название компании поставщика</label>
                        <input type="text" class="form-control" name="company" id="company" aria-describedby="company" placeholder="Компании">
                        <small id="company" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Адрес электронной почты</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="E-mail">
                        <small id="emailHelp" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea">Сообщения</label>
                        <textarea class="form-control" name="message" id="exampleTextarea" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file_price">Прикрепить прайс-лист</label>
                        <input type="file" class="form-control-file" name="file-price" id="file_price" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">Размер загружаемого файла не должен превышать 10 мб</small>
                    </div>
                    <div class="text-right">
                        <input type="hidden" name="lang" value="ru">
                        <input type="hidden" name="suppliers" value="true">
                        <button type="submit" id="send-suppliers" class="btn btn-red">Отправить</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

