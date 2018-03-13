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

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
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