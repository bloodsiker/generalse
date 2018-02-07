<?php
namespace Umbrella\controllers\site;

use Umbrella\vendor\controller\Controller;

class ContactsController extends Controller
{

    /**
     * @return bool
     */
    public function actionIndex()
    {

        $this->render('new_site/en/contact');
        return true;
    }
}
