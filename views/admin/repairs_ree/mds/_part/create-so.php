<div class="reveal" id="create-so-modal" data-reveal>
    <form action="" id="create-so-form" method="post" class="form" enctype="multipart/form-data" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Create SO</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row align-bottom ">
                    <div class="medium-12 small-12 columns">
                        <label for="upload_mds" class="button primary">Attach</label>
                        <input type="file" id="upload_mds" class="show-for-sr" name="upload_mds" required multiple>
                    </div>
                </div>
            </div>
            <input type="hidden" name="create-so" value="true">
            <div class="medium-12 small-12 columns">
                <button type="submit" class="button primary">Send</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>