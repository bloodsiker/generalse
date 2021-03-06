<div class="reveal" id="add-request-import-modal" data-reveal>
    <form action="/adm/crm/request/import" id="add-request-import-form" method="post" class="form" enctype="multipart/form-data" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Import request</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <label>Delivery address</label>
                        <select name="note" class="required" required>
                            <option value="" selected disabled>none</option>
                            <?php if(is_array($delivery_address)):?>
                                <?php foreach ($delivery_address as $address):?>
                                    <option value="<?= $address['address']?>" <?= $address['is_default'] == 1 ? 'selected' : null ?>><?= $address['address']?></option>
                                <?php endforeach;?>
                            <?php endif; ?>
                            <option value="other_address">Write another address</option>
                        </select>
                        <input type="hidden" name="your_address" placeholder="Enter your address">
                    </div>

                    <div class="medium-12 small-12 columns">
                        <label>Type</label>
                        <select name="order_type_id" class="required" required>
                            <option value="" selected disabled>none</option>
                            <?php foreach ($order_type as $type):?>
                                <option value="<?= $type['id']?>"  <?= $type['selected']?>><?= $type['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom ">
                            <div class="medium-12 small-12 columns">
                                <label for="upload_file_form" class="button primary">Attach</label>
                                <input type="file" id="upload_file_form" class="show-for-sr" name="excel_file" required>
                            </div>

                        </div>
                    </div>

                    <div class="medium-12 small-12 columns">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <div style="padding-bottom: 37px; color: #fff"><a
                                        href="/upload/attach_request/request_import.xlsx" style="color: #2ba6cb"
                                        download="">download</a> a template file to import
                                </div>
                            </div>
                            <input type="hidden" name="import_request" value="true">
                            <div class="medium-6 small-12 columns">
                                <button type="submit" class="button primary">Send</button>
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