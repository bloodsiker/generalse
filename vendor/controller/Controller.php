<?php

namespace Umbrella\vendor\controller;

use Umbrella\app\Services\Language;
use Umbrella\vendor\view\View;
/**
 * Class Controller
 */
class Controller
{
    /**
     * @var View
     */
    public $view;

    public $lang;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->lang = new Language();
    }


    /**
     * @param $template
     * @param array $params
     */
    public function render($template, $params = array())
    {
        return $this->view->render($template, $params);
    }
}