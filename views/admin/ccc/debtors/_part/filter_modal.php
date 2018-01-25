<div class="reveal" id="open-filter-modal" data-reveal>
    <form action="/adm/ccc/debtors/filter" id="filter_form" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Filter</h3>
            </div>

            <div class="medium-12 small-12 columns" style="margin-bottom: 10px">
                <label>Партнер</label>
                <select name="user_id" id="user_id_analog" class="selectpicker" data-live-search="true">
                    <option value=""></option>
                    <?php if(is_array($partnerList)):?>
                        <?php foreach($partnerList as $partner): ?>
                            <option data-tokens="<?= $partner['name_partner'] ?><" value="<?= $partner['name_partner'] ?>"><?= $partner['name_partner'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Статус</label>
                <select name="bill_status" id="">
                    <option value="">Все</option>
                    <option value="просрочен">Просрочен</option>
                    <option value="не оплачен">Не оплачен</option>
                    <option value="частично оплачен">Частично оплачен</option>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Отсрочка</label>
                <select name="deferment" id="">
                    <option value=""></option>
                    <?php foreach ($deferments as $deferment): ?>
                        <option value="<?= $deferment['deferment'] ?>"><?= $deferment['deferment'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="medium-12 small-12 columns" style="margin-bottom: 20px">
                <button type="submit" class="button primary">Apply</button>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>