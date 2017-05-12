<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
  <div class="medium-12 small-12 columns">
    <div class="row header-content">
      <div class="medium-12 small-12 top-gray columns">
        <h1>Moto</h1>
      </div>
      <div class="medium-12 small-12 bottom-gray colmns">
        <div class="row align-bottom">
          <div class="medium-12 text-left small-12 columns">
            <ul class="menu">
              <?php require_once ROOT . '/views/admin/layouts/crm_menu.php'; ?>
            </ul>
          </div>
          <div class="medium-12 small-12 columns">
            <div class="row align-bottom">
              <div class="medium-3 small-12 columns">
                <button class="button primary tool" id="add-create-button"><i class="fi-plus"></i> Create</button>
              </div>
              <!-- <div class="medium-4  small-12 columns">
                        <form action="/adm/result/" method="get" id="kpi" class="form">
                           <div class="row align-bottom">
                              <div class="medium-4 text-left small-12 columns">
                                 <label for="right-label"><i class="fi-calendar"></i> From date</label>
                                 <input type="text" id="date-start" name="start" required>
                              </div>
                              <div class="medium-4 small-12 columns">
                                 <label for="right-label"><i class="fi-calendar"></i> To date</label>
                                 <input type="text" id="date-end" name="end">
                              </div>
                              <div class="medium-4 small-12 columns">
                                 <button type="submit" class="button primary"><i class="fi-eye"></i> Show</button>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="medium-3 medium-offset-2 small-12 columns">
                        <form action="#" method="get" class="form">
                           <input type="text" class="search-input" placeholder="Search..." name="search">
                           <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                        </form>
                     </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- body -->
    <!-- <div class="body-content checkout">
         <div class="row">
            <table>
               <thead>
                  <tr>
                     <th class="sort">Order Number</th>
                     <th class="sort">Service Order</th>
                     <th class="sort">Stock</th>
                     <th class="sort">Date</th>
                     <th class="sort">Part Number</th>
                     <th class="sort">Desription</th>
                     <th class="sort">Quantity</th>
                     <th class="sort">Status</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div> -->
  </div>
</div>
<div class="reveal" id="add-checkout-modal" data-reveal>
  <form action="#" id="add-checkout-form" method="post" class="form" data-abide novalidate>
    <div class="row align-bottom">
      <div class="medium-12 small-12 columns">
        <h3>PSR</h3>
      </div>
      <div class="medium-12 small-12 columns">
        <div class="row">
          <div class="medium-12 small-12 columns">
            <div class="row align-bottom ">
              <div class="medium-12 small-12 columns">
                <label>Serial Number</label>
                <input type="text" pattern=".{8,}" class="required" name="Serial_number" required>
              </div>
              <div class="medium-12 small-12 columns">
                <label>MTM</label>
                <input type="text" class="required" name="mtm" required>
              </div>
              <div class="medium-5 small-12 columns">
                <label>Manufacture Date</label>
                <input type="text" class="required date" name="Manufacture_Date" required>
              </div>
              <div class="medium-5 small-12 columns">
                <label>Purchase Date</label>
                <input type="text" class="required date" name="Purchase_date" required>
              </div>
              <div class="medium-2 small-12 columns">
                <label>Days</label>
                <input type="text" name="Days">
              </div>
              <div class="medium-12 small-12 columns">
                <span class="error-date" style="color: #ff635a;">Unfortunately, It is not PSR. Please, contact your manager</span>
              </div>
            </div>
          </div>
          <div class="medium-12 small-12 columns">
            <div class="row">
              <div class="medium-12 small-12 columns">
                <label>Level</label>
                <select name="Level" id="repair_level" class="required" required>
                  <option value="" selected disabled>none</option>
                  <option value="L0">L0</option>
                  <option value="L1">L1</option>
                  <option value="L2">L2</option>
                  <option value="ACT">ACT</option>
                </select>
              </div>
            </div>
            <div class="row align-bottom l1-show">
              <div class="medium-6 small-12 columns">
                <label>Part Number</label>
                <input type="text" name="Part_Number">
              </div>
              <div class="medium-6 small-12 columns">
                <label>Source</label>
                <select class="source" name="Source_1">
                  <option value="" selected disabled>none</option>
                  <option value="Local Source">Local Source</option>
                  <option value="Not Used">Not Used</option>
                  <option value="Dismantling">Dismantling</option>
                  <option value="Restored">Restored</option>
                </select>
              </div>
              <div class="medium-6 ls-show small-12 columns">
                <label>Price</label>
                <input type="text" pattern="[^a-zA-Z]" name="Price_1">
              </div>
              <div class="medium-6 ls-show small-12 columns">
                  <select name="Price_1" class="required" required>
                     <option value="USD" selected>USD</option>
                     <option value="UAH">UAH</option>
                  </select>
               </div>
            </div>
            <div class="row l1-show">
              <div class="medium-12 small-12 columns">
                <button type="button" class="button primary" id="add-parts-info"><i class="fi-plus"></i> add</button>
              </div>
            </div>
          </div>
          <div class="medium-12 small-12 columns">
            <div class="row">
              <div class="medium-6 small-12 columns">
                <label for="exampleFileUpload" class="button primary">Attach</label>
                <input type="file" id="exampleFileUpload" class="show-for-sr" name="Attach_file[]" multiple="true">
              </div>
              <div class="medium-6 small-12 columns">
                <button type="submit" class="button primary">Send</button>
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
  <?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
