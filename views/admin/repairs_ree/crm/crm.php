<?php require(views_path('admin/layouts/header.php')) ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>CRM</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/repairs_ree_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-5 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-12 small-12 columns">
                                <button data-open="create-so-modal" class="button primary tool"><i class="fi-plus"></i> Create SO</button>
                                <button data-open="close-so-modal" class="button primary tool"><i class="fi-plus"></i> Close SO</button>
                            </div>
                        </div>
                    </div>
                    <div class="medium-4  small-12 columns">
                        <form action="/adm/repairs_ree/crm/" method="get" class="form">
                            <div class="row align-bottom">
                                <div class="medium-4 text-left small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                    <input type="text" id="date-start" value="<?=(isset($_GET['start']) && $_GET['start'] != '') ? $_GET['start'] : ''?>" name="start" required>
                                </div>
                                <div class="medium-4 small-12 columns">
                                    <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                    <input type="text" id="date-end" value="<?=(isset($_GET['end']) && $_GET['end'] != '') ? $_GET['end'] : ''?>" name="end">
                                </div>
                                <div class="medium-4 small-12 columns">
                                    <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="medium-3 small-12 columns form">
                        <form action="/adm/repairs_ree/crm/s/" method="get" class="form" data-abide novalidate>
                            <input type="text" class="required search-input" placeholder="Search..." name="search" required>
                            <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content">
            <div class="row">
                <div class="medium-12 small-12 columns">
                    <table class="umbrella-table margin-bottom">
                        <thead>
                        <tr>
                            <th>Partner</th>
                            <th>Repair ID</th>
                            <th>SO Number</th>
                            <th>Serial Number</th>
                            <th>Internal Reference Number</th>
                            <th>Part Number</th>
                            <th>Stock</th>
                            <th>Order Number</th>
                            <th>Status SO</th>
                            <th>Creation Date</th>
                            <th>Closure Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<?php require(views_path('admin/repairs_ree/crm/_part/create-so.php'))?>

<?php require(views_path('admin/repairs_ree/crm/_part/close-so.php'))?>

<?php require(views_path('admin/layouts/footer.php')) ?>
