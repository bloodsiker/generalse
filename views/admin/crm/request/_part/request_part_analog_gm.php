<div class="reveal" id="find-part-analog-gm" data-reveal>
    <form action="" id="find-part-analog-gm-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Part analog</h3>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Партнер</label>
                <select name="user_id" id="user_id_analog" class="selectpicker required" data-live-search="true" required>
                    <option value=""></option>
                    <?php if(is_array($partnerList)):?>
                        <?php foreach($partnerList as $partner): ?>
                            <option data-tokens="<?= $partner['name_partner'] ?><" value="<?= $partner['id_user'] ?>"><?= $partner['name_partner'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Part Number <span class="load_part_number"></span></label>
                <input type="text" class="required" onkeyup="partAnalogGM(event.target['value'])" name="part_analog_gm" value="11201301" autocomplete="off" required>
            </div>
            <div class="medium-12 small-12 columns" id="analog-in-stocks" style="min-height: 100px">

            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>