<?php

namespace Umbrella\vendor\controller;

use Josantonius\Url\Url;
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


    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        Url::redirect('/ru/404');
    }
}