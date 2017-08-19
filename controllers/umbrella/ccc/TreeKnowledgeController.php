<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KnowledgeArticle;
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
        self::checkDenied('ccc.tree_knowledge', 'controller');
    }

    /**
     * @param $customer
     * @return bool
     */
    public function actionIndex($customer)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/ccc/knowledge/index', compact('user', 'customer'));
        return true;
    }


    /**
     * Список статей в категории
     * @param $customer
     * @param $id_category
     * @return bool
     */
    public function actionArticlesByCategory($customer, $id_category)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $categoryName = KnowledgeCatalog::getInfoCategoryById($id_category);
        $listCategory = KnowledgeArticle::getArticlesByCategory($id_category);

        $this->render('admin/ccc/knowledge/articles', compact('user', 'id_category', 'listCategory', 'customer', 'categoryName'));
        return true;
    }


    /**
     * Просмотр статьи
     * @param $customer
     * @param $id_category
     * @param $id
     * @return bool
     */
    public function actionViewArticle($customer, $id_category, $id)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $article = KnowledgeArticle::getArticlesById($id);

        $lastViewArticle = KnowledgeCatalog::getLastVisitArticle($user->id_user, 1);

        if (is_array($lastViewArticle)){
            if($lastViewArticle[0]['id_article'] != $id){
                KnowledgeCatalog::userViewArticle($user->id_user, $id);
            }
        }

        $this->render('admin/ccc/knowledge/view', compact('user', 'article', 'customer'));
        return true;
    }


    public function actionSearch($customer, $search)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $search = $_GET['search'];
        $listSearch = KnowledgeArticle::getSearchContent($customer, $search);

        $this->render('admin/ccc/knowledge/search', compact('user', 'customer', 'listSearch'));
        return true;
    }


    /**
     * Популярные статьи
     * @param $customer
     * @return bool
     */
    public function actionPopularCategory($customer)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $popularArticles = KnowledgeArticle::getPopularArticles();

        $this->render('admin/ccc/knowledge/popular_category', compact('user', 'customer', 'popularArticles'));
        return true;
    }
}