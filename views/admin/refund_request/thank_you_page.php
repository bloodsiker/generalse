<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <form action="#" method="post" class="form form_warranty" id="form_warranty" data-abide novalidate enctype="multipart/form-data">
            <div class="row header-content">
                <div class="medium-12 small-12 top-gray columns">
                    <h1>Warranty Exception Registration</h1>
                </div>
                <div class="medium-12 small-12 bottom-gray colmns">
                    <div class="row align-bottom">
                        <div class="medium-12 small-12 text-right columns">
                            <a href="/adm/refund_request/registration" class="button primary tool"><i class="fi-pencil"></i> Registration</a>
                            <a href="/adm/refund_request/view" class="button primary tool"><i class="fi-eye"></i> Show requests</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="body-content">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <div class="thank_you_page">
                            <h3>Thank you for leaving the request! <br> Our managers will handle your request.</h3>
                        </div>

                    </div>
                </div>


            </div>
        </form>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
