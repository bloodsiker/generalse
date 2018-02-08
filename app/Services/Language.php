<?php

namespace Umbrella\app\Services;

use Josantonius\Url\Url;

/**
 * Class Language
 * @package Umbrella\app\Services
 */
class Language
{

    private $prev_url;

    private $current_url;

    private $lang_array = ['ru', 'ua', 'en'];

    /**
     * Language constructor.
     */
    public function __construct()
    {
        $this->getPrevUrl();
        $this->getCurrentUrl();
    }

    /**
     * @return mixed
     */
    public function getPrevUrl()
    {
        return $this->prev_url = $_SERVER['HTTP_REFERER'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getCurrentUrl()
    {
        return $this->current_url = $_SERVER['REQUEST_URI'];
    }

    /**
     * @param $path
     *
     * @return array
     */
    public function getSegmentPath($path)
    {
        return explode('/', $path);
    }

    /**
     *
     */
    public function changeLang()
    {
        $prev_segment = parse_url($this->prev_url);
        $lang = $this->getCurrentLang();

        $path = $this->getSegmentPath($prev_segment['path']);

        if($lang == 'en'){
            unset($path[1]);
        } else {
            $key = false;
            foreach ($this->lang_array as $value){
                $key = array_search($value, $path);
                if($key){
                    break;
                }
            }
            $path[$key] = '/' . $lang;
        }
        Url::redirect(implode('/', $path));
    }


    /**
     * return current language
     *
     * @param null $param
     *
     * @return string
     */
    public function getCurrentLang($param = null)
    {
        $current_segment = parse_url($this->current_url);
        $lang_path = $this->getSegmentPath($current_segment['path']);

        $lang_path = $param == 'controller' ? $lang_path[1] : $lang_path[2];

        switch ($lang_path)
        {
            case 'ru':
                $lang = 'ru';
                break;
            case 'en':
                $lang = 'en';
                break;
            case 'ua':
                $lang = 'ua';
                break;
            default:
                $lang = 'en';
        }
        return $lang;
    }
}