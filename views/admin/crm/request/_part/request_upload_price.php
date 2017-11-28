<div class="reveal" id="open-upload-price" data-reveal>
    <form action="/adm/crm/request/upload_price" id="price-upload" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Upload price</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">


                    <div class="medium-12 small-12 columns">
                        <div class="row" style="color: #fff">
                            <h4>Формат и название файлов</h4>
                            <ul>
                                <li>Electrolux(Партнер): <span style="color: orange">Price_Electrolux.zip</span></li>
                                <li>Electrolux(Оптовик): <span style="color: orange">Price_Electrolux_Opt.zip</span></li>
                                <li>Electrolux GE(Партнер GE): <span style="color: orange">Electrolux_Prices_GE.zip</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="medium-6 small-12 columns">
                        <label>Group Partner</label>
                        <select name="id_group" class="required" required>
                            <option value="2">Electrolux</option>
                            <option value="4">Electrolux GE</option>
                        </select>
                    </div>

                    <div class="medium-6 small-12 columns">
                        <label>Price</label>
                        <select name="partner_status" class="required" required>
                            <option value="Партнер">Партнер</option>
                            <option value="Оптовик">Оптовик</option>
                            <option value="Партнер GE">Партнер GE</option>
                        </select>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_new_price" class="button primary">Attach</label>
                                <input type="file" id="upload_new_price" class="show-for-sr" name="excel_file" multiple>
                            </div>

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="upload-progress">
                                <div class="upload-bar"></div >
                                <div class="upload-percent">0%</div >
                            </div>
                        </div>
                    </div>


                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div id="status" style="color: #fff;"></div>
                            </div>
                            <div class="medium-6 small-12 columns">
                                <input type="submit" class="button primary" value="Upload File to Server">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>