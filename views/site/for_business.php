<?php require_once ROOT . '/views/site/layouts/header.php'; ?>

    <section class="businesses">
        <div class="medium-8 medium-offset-4 small-12 red-header">
            <h2>Our offer to Businesses</h2>
        </div>
        <div class="row text-center">
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/1.svg" alt="Region-wide coverage in 4 countries: Belarus, Moldova, Georgia, Armenia">
                <p>
                    Region-wide coverage in 4
                    countries: Belarus, Moldova,
                    Georgia, Armenia
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/2.svg" alt="Repair services for 3 product lines: Smartphones, Tablets, PCs">
                <p>
                    Repair services for 3 product
                    lines: Smartphones, Tablets,
                    PCs
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/3.svg" alt="Combination of own companies and subcontractors">
                <p>
                    Combination of own
                    companies and
                    subcontractors
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/4.svg" alt="Single Management Center and focal point for Vendors">
                <p>
                    Single Management Center
                    and focal point for Vendors
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/5.svg" alt="Centralized Local Solutions: parts purchase, dismantling, L3">
                <p>
                    Centralized Local Solutions:
                    parts purchase, dismantling,
                    L3
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/6.svg" alt="Enablement of Vendor Branded Services Sales">
                <p>
                    Enablement of Vendor
                    Branded Services Sales
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/7.svg" alt="Implementation of Customer Satisfaction programs">
                <p>
                    Implementation of Customer
                    Satisfaction programs
                </p>
            </div>
            <div class="medium-3 small-6 columns">
                <img src="/template/site/img/For_business/Offers/8.svg" alt="Vendor-branded CCI design">
                <p>
                    Vendor-branded CCI design
                </p>
            </div>
        </div>
    </section>
    <section class="contact-us">
        <div class="medium-7 small-12 text-right red-header">
            <h2>Contact us</h2>
        </div>
        <div class="row align-center">
            <div class="medium-7 small-12 columns">
                <form id="business-form" data-abide novalidate action="#" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="medium-6 small-12 columns">
                            <input type="text" placeholder="Your name" name="fio" required pattern="text">
                            <input type="text" placeholder="Your e-mail" name="email" required pattern="email">
                        </div>
                        <div class="medium-6 small-12 columns">
                            <input type="text" placeholder="Company name" name="company" data-abide-ignore>
                            <input type="text" class="phone-mask" placeholder="Phone number" name="phone" pattern="number" required>
                            <input type="text" name="page" value="For business"  style="display: none">
                        </div>
                        <div class="medium-12 small-12 columns">
                            <textarea name="message" type="text"  placeholder="Type your message here" ></textarea>
                        </div>
                        <div class="medium-6 small-12 columns">
                            <label for="file" class="button">add an attachment</label>
                            <input type="file" id="file" name="userfile" class="show-for-sr">
                        </div>
                        <div class="medium-6 small-12 columns">
                            <button type="submit" id="business-send">Send message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


<?php require_once ROOT . '/views/site/layouts/footer.php'; ?>