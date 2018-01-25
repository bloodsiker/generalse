<div class="reveal" id="create-so-modal" data-reveal>
    <form action="" id="create-so-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Create SO</h3>
            </div>
            <input type="hidden" name="import-create-so" value="true">
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
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>