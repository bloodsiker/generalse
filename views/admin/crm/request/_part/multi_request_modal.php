<?php if($user->isPartner()): ?>
    <div class="reveal large" id="add-multi-request-modal" data-reveal>
        <div class="row align-top">
            <div class="medium-5 small-12 columns">
                <form action="" id="add-multi-request-form" method="post" class="form" data-abide novalidate>
                    <div class="row align-top">
                        <div class="medium-12 small-12 columns">
                            <h3>Новый запрос</h3>
                        </div>

                        <div class="medium-9 small-9 columns">
                            <label>Парт номер <span id="load_part_number"></span></label>
                            <input type="text" class="required" name="multi_part_number" autocomplete="off" required>
                        </div>

                        <div class="medium-3 small-3 columns">
                            <label>Количество</label>
                            <input type="number" class="required" name="part_quantity" value="1" autocomplete="off" required>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <label>Описание парт номера</label>
                            <input type="text" name="goods_name">
                        </div>

                        <div class="medium-12 small-12 columns">
                            <ul class="stocks-view">

                            </ul>
                        </div>

                        <div class="medium-9 small-12 columns">
                            <label>Склады</label>
                            <select name="stock_id">

                            </select>
                        </div>

                        <div class="medium-3 small-3 columns">
                            <label>Цена</label>
                            <input type="text" name="stock_price" disabled>
                        </div>

                        <input type="hidden" name="pn_price" value="">
                        <input type="hidden" name="stock_count" value="">
                        <input type="hidden" name="stock_name" value="">
                        <input type="hidden" name="user_currency" value="">
                        <input type="hidden" name="request_user_id" value="">

                        <div class="medium-12 small-12 columns">
                            <label>Срок действия запроса (дней)</label>
                            <input type="text" name="period" class="required" onkeyup="checkCurrPartNumber(this)" autocomplete="off" required>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <label>Заметки</label>
                            <input type="text" name="note1" autocomplete="off">
                        </div>

                        <div class="medium-12 small-12 columns">
                            <button type="submit" class="button primary">Добавить в корзину</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="medium-7 small-12 columns">
                <div class="row align-top">
                    <div class="medium-12 small-12 columns">
                        <h3>Корзина</h3>
                    </div>
                    <div class="medium-12 small-12 columns" id="cart-container">
                        <?php require_once ROOT . '/views/admin/crm/request/multi-request-cart.php'?>
                    </div>
                </div>
            </div>
        </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php elseif($user->isManager() || $user->isAdmin()): ?>

    <div class="reveal large" id="add-multi-request-modal" data-reveal>
        <div class="row align-top">
            <div class="medium-5 small-12 columns">
                <form action="" id="add-multi-request-form" method="post" class="form" data-abide novalidate>
                    <div class="row align-top">
                        <div class="medium-12 small-12 columns">
                            <h3>Новый запрос Manager</h3>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <label>Партнер</label>
                            <select name="user_id" class="selectpicker required" data-live-search="true" required>
                                <option value=""></option>
                                <?php if(is_array($partnerList)):?>
                                    <?php foreach($partnerList as $partner): ?>
                                        <option data-tokens="<?= $partner['name_partner'] ?><" value="<?= $partner['id_user'] ?>"><?= $partner['name_partner'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="medium-9 small-9 columns">
                            <label>Парт номер <span class="load_part_number"></span></label>
                            <input type="text" class="required" name="multi_part_number" autocomplete="off" required>
                        </div>

                        <div class="medium-3 small-3 columns">
                            <label>Количество</label>
                            <input type="number" class="required" name="part_quantity" value="1" autocomplete="off" required>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <label>Описание парт номера <span id="send-content" class="hide">Отправить контент-менеджеру</span></label>
                            <input type="text" name="goods_name">
                        </div>

                        <div class="medium-12 small-12 columns">
                            <ul class="stocks-view">

                            </ul>
                        </div>

                        <div class="medium-6 small-12 columns">
                            <label>Склады</label>
                            <select name="stock_id">

                            </select>
                        </div>

                        <div class="medium-3 small-3 columns">
                            <label>Цена <span id="first_price_currency"></span></label>
                            <input type="text" name="stock_price" id="first_price">
                        </div>

                        <div class="medium-3 small-3 columns">
                            <label>Цена <span id="last_price_currency"></span></label>
                            <input type="text" name="stock_price_other" id="last_price">
                        </div>

                        <input type="hidden" name="pn_price" value="">
                        <input type="hidden" name="stock_count" value="">
                        <input type="hidden" name="stock_name" value="">
                        <input type="hidden" name="user_currency" value="">
                        <input type="hidden" name="request_user_id" value="">

                        <div class="medium-12 small-12 columns">
                            <label>Срок действия запроса (дней)</label>
                            <input type="text" name="period" class="required" onkeyup="checkCurrPartNumber(this)" autocomplete="off" required>
                        </div>

                        <div class="medium-12 small-12 columns">
                            <label>Заметки</label>
                            <input type="text" name="note1" autocomplete="off">
                        </div>

                        <div class="medium-12 small-12 columns">
                            <button type="submit" class="button primary">Добавить в корзину</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="medium-7 small-12 columns">
                <div class="row align-top">
                    <div class="medium-12 small-12 columns">
                        <h3>Корзина</h3>
                    </div>
                    <div class="medium-12 small-12 columns" id="cart-container">
                        <?php require_once ROOT . '/views/admin/crm/request/multi-request-cart.php'?>
                    </div>
                </div>
            </div>
        </div>

        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>