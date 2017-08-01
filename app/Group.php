<?php

namespace Umbrella\app;

use Umbrella\models\Denied;
use Umbrella\models\GroupModel;

/**
 * Class Group
 */
class Group
{
    /**
     * Group constructor.
     */
    public function __construct()
    {

    }


    /**
     * Название группы
     * @param $id_group
     * @return mixed
     */
    public function getNameGroup($id_group)
    {
        $group = GroupModel::getNameGroup($id_group);
        return $group['group_name'];
    }


    /**
     * ID пользователей состоящий в группе
     * @param $id_group
     * @return array
     */
    public function usersFromGroup($id_group)
    {
        $users_group = GroupModel::getUsersByGroup($id_group);
        return array_column($users_group, 'id_user');
    }



    /**
     * Проверяем, состоит ли пользователь в группах
     * @param $id_user
     * @return bool
     */
    public function checkUserInGroups($id_user)
    {
        $list_users = GroupModel::getAllUsersGroup();
        $found_key = in_array($id_user, array_column($list_users, 'id_user'));
        if($found_key){
            return true;
        }
        return false;
    }


    /**
     * Возращаем список id(или названий) складов
     * @param $id_group
     * @param string $key
     * @param $section
     * @return array
     */
    public function stocksFromGroup($id_group, $key = 'id', $section)
    {
        $list_stock = GroupModel::getStocksFromGroup($id_group, $section);
        if($key == 'id'){
            return array_column($list_stock, 'id_stock');
        } elseif($key == 'name'){
            return array_column($list_stock, 'stock_name');
        }
    }


    /**
     * Проверяем, есть ли склад в группе
     * @param $id_group
     * @param $id_stock
     * @param $section
     * @return bool
     */
    public function checkStockInGroups($id_group, $id_stock, $section)
    {
        $list_stock = GroupModel::getStocksFromGroup($id_group, $section);
        $found_key = in_array($id_stock, $this->stocksFromGroup($id_group, 'id', $section));
        if($found_key){
            return true;
        }
        return false;
    }


    /**
     * Список страниц запрещенных для просмотра пользователей группы
     * @param $id_group
     * @return array
     */
    public function deniedGroupPage($id_group)
    {
        return Denied::getDeniedByGroup($id_group);
    }


    /**
     * При добавлении нового запрета для группы, добавляем этот запрет и каждому пользователю
     * @param $array_user
     * @param $name
     * @param $slug
     * @param $id_group
     * @return bool
     */
    public function  addDeniedUserFromGroup($array_user, $name, $slug, $id_group)
    {
        foreach ($array_user as $id_user){
            Denied::addDeniedSlugInUser($id_user, $name, $slug, $id_group);
        }
        return true;
    }


    /**
     * При удалении запретной страницы из группы, она удаляеться у всех членов группы
     * @param $id_group
     * @param $slug
     * @return bool
     */
    public function deleteDeniedForGroupUser($id_group, $slug)
    {
        Denied::deleteUserFromGroupByDenied($id_group, $slug);
        return true;
    }
}