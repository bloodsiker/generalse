<?php require_once ROOT . '/views/new_site/en/layouts/head.php'; ?>

<div class="main">

    <?php require_once ROOT . '/views/new_site/en/layouts/header.php'; ?>

    <main class="container">
        <img class="mw-100 d-none" src="/template/new_site/img/black-and-white-city-man-people.jpg" alt="">
        <div class="row pt-5 mb-lg-5">
            <div class="col-md-6">
                <h1 class="mt-4">For Suppliers</h1>
                <p>General Services is interested in cooperation with manufacturers and suppliers of various product groups that can provide the required assortment, and whose products correspond to the required level of quality.</p>
                <p>We are interested in suppliers and manufacturers of the following original products for laptops, smartphones and tablets of any brands:</p>
                <ul>
                    <li>Display modules for smartphones</li>
                    <li>Matrix displays for laptops</li>
                    <li>Motherboards for smartphones, tablets, laptops</li>
                    <li>Hard drives HDD and SSD</li>
                    <li>RAM modules</li>
                    <li>Keyboards</li>
                    <li>Rechargeable batteries for smartphones</li>
                    <li>Rechargeable batteries for laptops</li>
                    <li>Wired chargers for smartphones and laptops</li>
                    <li>Wireless chargers for smartphones</li>
                    <li>Body details for smartphones and laptops</li>
                </ul>
                <p>If your company is a manufacturer or supplier of one of the specified product groups, fill out the application in the Supplier's Form in this page, and we shall answer in case of interest.</p>
            </div>
            <div class="col-md-6">
                <form action="" method="post" enctype="multipart/form-data" id="form-suppliers" class="form mb-3">
                    <div class="form-group">
                        <label for="name">Name and surname</label>
                        <input type="text" class="form-control" name="fio" id="name" aria-describedby="name" placeholder="Name and surname">
                        <small id="name" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="company">Name of supplier company</label>
                        <input type="text" class="form-control" name="company" id="company" aria-describedby="company" placeholder="Name of supplier company">
                        <small id="company" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-mail address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="E-mail">
                        <small id="emailHelp" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea">Message text</label>
                        <textarea class="form-control" name="message" id="exampleTextarea" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file_price">Attach a price list</label>
                        <input type="file" class="form-control-file" name="file-price" id="file_price" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">The size of the uploaded file should not exceed 10 mb</small>
                    </div>
                    <div class="text-right">
                        <input type="hidden" name="lang" value="en">
                        <input type="hidden" name="suppliers" value="true">
                        <button type="submit" id="send-suppliers" class="btn btn-red">Send</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once ROOT . '/views/new_site/en/layouts/footer.php'; ?>

