<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">

        <section>
            <h1 class="text-uppercase mb-5">General services europe</h1>

            <div class="row align-items-center">
                <div class="col-md-4">
                    <p><strong>General Services</strong> has the widest geographical coverage among all the service providers in the CIS countries. Our branches and warehouses, partner customer service centers are located in 7 countries of Eastern Europe providing the customers not only with a high quality after-sales service, but also a single contact for providing services in various countries.</p>
                    <p>We provide:</p>
                    <ul>
                        <li>Achievement and control over the required technical indicators</li>
                        <li>Maintenance of local spare parts stocks</li>
                        <li>Providing operational logistics</li>
                        <li>Use of alternative sources of providing component parts, if necessary</li>
                        <li>Minimizing the cost of returning the non-repairable equipment</li>
                        <li>Administrative and technical training of personnel and their audit</li>
                        <li>Effective communications with a network of service centers</li>
                        <li>Single contact and transparent management process for the customer</li>
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
                    <h2><?= $country['country_en']?></h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-nowrap">Company name</th>
                            <th width="10%">City</th>
                            <th width="30%">Address</th>
                            <th>Telephone</th>
                            <th>Specialization</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($country['service_center'] as $service): ?>

                            <tr>
                                <td><?= $service['company_name_en'] ?></td>
                                <td><?= $service['city_en'] ?></td>
                                <td><?= $service['address_en'] ?></td>
                                <td><?= $service['phone'] ?></td>
                                <td><?= $service['specialization_en'] ?></td>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>


    </main>
</div>


<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

