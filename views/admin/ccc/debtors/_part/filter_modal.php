<div class="reveal" id="open-filter-modal" data-reveal>
    <form action="/adm/ccc/debtors/filter" id="filter_form" method="get" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Filter</h3>
            </div>

            <div class="medium-12 small-12 columns" style="margin-bottom: 10px">
                <label>Партнер</label>
                <select name="client_name" id="user_id_analog" class="selectpicker" data-live-search="true">
                    <option value="">Все партнеры</option>
                    <?php if(is_array($partnerList)):?>
                        <?php foreach($partnerList as $partner): ?>
                            <option data-tokens="<?= $partner['client_name'] ?><" value="<?= $partner['client_name'] ?>"><?= $partner['client_name'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Статус</label>
                <select name="order_status" id="">
                    <option value="">Все</option>
                    <?php if(is_array($orderStatus)):?>
                        <?php foreach ($orderStatus as $status): ?>
                            <option value="<?= $status['order_status'] ?>"><?= $status['order_status'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <label>Отсрочка</label>
                <select name="order_delay" id="">
                    <option value=""></option>
                    <?php if(is_array($orderDelay)):?>
                        <?php foreach ($orderDelay as $delay): ?>
                            <option value="<?= $delay['order_delay'] ?>"><?= $delay['order_delay'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
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