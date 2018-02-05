<div class="reveal" id="filter-modal" data-reveal>
    <form action="" id="filter-form" method="post" class="form" data-abide novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Filter</h3>
            </div>
            <div class="medium-6 small-12 columns">
                <label>Year</label>
                <select name="year" class="required" required>
                    <?php foreach ($intervalYears as $date): ?>
                        <option <?= ($year == $date['year']) ? 'selected' : null ?> value="<?= $date['year'] ?>"><?= $date['year'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="medium-6 small-12 columns">
                <label>Month</label>
                <select name="month" class="required" required>
                    <?php foreach ($intervalMonths as $date): ?>
                        <option <?= ($month == $date['month']) ? 'selected' : null ?> value="<?= $date['month'] ?>"><?= $date['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="medium-12 small-12 columns">
                <div class="row">
                    <input type="hidden" name="filter" value="true">
                    <div class="medium-12 small-12 columns">
                        <button type="submit" class="button primary">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>