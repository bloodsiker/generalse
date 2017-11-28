<div class="reveal" id="price-modal" data-reveal>
    <form action="" id="price-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Price</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Part Number <span style="color: #4CAF50;" class="name-product"></span></label>
                <span style="color: orange;" class="pn-analog"></span>
                <input type="text" class="required" name="part_number" required>
            </div>
            <div class="medium-12 small-12 columns">
                <label>Price</label>
                <input type="text" class="required" name="price" disabled>
            </div>

            <div class="large-12 small-12 columns group-analog hide">
                <label>Part Analog
                    <div class="input-group">
                        <input type="text" name="part-analog" id="copy" class="required" style="margin: 0"/>
                        <span class="btn-clip input-group-label copy-analog" data-clipboard-target="#copy">Copy</span>
                    </div>
                </label>
            </div>

            <div class="medium-12 small-12 columns group-analog hide">
                <label>Price analog</label>
                <input type="text" class="required" name="analog-price" disabled>
            </div>

            <div class="medium-12 small-12 columns group-stocks hide">
                <label>Stock <span style="color: #4CAF50;" class="name-stock"></span></label>
                <input type="text" class="required" name="quantity" disabled>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>