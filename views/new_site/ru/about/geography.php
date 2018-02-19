<?php require_once ROOT . '/views/new_site/ru/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/ru/layouts/header.php'; ?>

    <main class="container">

        <section>
            <h1 class="text-uppercase mb-5">General services europe</h1>

            <div class="row align-items-top">
                <div class="col-md-4">
                    <p><strong>General Services</strong> имеет самое широкое географическое покрытие среди всех сервис-провайдеров в странах СНГ. Наши филиалы и склады, партнерские центры обслуживания клиентов находятся в 7 странах Восточной Европы, предоставляя клиентам не только качественный послепродажный сервис, но и единый контакт по обеспечению обслуживания в различных странах.</p>
                    <p>Мы обеспечиваем:</p>
                    <ul>
                        <li>Достижение и контроль над требуемыми техническими показателями</li>
                        <li>Содержание локальных стоков запчастей</li>
                        <li>Обеспечение оперативной логистики</li>
                        <li>Использование альтернативных источников обеспечения комплектующими при необходимости</li>
                        <li>Минимизацию расходов на возврат неремонтопригодной техники</li>
                        <li>Административное и техническое обучение персонала и их аудит</li>
                        <li>Эффективные коммуникации с сетью сервисных центров</li>
                        <li>Единый контакт и прозрачный процесс управления для заказчика</li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div id="vmap" style="width: 100%;height: 600px;" class="w-100"></div>
                </div>
            </div>
        </section>

        <?php if(is_array($serviceInCountry)): ?>
            <?php foreach ($serviceInCountry as $country): ?>
                <section data-country="<?= $country['country_code']?>">
                    <h2><?= $country['country_ru']?></h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-nowrap">Название компании</th>
                            <th width="10%">Город</th>
                            <th width="30%">Адрес</th>
                            <th>Телефон</th>
                            <th>Специализация</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($country['service_center'] as $service): ?>

                                <tr>
                                    <td><?= $service['company_name'] ?></td>
                                    <td><?= $service['city_ru'] ?></td>
                                    <td><?= $service['address_ru'] ?></td>
                                    <td><?= $service['phone'] ?></td>
                                    <td><?= $service['specialization_ru'] ?></td>
                                </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>


<?php require_once ROOT . '/views/new_site/ru/layouts/footer.php'; ?>

