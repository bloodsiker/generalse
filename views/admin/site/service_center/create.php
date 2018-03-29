<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-8 medium-offset-2 small-12 columns">
            <h2>Add service center</h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post" class="form" data-abide novalidate>
                    <div class="row">
                        <div class="medium-12 small-12 columns">
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Country
                                        <select name="country_code" class="required" required>
                                            <option value="ua">Ukraine</option>
                                            <option value="by">Belarus</option>
                                            <option value="am">Armenia</option>
                                            <option value="md">Moldova</option>
                                            <option value="ge">Georgia</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Phones
                                        <input type="text" name="phone"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="medium-6 small-12 columns">
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Company name ru
                                        <input type="text" name="company_name" class="required" required/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>City ru
                                        <input type="text" name="city_ru" class="required" required/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Address ru
                                        <input type="text" name="address_ru"/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Specialization ru
                                        <input type="text" name="specialization_ru" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="medium-6 small-12 columns">
                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Company name en
                                        <input type="text" name="company_name_en" class="required" required/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>City en
                                        <input type="text" name="city_en" class="required" required/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Address en
                                        <input type="text" name="address_en"/>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="large-12 columns">
                                    <label>Specialization en
                                        <input type="text" name="specialization_en" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                        <input type="hidden" name="add_sc" value="true">

                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" class="button small float-right" value="Create">
                                <a href="/adm/site/service-center" class="button small info"> Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>