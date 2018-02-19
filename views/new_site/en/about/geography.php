<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">

        <section>
            <h1 class="text-uppercase mb-5">General services europe</h1>

            <div class="row align-items-center">
                <div class="col-md-4">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus enim excepturi inventore molestias
                        officiis recusandae sequi unde! Aliquid aut, cum error est excepturi ipsa, non nulla placeat, quaerat
                        officiis recusandae sequi unde! Aliquid aut, cum error est excepturi ipsa, non nulla placeat, quaerat
                        officiis recusandae sequi unde! Aliquid aut, cum error est excepturi ipsa, non nulla placeat, quaerat
                        voluptas voluptatem!</p>
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


<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

