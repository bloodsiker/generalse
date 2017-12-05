<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Activity</h1>
            </div>
            <div class="medium-12 small-12 bottom-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-12 text-left small-12 columns">
                        <ul class="menu">
                            <?php require_once ROOT . '/views/admin/layouts/psr_menu.php'; ?>
                        </ul>
                    </div>
                    <div class="medium-12 small-12 columns">
                        <div class="row align-bottom">
                            <div class="medium-10 small-12 columns">
                                <button data-open="open-activity" class="button primary tool"><i class="fi-plus"></i> Filter</button>
                            </div>
                            <div class="medium-2 small-12 columns">
                                <input type="text" id="goods_search" class="search-input" placeholder="Search..." name="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="body-content checkout">
             <div class="row">

                 <table class="umbrella-table" id="goods_data">
                     <thead>
                     <tr>
                         <th>ID</th>
                         <th>Name partner</th>
                         <th>Log</th>
                         <th>Date</th>
                     </tr>
                     </thead>
                     <tbody>
                     <?php if(is_array($logList)):?>
                         <?php foreach ($logList as $log):?>
                             <tr class="goods">
                                 <td><?= $log['id_log']?></td>
                                 <td><?= $log['name_partner']?></td>
                                 <td><?= $log['log_text']?></td>
                                 <td><?= $log['date_log']?></td>
                             </tr>
                         <?php endforeach;?>
                     <?php endif;?>
                     </tbody>
                 </table>
             </div>
          </div>
    </div>
</div>


<div class="reveal" id="open-activity" data-reveal>
    <form action="" id="filer-activity" method="post" class="form" data-abide
          novalidate>
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <h3>Filter</h3>
            </div>
            <div class="medium-12 small-12 columns">
                <div class="row">

                    <div class="medium-12 small-12 columns">
                        <label>Партнер</label>
                        <select name="user_id" class="selectpicker required" data-live-search="true" required>
                            <option value=""></option>
                            <?php if(is_array($usersInGroup)):?>
                                <?php foreach($usersInGroup as $partner): ?>
                                    <option data-tokens="<?= $partner['name_partner'] ?><" value="<?= $partner['id_user'] ?>"><?= $partner['name_partner'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="medium-12 small-12 columns" style="margin-top: 15px;">
                        <div class="row">
                            <div class="medium-6 small-12 columns">
                                <label>From Date</label>
                                <input type="text" class="required date" name="start" required>
                            </div>
                            <div class="medium-6 small-12 columns">
                                <label>To Date</label>
                                <input type="text" class="required date" name="end" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="apply_filter" value="true">
                    <div class="medium-12 small-12 columns" style="margin-top: 15px; margin-bottom: 100px;">
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

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
