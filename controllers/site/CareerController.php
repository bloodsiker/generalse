<?php
namespace Umbrella\controllers\site;

use Umbrella\vendor\controller\Controller;

class NewsController extends Controller
{

    /**
     * @return bool
     */
    public function actionIndex()
    {

        $this->render('new_site/en/news/index');
        return true;
    }

    public function actionNew($slug)
    {

        $this->render('new_site/en/news/show_new');
        return true;
    }
}
