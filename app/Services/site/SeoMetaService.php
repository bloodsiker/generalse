<?php

namespace Umbrella\app\Services\site;

use Umbrella\models\site\SeoMeta;

class SeoMetaService
{
    private $lang;

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
    }


    /**
     * @param $pagename
     *
     * @return mixed
     */
    public function getSeoForPage($pagename)
    {
        $seo = SeoMeta::getSeoForPage($pagename);

        $result['title'] = $seo[$this->lang .'_title'];
        $result['description'] = $seo[$this->lang .'_description'];
        $result['keywords'] = $seo[$this->lang .'_keywords'];
        return $result;
    }

}