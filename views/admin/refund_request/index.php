<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>

<div class="row">
    <div class="medium-12 small-12 columns">
        <form action="#" method="post" class="form form_warranty" id="form_warranty" data-abide novalidate enctype="multipart/form-data">
            <div class="row header-content">
                <div class="medium-12 small-12 top-gray columns">
                    <h1>Warranty Exception Registration</h1>
                </div>
                <div class="medium-12 small-12 bottom-gray colmns">
                    <div class="row align-bottom">
                        <div class="medium-3 text-left small-12 columns">
                            <label><i class="fi-flag"></i> Request Country
                                <select required class="country" name="Request_Country" id="country">
                                    <option value="" disabled selected>Country</option>
                                    <?php if (is_array($countryList)): ?>
                                        <?php foreach ($countryList as $country): ?>
                                            <option value="<?=$country['full_name']?>" <?= ($user->country == $country['full_name']) ? 'selected' : ''?>><?=$country['full_name']?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </label>
                        </div>
                        <div class="medium-3 small-12 columns">
                            <label><i class="fi-list"></i> Request Type
                                <select required id="request-type" name="Request_Type">
                                    <!-- <option value="" disabled selected>none</option>-->
                                    <!-- <option value="Warranty Exception">Warranty Exception</option>
                                    <option value="General Service Query">General Service Query</option> -->
                                    <option selected value="Refund">Refund</option>
                                </select>
                            </label>
                        </div>
                        <div class="medium-6 small-12 text-right columns">
                            <a class="button primary tool active-req"><i class="fi-pencil"></i> Registration</a>
                            <a href="/adm/refund_request/view" class="button primary tool"><i class="fi-eye"></i> Show requests</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- warranty -->
            <!-- <div class="body-content warranty">
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <p style="color: red;">The form is used to request warranty exceptions, raise escalated cases to the Service Delivery team.</p>
                  <h3>Your Information</h3>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Originator's First Name</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Originator's_First_Name" required>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Originator's Last Name</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Originator's_Last_Name" required>
                    </div>
                  </div>
                </div>
                <div class="medium-6 medium-offset-1 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Originator's Email</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Originator's_Email" required>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <h3>Additional People to be Notified</h3>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 1</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_1">
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 2</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_2">
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 3</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_3">
                    </div>
                  </div>
                </div>
                <div class="medium-6 medium-offset-1 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 4</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_4">
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 5</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_5">
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Additional People to be Notified 6</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" name="Additional_People_to_be_Notified_6">
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <h3>Customer Information</h3>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Customer Name</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Customer_Name" required>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>RCMS Number</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="RCMS_Number" required>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <h3>Machine Information</h3>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Product Type</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <select id="product-type" name="Product_Type" required class="required">
                        <option value="">none</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Desktops and All-in-Ones">Desktops and All-in-Ones</option>
                        <option value="Laptops">Laptops</option>
                        <option value="Monitors and Projectors">Monitors and Projectors</option>
                        <option value="Servers">Servers</option>
                        <option value="Smartphones">Smartphones</option>
                        <option value="Tablets">Tablets</option>
                      </select>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Machine Series</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <select id="machine-series" required class="required" name="Machine_Series">
                        <option value="" selected="divue">none</option>
                      </select>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Machine Model Name</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <select required class="required" id="machine-model-name" name="Machine_Model_Name" style="width: 170px;">
                        <option value="" selected="divue">none</option>
                      </select>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Part Number (Machine Type & Model)</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Part_Number_(Machine_Type_&_Model)" required>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Machine Serial Number</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Machine_Serial_Number" required>
                    </div>
                  </div>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Total Number of Failing Machines</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="text" class="required" name="Total_Number_of_Failing_Machines" required>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>PoP Received/Checked</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <input type="checkbox" class="required" name="PoP_Received/Checked" required>
                    </div>
                  </div>
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Does customer have warranty upgrade?</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <select class="required" name="Does_customer_have_warranty_upgrade?" required>
                        <option value="" disabled selected>none</option>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <h3>Request Information</h3>
                </div>
                <div class="medium-5 small-12 columns">
                  <div class="row align-middle">
                    <div class="medium-5 small-12 text-right columns">
                      <label>Fault Description</label>
                    </div>
                    <div class="medium-7 small-12 columns">
                      <textarea type="text" class="required" name="Fault_Description" required></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="medium-12 small-12 columns">
                  <h3>Attachment</h3>
                  <p>When all data is corret, please provide attachment if necessary</p>
                  <p>Note: Attachments cannot be open at the time you create the case - please close them prior to attaching to the case. Maximum file size is 10 MB.</p>
                </div>
                <div class="medium-2 small-12 columns">
                  <label for="exampleFileUpload" class="button primary">Attach</label>
                  <input type="file" id="exampleFileUpload" name="Attach_Warantary" class="show-for-sr">
                </div>
              </div>
              <hr>
              <div class="row align-center">
                <div class="medium-3 small-12 columns">
                  <button class="button primary">Submit</button>
                </div>
              </div>
            </div> -->
            <!--  warantary/// -->
            <!-- GENERAL AND REFUND -->
            <div class="body-content">
                <div class="row">
                    <div class="medium-12 small-12 columns" style="margin-bottom: 20px">
                        <?php if(isset($not_exit_file)):?>
                            <h1 style="color: red">Error sending form</h1>
                            <span style="color: red">- <?=$not_exit_file?></span>
                        <?php endif;?>

                        <?php if(isset($arr_error_pn) && is_array($arr_error_pn)):?>
                            <h1 style="color: red">Error sending form</h1>
                            <?php foreach($arr_error_pn as $error_pn):?>
                                <span style="color: red"><?=$error_pn?> - Part number is not found</span><br>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <h3>Your Information</h3>
                    </div>
                    <div class="medium-5 small-12 columns">
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Requestor First Name</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" class="required" name="Requestor_First_Name" value="<?=(isset($_POST['Requestor_First_Name'])) ? $_POST['Requestor_First_Name'] : ''?>" required>
                            </div>
                        </div>
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Requestor Last Name</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" class="required" name="Requestor_Last_Name" value="<?=(isset($_POST['Requestor_Last_Name'])) ? $_POST['Requestor_Last_Name'] : ''?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="medium-6 medium-offset-1 small-12 columns">
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Requestor Email</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Requestor_Email" class="required" value="<?=(isset($_POST['Requestor_Email'])) ? $_POST['Requestor_Email'] : ''?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <h3>Case Information</h3>
                    </div>
                    <div class="medium-5 small-12 columns">
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Refund Reason</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <select id="refund-reason" name="Refund_Reason" class="required" required>
                                    <option value="">none</option>
                                    <option value="Part_shortage">Part shortage</option>
                                    <option value="Multiple_repair">Multiple repair</option>
                                    <option value="Exception">Exception</option>
                                    <option value="EOL">EOL</option>
                                    <option value="DOA">DOA</option>
                                    <option value="Parts_DOA">Parts DOA</option>
                                    <option value="SWAP">SWAP</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-middle doa-validation-results" style="display: none;">
                            <div class="medium-5 small-12 text-right columns">
                                <label>DOA Validation results</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <select name="DOA_Validation_results">
                                    <option value="">none</option>
                                    <option value="DOA">DOA</option>
                                    <option value="NTF">NTF</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide missing-part" style="display: none;">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Missing Part</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Missing_Part" >
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Lenovo SO</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Lenovo_SO" class="required" required>
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>SO Create Date</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" id="so-create-date" name="SO_Create_Date" class="required" required>
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>SN</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <span id='spanError' style='color: red; font-size: 13px'></span>
                                <input type="text" id="SN_check" class="required" name="SN" pattern=".{8,}" required>
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>PN (MTM)</label>
                            </div>
                            <div class="medium-7 small-12 PN_check columns">
                                <input type="text" id="PN_check" name="PN_MTM" class="required" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Product Group</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <select name="Product_Group" class="required" required>
                                    <option value="">none</option>
                                    <option value="Think_units">Think units</option>
                                    <option value="Lenovo-former_Idea-notebooks">Lenovo - former Idea - notebooks</option>
                                    <option value="MBG_Phone">MBG Phone</option>
                                    <option value="MBG_Tablet">MBG Tablet</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="medium-6 medium-offset-1 small-12 columns">
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Multiple Request</label>
                            </div>
                            <div class="medium-7 small-12 columns" >
                                <input type="checkbox" id="multiple-request"  name="Multiple_Request" <?=(isset($_POST['Multiple_Request']) == 1) ? 'checked' : ''?> value="1">
                            </div>
                        </div>
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Partner SO number/ RMA at partner side</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Partner_SO_RMA" class="required" required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <h3>Additional Information</h3>
                    </div>
                    <div class="medium-5 small-12 columns">
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Future Unit Location</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Future_Unit_location" class="required" required>
                            </div>
                        </div>
                        <div class="row align-middle">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Additional Comment</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <textarea name="Additional_Comment"><?=(isset($_POST['Additional_Comment'])) ? $_POST['Additional_Comment'] : ''?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="medium-6 medium-offset-1 small-12 columns">
                        <div class="row align-middle multiple-request-hide">
                            <div class="medium-5 small-12 text-right columns">
                                <label>Estimated cost (PoP price)</label>
                            </div>
                            <div class="medium-7 small-12 columns">
                                <input type="text" name="Estimated_cost" class="required" required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="medium-12 small-12 columns">
                        <h3>Attachment</h3>
                        <p>For bulk request, please fill in this .csv form in order to provide all necessary data and add as csv attachment</p>
                        <p>Please attach case related files such as invoice, pictures, service protocol to your request. This will help us towards a faster resolution.</p>
                        <ul> Notes:
                            <li>Please make sure to close attachments prior to attaching to the case</li>
                            <li>Maximum file size is 10 MB</li>
                            <li style="color: red;">If you need to resubmit the form due to missing input details, please re-attach attachments (if you have submitted any).</li>
                            <li style="color: red;">For date information please use the YYYY-MM-DD format in the file.</li>
                        </ul>
                    </div>
                    <div class="medium-2 small-12 columns">
                        <label for="exampleFileUpload" class="button primary">Attach</label>
                        <input type="file" id="exampleFileUpload" class="show-for-sr" name="csv_file">
                    </div>
                </div>
                <hr>
                <div class="row align-center">
                    <div class="medium-3 small-12 columns">
                        <button class="button primary send_request" name="send_request">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
