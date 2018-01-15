<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Dashboard</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/engineers_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-3 small-12 columns">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- body -->
        <div class="body-content checkout">
            <div class="row">
                <div class="medium-8 medium-offset-2 small-12 columns">
                    <table class="umbrella-table">
                        <caption>KPI</caption>
                        <thead>
                        <tr>
                            <th>KPI</th>
                            <th>Condition</th>
                            <th>Target</th>
                            <th>Weight</th>
                            <th>Result</th>
                            <th>Coef.</th>
                            <th>Rate</th>
                            <th>Бонус</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SW repair TAT</td>
                                <td>1 business day</td>
                                <td>90%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Engineer Order TAT</td>
                                <td>1 business day</td>
                                <td>95%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Выплата</td>
                            </tr>
                            <tr>
                                <td>Repair TAT</td>
                                <td>1 business day</td>
                                <td>90%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>FTF 30 days</td>
                                <td>no more</td>
                                <td>4%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>FTF 90 days</td>
                                <td>no more</td>
                                <td>6%</td>
                                <td>0,1</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>L2 rate</td>
                                <td>more</td>
                                <td>30%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Return Spare Parts</td>
                                <td>1 business day</td>
                                <td>95%</td>
                                <td>0,15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td>0,00</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
