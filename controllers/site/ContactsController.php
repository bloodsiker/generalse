<?php
namespace Umbrella\controllers\site;

use Umbrella\vendor\controller\Controller;

class MainController extends Controller
{

    /**
     * @return bool
     */
    public function actionIndex()
    {

        $this->render('new_site/en/index');
        return true;
    }
}
