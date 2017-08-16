<?php

namespace Umbrella\app\Services;

use Umbrella\models\Lithographer;

/**
 * Class UserService
 */
class UserService
{
    private $id_user;

    /**
     * UserService constructor.
     * @param $id_user
     */
    public function __construct($id_user)
    {
        $this->id_user = $id_user;
    }

    /**
     * Закрываем доступ к просмотру в литографе для юзера с ролью партнер
     * @return bool
     */
    public function addDeniedLithograph()
    {
        $ids = $this->getAllArticles();
        if(is_array($ids)){
            foreach ($ids as $article_id){
                Lithographer::addUserViewClose($this->id_user , $article_id);
            }
        }
        return true;
    }

    /**
     * Список всех статей в литографе
     * @return array
     */
    public function getAllArticles()
    {
        $allArticles = Lithographer::getAllContentByAdmin();
        $ids = array_column($allArticles, 'id');
        return $ids;
    }

}