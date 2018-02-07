<?php

namespace Umbrella\controllers;


use Umbrella\app\Services\Language;

class LanguageController
{
    public function actionChangeLang()
    {
        $url = new Language();
        echo "<pre>";
        print_r($url);

        return true;
    }
}