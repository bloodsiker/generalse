<?php require_once ROOT . '/views/admin/layouts/header.php' ?>

    <div class="row">
        <div class="medium-6 medium-offset-3 small-12 columns">
            <h2>Add vacancy</h2>
            <div class="row body-content" style="background: #EFEFEF">
                <div class="medium-12 small-12 columns">
                    <form method="post" class="form" data-abide novalidate>

                        <ul class="tabs" style="background: #efefef; border: 1px solid #323e48;" data-active-collapse="true" data-tabs id="collapsing-tabs">
                            <li class="tabs-title is-active"><a href="#panel1c" aria-selected="true">En</a></li>
                            <li class="tabs-title"><a href="#panel2c">Ru</a></li>
                        </ul>

                        <div class="tabs-content" style="background: #efefef; border: 1px solid #323e48;" data-tabs-content="collapsing-tabs">
                            <div class="tabs-panel is-active" id="panel1c">
                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Vacancy
                                            <input type="text" name="en_title" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Department
                                            <input type="text" name="en_department" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Location
                                            <input type="text" name="en_location" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Employment
                                            <input type="text" name="en_employment" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Text
                                            <textarea name="en_text" id="ck_article" cols="30" rows="4"></textarea>
                                        </label>
                                    </div>
                                </div>
                            </div>



                            <div class="tabs-panel" id="panel2c">
                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Вакансия
                                            <input type="text" name="ru_title" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Отдел
                                            <input type="text" name="ru_department" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Положение
                                            <input type="text" name="ru_location" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Вид занятости
                                            <input type="text" name="ru_employment" class="required" required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="large-12 columns">
                                        <label>Описание
                                            <textarea name="ru_text" id="edit" cols="30" rows="4"></textarea>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 columns">
                                <label>Published
                                    <select name="published" class="required" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="add_vacancy" value="true">

                        <div class="row">
                            <div class="large-12 columns">
                                <input type="submit" class="button small float-right" value="Create">
                                <a href="/adm/site/vacancy" class="button small info"> Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/views/admin/layouts/footer.php' ?>