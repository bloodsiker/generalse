<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KnowledgeCatalog;

/**
 * Древо знаний для Customer Care Center
 * Class TreeKnowledgeController
 */
class TreeKnowledgeController extends AdminBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $category = KnowledgeCatalog::getAllCategories();

        $this->render('admin/ccc/knowledge/index', compact('user', 'category'));
        return true;
    }


    /**
     * @param $id_category
     * @return bool
     */
    public function actionArticlesByCategory($id_category)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/ccc/knowledge/articles', compact('user'));
        return true;
    }


    public function actionPopularCategory()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/ccc/knowledge/popular_category', compact('user'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionCategory()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/ccc/knowledge/index', compact('user'));
        return true;
    }
}