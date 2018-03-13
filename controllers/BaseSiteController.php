<?php
namespace Umbrella\controllers;

use Umbrella\app\Services\Language;
use Umbrella\components\Router;
use Umbrella\vendor\controller\Controller;

/**
 * Class BaseSiteController
 * @package Umbrella\controllers
 */
class BaseSiteController extends Controller
{
    /**
     * @var Language
     */
    public $lang;

    /**
     * BaseSiteController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang = new Language();
    }
}
