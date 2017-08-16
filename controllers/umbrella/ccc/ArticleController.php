<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KnowledgeArticle;
use Umbrella\models\ccc\KnowledgeCatalog;

class ArticleController extends AdminBase
{
    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('ccc.tree_knowledge.article', 'controller');
    }


    /**
     * Страница списка статей
     * @return bool
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $listArticles = KnowledgeArticle::getAllArticlesAdmin();
        $listCustomer = KnowledgeCatalog::getCustomerInCategory();

        $renderOptions = KnowledgeCatalog::subCategoryList(0);

        if((isset($_REQUEST['add-article']) && $_REQUEST['add-article'] == 'true')){
            $options['id_category'] = $_REQUEST['id_category'];
            $options['id_user'] = $user->id_user;
            $options['title'] = $_REQUEST['title'];
            $options['description'] = $_REQUEST['description'];
            $options['text'] = $_REQUEST['text'];
            $options['published'] = $_REQUEST['published'];

            $ok = KnowledgeArticle::addArticle($options);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        $this->render('admin/ccc/article/index', compact('user', 'listArticles', 'listCustomer', 'renderOptions'));
        return true;
    }


    /**
     * Редактирование статьи
     * @param $id_article
     * @return bool
     */
    public function actionEditArticle($id_article)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $article = KnowledgeArticle::getArticlesById($id_article);
        $renderOptions = KnowledgeCatalog::subCategoryList(0, $article['id_category']);

        if((isset($_REQUEST['edit-article']) && $_REQUEST['edit-article'] == 'true')){
            $options['id_category'] = $_REQUEST['id_category'];
            $options['title'] = $_REQUEST['title'];
            $options['description'] = $_REQUEST['description'];
            $options['text'] = $_REQUEST['text'];
            $options['published'] = $_REQUEST['published'];
            $options['updated_at'] = date('Y-m-d H:i:s');

            $ok = KnowledgeArticle::updateArticleById($id_article, $options);
            if($ok){
                header("Location: /adm/ccc/tree_knowledge/articles");
            }
        }
        $this->render('admin/ccc/article/edit', compact('user', 'article', 'renderOptions'));
        return true;
    }


    /**
     * @param $id_article
     * @return bool
     */
    public function actionDeleteArticle($id_article)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $ok = KnowledgeArticle::deleteArticleById($id_article);
        if($ok){
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }
}