<?php require_once ROOT . '/views/site/layouts/header.php'; ?>

<section class="career">
    <div class="medium-10 text-right small-12 red-header">
        <h2>Career with General Services</h2>
    </div>
    <div class="row item align-middle">
        <div class="medium-3 small-12 text-center show-for-small-only columns">
            <img src="/template/site/img/Career/1.png" width="200px" alt="Career with General Services">
        </div>
        <div class="medium-6 small-12 columns">
            <h3>Service Delivery Manager (GE)</h3>
            <div class="row">
                <div class="medium-4 small-12 columns">
                    <p>Location: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Georgia </p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>Key function:</p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Service and Logistics management</p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>We offer: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>full benefit package, competitive salary, growth opportunities</p>
                    <button data-open="worker1">details</button>

                </div>
            </div>
        </div>
        <div class="medium-3 small-12 hide-for-small-only columns">
            <img src="/template/site/img/Career/1.png" alt="Career with General Services">
        </div>
    </div>

    <div class="row item align-middle">
        <div class="medium-3 medium-offset-3 small-12 text-center columns">
            <img src="/template/site/img/Career/2.png" width="200px" alt="Career with General Services">
        </div>
        <div class="medium-6 small-12 columns">
            <h3>Service Delivery Manager (BY)</h3>
            <div class="row">
                <div class="medium-4 small-12 columns">
                    <p>Location: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Belarus </p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>Key function:</p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Service and Logistics management</p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>We offer: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>full benefit package, competitive salary, growth opportunities</p>
                    <button data-open="worker2">details</button>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="reveal large" id="worker1" data-reveal data-animation-in="fade-in" data-animation-out="fade-out">
    <div class="row item">
        <div class="medium-12 small-12 columns">
            <h3>Service Delivery Manager (GE)</h3>

            <ol> Requirements:
                <li>Experience in position Supervisor\Director of customer service delivery and/or account management no less 5 years</li>
                <li>Well-developed multi-tasking, organizational skills, and detail orientation abilities</li>
                <li>Advanced user of Microsoft Office (PowerPoint, Outlook, Word and Excel)</li>
                <li>Knowledge and experience in service technological processes</li>
                <li>Knowledge and experience in customs legislation of Georgia</li>
                <li>Knowledge and experience in administration and logistics processes, financial and tax aspects</li>
                <li>Knowledge on IT-device’s market and technology components</li>

            </ol>
            <ol> Responsibilities:
                <li>Hiring of personnel according to business needs</li>
                <li>Creation of Depot-structure of Company</li>
                <li>Introduction of the company's corporate ERP-system</li>
                <li>Creation of effective business processes within company</li>
                <li>Organization of the company in accordance with the norms of the legislation of Georgia</li>
                <li>Creation of professional infrastructure for the processing of spare parts</li>
                <li>Quality Control and High Technical performance</li>
                <li>Control Administrative discipline in company</li>
            </ol>
        </div>

    </div>
    <div class="row contact-us align-center">
        <div class="medium-7 small-12 columns">
            <form data-abide id="career-form1" novalidate action="#" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="fio" placeholder="Your name" required pattern="text">
                        <input type="text" name="email" placeholder="Your e-mail" required pattern="email">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="company" placeholder="Company name" data-abide-ignore>
                        <input type="text" name="phone" class="phone-mask" placeholder="Phone number" pattern="number" required>
                        <input type="text" name="page" value="Career"  style="display: none">
                        <input type="text" name="vacancy" value="Service Delivery Manager (GE)"  style="display: none">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <textarea name="message" type="text" placeholder="Type your message here" ></textarea>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label for="file" class="button">add an attachment</label>
                        <input type="file" name="userfile" id="file" class="show-for-sr">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <button type="submit" id="career-send1">Send message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal large" id="worker2" data-reveal data-animation-in="fade-in" data-animation-out="fade-out">
    <div class="row item">
        <div class="medium-12 small-12 columns">
            <h3>Service Delivery Manager (BY)</h3>
            <ol> Requirements:
                <li>Experience in position Supervisor\Director of customer service delivery and/or account management no less 5 years</li>

                <li>Well-developed multi-tasking, organizational skills, and detail orientation abilities</li>

                <li>Advanced user of Microsoft Office (PowerPoint, Outlook, Word and Excel)</li>

                <li>Knowledge and experience in service technological processes</li>

                <li>Knowledge and experience in customs legislation of Belarus</li>

                <li>Knowledge and experience in administration and logistics processes, financial and tax aspects</li>

                <li>Knowledge on IT-device’s market and technology components</li>
            </ol>
            <ol> Responsibilities:
                <li>Hiring of personnel according to business needs</li>

                <li>Creation of Depot-structure of Company</li>

                <li>Introduction of the company's corporate ERP-system</li>

                <li>Creation of effective business processes within company</li>

                <li>Organization of the company in accordance with the norms of the legislation of Belarus</li>

                <li>Creation of professional infrastructure for the processing of spare parts</li>

                <li>Quality Control and High Technical performance</li>

                <li>Control Administrative discipline in company</li>

            </ol>
        </div>

    </div>
    <div class="row contact-us align-center">
        <div class="medium-7 small-12 columns">
            <form data-abide id="career-form2" novalidate action="#" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="fio" placeholder="Your name" required pattern="text">
                        <input type="text" name="email" placeholder="Your e-mail" required pattern="email">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="company" placeholder="Company name" data-abide-ignore>
                        <input type="text" name="phone" class="phone-mask" placeholder="Phone number" pattern="number" required>
                        <input type="text" name="page" value="Career"  style="display: none">
                        <input type="text" name="vacancy" value="Service Delivery Manager (BY)"  style="display: none">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <textarea name="message" type="text" placeholder="Type your message here" ></textarea>
                    </div>
                    <div class="medium-6 small-12 columns">
                        <label for="file" class="button">add an attachment</label>
                        <input type="file" name="userfile" id="file" class="show-for-sr">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <button type="submit" id="career-send2">Send message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<?php require_once ROOT . '/views/site/layouts/footer.php'; ?>


