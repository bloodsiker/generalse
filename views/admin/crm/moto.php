<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Moto</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-7 small-9 columns">
                                <button class="button primary tool" id="create-rep-button"><i class="fi-plus"></i>
                                    Create New Repair
                                </button>
                                <button class="button primary tool" id="create-parts-button"><i class="fi-plus"></i> Add
                                    Part(s)
                                </button>
                                <button class="button primary tool" id="create-locsource-button"><i class="fi-plus"></i>
                                    Add Local Source
                                </button>
                                <button class="button primary tool" id="create-closerepeir-button"><i
                                            class="fi-plus"></i> Close Repair
                                </button>

                            </div>

                            <div class="medium-2 small-3 columns">
                                <form action="/adm/crm/moto/" method="get" class="form">
                                    <label>Status</label>
                                    <select name="status" onchange="this.form.submit()" class="required" required>
                                        <option value="Все" <?=(isset($_GET['status']) && $_GET['status'] == 'Все') ? 'selected' : ''?>>Все</option>
                                        <option value="Ремонт завершен" <?=(isset($_GET['status']) && $_GET['status'] == 'Ремонт завершен') ? 'selected' : ''?>>Ремонт завершен</option>
                                        <option value="Ожидание детали" <?=(isset($_GET['status']) && $_GET['status'] == 'Ожидание детали') ? 'selected' : ''?>>Ожидание детали</option>
                                        <option value="Диагностика" <?=(isset($_GET['status']) && $_GET['status'] == 'Диагностика') ? 'selected' : ''?>>Диагностика</option>
                                        <option value="Ремонт" <?=(isset($_GET['status']) && $_GET['status'] == 'Ремонт') ? 'selected' : ''?>>Ремонт</option>
                                    </select>
                                </form>
                            </div>

                            <div class="medium-3 small-3 columns">
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <?php if($user->role == 'partner'):?>
                    <table id="goods_data" class="umbrella-table table">
                        <thead>
                        <tr>
                            <th scope="col">Registration Number</th>
                            <th >Serial Number</th>
                            <th >Part Number</th>
                            <th >Goods Name</th>
                            <th >Problem description</th>
                            <th >Purchase Date</th>
                            <th >Carry-in Date</th>
                            <th >Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (is_array($listMoto)): ?>
                            <?php foreach ($listMoto as $moto): ?>
                                <tr data-siteid="<?=$moto['site_id']?>" class="goods ">
                                    <td><?=$moto['service_object_id']?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['serial_number'])?></td>
                                    <td><?=$moto['part_number']?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['goods_name'])?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['problem_description'])?></td>
                                    <td><?= Umbrella\components\Functions::formatDate($moto['purchase_date'])?></td>
                                    <td><?= Umbrella\components\Functions::formatDate($moto['carry_in_date'])?></td>
                                    <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['status_name'])?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                <?php elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'):?>
                <table class="umbrella-table" id="goods_data">
                    <thead>
                    <tr>
                        <th class="sort">Partner</th>
                        <th class="sort">Registration Number</th>
                        <th class="sort">Serial Number</th>
                        <th class="sort">Part Number</th>
                        <th class="sort">Goods Name</th>
                        <th class="sort">Problem description</th>
                        <th class="sort">Purchase Date</th>
                        <th class="sort">Carry-in Date</th>
                        <th class="sort">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($listMoto)): ?>
                        <?php foreach ($listMoto as $moto): ?>
                        <tr data-siteid="<?=$moto['site_id']?>" class="goods ">
                            <td><?=$moto['site_client_name']?></td>
                            <td><?=$moto['service_object_id']?></td>
                            <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['serial_number'])?></td>
                            <td><?=$moto['part_number']?></td>
                            <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['goods_name'])?></td>
                            <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['problem_description'])?></td>
                            <td><?= Umbrella\components\Functions::formatDate($moto['purchase_date'])?></td>
                            <td><?= Umbrella\components\Functions::formatDate($moto['carry_in_date'])?></td>
                            <td><?=iconv('WINDOWS-1251', 'UTF-8', $moto['status_name'])?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


