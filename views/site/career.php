<?php require_once ROOT . '/views/site/layouts/header.php'; ?>

<section class="career">
    <div class="medium-10 text-right small-12 red-header">
        <h2>Career with General Services</h2>
    </div>
    <div class="row item align-middle">
        <div class="medium-3 medium-offset-3 small-12 text-center columns">
            <img src="/template/site/img/Career/2.png" width="200px" alt="Career with General Services">
        </div>
        <div class="medium-6 small-12 columns">
            <h3>Sales Team Manager</h3>
            <div class="row">
                <div class="medium-4 small-12 columns">
                    <p>Location: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Ukraine </p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>Key function:</p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>Sales of spare parts and components in B2B and B2C</p>
                </div>
                <div class="medium-4 small-12 columns">
                    <p>We offer: </p>
                </div>
                <div class="medium-8 small-12 columns">
                    <p>full benefit package, competitive salary, growth opportunities</p>
                    <button data-open="worker3">details</button>
                </div>
            </div>
        </div>
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
                    <div class="medium-6 small-12 columns hide">
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
                    <div class="medium-6 small-12 columns hide">
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

<div class="reveal large" id="worker3" data-reveal data-animation-in="fade-in" data-animation-out="fade-out">
    <div class="row item">
        <div class="medium-12 small-12 columns">
            <h3>Sales Team Manager</h3>
            <p style="font-size: 14px;">
                The Sales Manager in our company is the person who wants to develop together with a young, professional and very ambitious team in a modern, international IT company. We have only ambitious, interesting, achievable tasks that allow us to constantly develop in the professional, personal and financial terms. We are the company that encourages any initiative, listens to its employees and helps to develop employees and promote their ideas and visions. We have unlimited possibilities, very big plans and opportunities. From you just needed to grow with us at a frantic pace.
            </p>
            <ul> Functional responsibilities:
                <li>Sales of spare parts and components in B2B and B2C</li>
                <li>Work with Service Centers and End Users</li>
                <li>Development and retention of the current Active Client Database</li>
                <li>Development of the base of potential clients </li>
                <li>Participation in analysis, forecasting, pricing - on behalf of ROP</li>
                <li>Development of new projects in sales and marketing</li>
                <li>Work with Marketplaces and retail, active interaction with the Department of Purchasing and Marketing</li>
                <li>Work in CRM, primary documentation, minimum reporting.</li>
            </ul>
            <ul> Requirements:
                <li>Initiative, purposefulness</li>
                <li>Experience in sales - in any field is welcome</li>
                <li>Desire to learn and dynamically develop in the direction of service, sales</li>
                <li>Implementation of the Sales Plan and the desire to indecently make a lot of money</li>
                <li>Responsibility, activity, sociability and ambitiousness </li>
                <li>English at the level above the average - written, basic conversational. A good level of English allows you to work with foreign markets. Training in the company.</li>
            </ul>
        </div>

    </div>
    <div class="row contact-us align-center">
        <div class="medium-7 small-12 columns">
            <form data-abide id="career-form3" novalidate action="#" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="fio" placeholder="Your name" required pattern="text">
                        <input type="text" name="email" placeholder="Your e-mail" required pattern="email">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <input type="text" name="company" placeholder="Company name" data-abide-ignore>
                        <input type="text" name="phone" class="phone-mask" placeholder="Phone number" pattern="number" required>
                        <input type="hidden" name="page" value="Career">
                        <input type="hidden" name="vacancy" value="Sales Team Manager">
                    </div>
                    <div class="medium-12 small-12 columns">
                        <textarea name="message" type="text" placeholder="Type your message here" ></textarea>
                    </div>
                    <div class="medium-6 small-12 columns hide">
                        <label for="file" class="button">add an attachment</label>
                        <input type="file" name="userfile" id="file" class="show-for-sr">
                    </div>
                    <div class="medium-6 small-12 columns">
                        <button type="submit" id="career-send3">Send message</button>
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


