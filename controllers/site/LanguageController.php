<?php

namespace Umbrella\controllers\site;

use Umbrella\app\Services\Language;

/**
 * Class LanguageController
 * @package Umbrella\controllers\site
 */
class LanguageController
{
    /**
     * @return bool
     */
    public function actionChangeLang()
    {
        $url = new Language();
        $url->changeLang();
        return true;
    }
}