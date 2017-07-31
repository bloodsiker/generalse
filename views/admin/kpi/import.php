<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1 class="title-filter">Import data</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <form action="/adm/result/" method="get" id="kpi" class="form" data-abide novalidate>
                    <div class="row align-bottom">
                        <div class="medium-12 text-right small-12 columns">
                            <a href="/adm/kpi" class="button primary tool">Kpi</a>
                            <a href="/adm/kpi/import" class="button primary tool active-req"><i class="fi-page-export"></i> Import</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="body-content">
    <div class="row">
        <div class="medium-6 small-12 columns">
            <div class="alert-danger">
                <i class="fi-info"></i> Важно! Даты в excel файле должны быть в формате YYYY-mm-dd (2017-01-31)
            </div>
        </div>
        <?php if (Umbrella\app\AdminBase::checkDenied('kpi.import.b', 'view')): ?>
            <div class="medium-12 small-12 columns">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="medium-2 small-12 columns">
                            <input type="text" class="required" name="service_order" value="KPI" disabled>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="exampleFileUpload" class="button primary">Attach</label>
                            <input type="file" id="exampleFileUpload" class="show-for-sr" name="excel_file" required="">
                        </div>
                        <input type="hidden" name="kpi_excel_file" value="true">
                        <div class="medium-2 small-12 columns">
                            <button class="button primary"> Import</button>
                        </div>
                        <div class="medium-6 small-12 columns">
                            <?php if(isset($cout_kpi_success) && !empty($cout_kpi_success)):?>
                                <span class="alert-success">
                                    <?=(isset($cout_kpi_success) && !empty($cout_kpi_success)) ? 'Успешно добавленно ' . $cout_kpi_success . ' строк' : '';?>
                                </span>
                            <?php endif;?>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif;?>
    </div>

    <?php if (Umbrella\app\AdminBase::checkDenied('callcsat.import.b', 'view')): ?>
        <div class="row">
            <div class="medium-12 small-12 columns">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="medium-2 small-12 columns">
                            <input type="text" class="required" name="service_order" value="Call CSAT" disabled>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="exampleFileUpload2" class="button primary">Attach</label>
                            <input type="file" id="exampleFileUpload2" class="show-for-sr" name="excel_file" required="">
                        </div>
                        <input type="hidden" name="call_excel_file" value="true">
                        <div class="medium-2 small-12 columns">
                            <button class="button primary"> Import</button>
                        </div>
                        <div class="medium-6 small-12 columns">
                            <?php if(isset($cout_call_success) && !empty($cout_call_success)):?>
                                <span class="alert-success">
                                    <?=(isset($cout_call_success) && !empty($cout_call_success)) ? 'Успешно добавленно ' . $cout_call_success . ' строк' : '';?>
                                </span>
                            <?php endif;?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif;?>

    <?php if (Umbrella\app\AdminBase::checkDenied('emailcsat.import.b', 'view')): ?>
        <div class="row">
            <div class="medium-12 small-12 columns">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="medium-2 small-12 columns">
                            <input type="text" class="required" name="service_order" value="Email CSAT" disabled>
                        </div>
                        <div class="medium-2 small-12 columns">
                            <label for="exampleFileUpload3" class="button primary">Attach</label>
                            <input type="file" id="exampleFileUpload3" class="show-for-sr" name="excel_file" required="">
                        </div>
                        <input type="hidden" name="email_excel_file" value="true">
                        <div class="medium-2 small-12 columns">
                            <button class="button primary"> Import</button>
                        </div>
                        <div class="medium-6 small-12 columns">
                            <?php if(isset($cout_email_success) && !empty($cout_email_success)):?>
                                <span class="alert-success">
                                    <?=(isset($cout_email_success) && !empty($cout_email_success)) ? 'Успешно добавленно ' . $cout_email_success . ' строк' : '';?>
                                </span>
                            <?php endif;?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif;?>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>