<div class="reveal small" id="create-rep" data-reveal>
    <form action="#" id="create-new-repair" method="post" class="form" data-abide novalidate enctype="multipart/form-data">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Create New Repair</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <h5>Device Info</h5>
                        <div class="row">
                            <input type="hidden" name="new-repair" value="true">
                            <div class="medium-12 small-12 columns">
                                <label>Serial Number</label>
                                <input type="text" pattern=".{8,}" class="required" name="serial_number" autocomplete="off" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>MTM <span style="color: #4CAF50;" class="name-product"></span></label>
                                <input type="text" class="required" name="mtm" autocomplete="off" required>
                                <input type="hidden" class="" name="goods_name" value="">
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Purchase Date</label>
                                <input type="text" class="required date" name="purchase_date" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Carry-in Date</label>
                                <input type="text" class="required date" name="carry_in_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <h5>Customer Info</h5>
                        <div class="row">
                            <div class="medium-12 small-12 columns">
                                <label>Customer Name and Last Name</label>
                                <input type="text" class="required" name="client_name" autocomplete="off" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Customer Phone Number</label>
                                <input type="text" class="required" name="client_phone" autocomplete="off" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Customer E-mail</label>
                                <input type="text" class="required" name="client_email" autocomplete="off" required>
                            </div>
                            <div class="medium-12 small-12 columns">
                                <label>Problem Description</label>
                                <textarea style="height: 100px" name="problem_description" class="required" required></textarea>
                            </div>
                            <div class="medium-6 medium-offset-6 small-12 columns">
                                <label>Attach</label>
                                <label for="exampleFileUpload" class="button primary">Attach</label>
                                <input type="file" id="exampleFileUpload" class="show-for-sr" name="attach_file[]"
                                       multiple="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="create-parts" data-reveal>
    <form action="#" id="add-parts" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Add Part(s)</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <input type="hidden" name="add-parts" value="true">
                    <div class="medium-12 small-12 columns">
                        <label>Serial Number <span class="serial_num"></span></label>
                        <input type="text" pattern=".{8,}" class="required" name="serial_num_parts" autocomplete="off" required>
                        <input type="hidden" class="" name="site_id" value="">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                        <input type="text" class="required" name="mtm" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="create-locsource" data-reveal>
    <form action="#" id="add-local-source" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Add Local Source</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <input type="hidden" name="add-local-source" value="true">
                    <div class="medium-12 small-12 columns">
                        <label>Serial Number <span class="serial_num_local"></span></label>
                        <input type="text" pattern=".{8,}" class="required" name="serial_num_local" autocomplete="off" required>
                        <input type="hidden" class="" name="site_id" value="">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                        <input type="text" class="required" name="mtm" autocomplete="off" required>
                    </div>
                </div>
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <label>Price</label>
                        <input type="text" class="required" name="price" required>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label>Currency</label>
                        <select name="Part_Price_1" disabled id="part-price" class="required" required>
                            <option value="USD" selected>USD</option>
                            <!-- <option value="UAH">UAH</option> -->
                        </select>
                    </div>

                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal" id="create-closerepeir" data-reveal>
    <form action="#" id="close-repair" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Close Repair</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <input type="hidden" name="close-repair" value="true">
                    <div class="medium-12 small-12 columns">
                        <label>Serial Number <span class="serial_num_close"></span></label>
                        <input type="text" pattern=".{8,}" class="required" name="serial_num_close" autocomplete="off" required>
                        <input type="hidden" class="" name="site_id" value="">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Complete Date</label>
                        <input type="text" class="required date" name="complete_date" required>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <label>Repair Level</label>
                        <select name="repair_level" id="repair_level" class="required" required>
                            <option value="" selected disabled>none</option>
                            <option value="L0">L0</option>
                            <option value="L1">L1</option>
                            <option value="L2">L2</option>
                            <option value="Refunded">Refunded</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="reveal small" id="show-details" data-reveal>
    <div class="row align-bottom">
        <div class="medium-12 small-12 columns">
            <h3>Moto</h3>
        </div>
        <div class="medium-12 small-12 columns" id="container-details">

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
