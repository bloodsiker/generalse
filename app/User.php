<?php

/**
 * Class User
 */
class User
{
    public $id_user;

    public function __construct($id_user){
        $infoUser = $this->getInfoUser($id_user);
        $this->id_user = $infoUser['id_user'];
        $this->name_partner = $infoUser['name_partner'];
        $this->login = $infoUser['login'];
        $this->role = $infoUser['role'];
        $this->name_role = $infoUser['name_role'];
        $this->country = $infoUser['full_name'];
        $this->group_name = $infoUser['group_name'];
        $this->coefficient = $infoUser['kpi_coefficient'];

        $this->activeUser($this->id_user,date('Y-m-d H:i:s'));
    }

    /**
     * @param $id_user
     * @return mixed
     */
    public function getInfoUser($id_user)
    {
        $user = Admin::getAdminById($id_user);

        return $user;
    }

    /**
     * @param $id_user
     * @param $date_active
     * @return bool
     */
    public function activeUser($id_user, $date_active)
    {
        Admin::userLasTimeOnline($id_user, $date_active);

        return true;
    }

    /**
     * Получаем массив пользователей, которым может управлять данный пользователь
     * @param $id_user
     * @return array
     */
    public function controlUsers($id_user)
    {
        $control_users_id = Admin::getControlUsersId($id_user);
        $array = [];
        //$stock_users_id[]['use_stock_user_id'] = $id_user;
        //$new_array = array_reverse($stock_users_id);
        if($control_users_id){
            $array = array_column($control_users_id, 'control_user_id');
            $array[] = $id_user;
            $new_array = array_reverse($array);
            //$list = implode(',', $array);
            return $new_array;
        } else {
            $array[0] = $id_user;
            return $array;
        }
    }

    /**
     * Рендерим выпадающий список пользователей
     * @param $id_user
     */
    public function renderSelectControlUsers($id_user)
    {
        $array_stock = self::controlUsers($id_user);
        $option = '';
        foreach ($array_stock as $key => $id_user){
            $option .=  "<option value='{$id_user}'>" . Admin::getNameById($id_user) . "</option>";
        }
        echo $option;
    }

    /**
     * Проверяет, если пользователь в списке пользователей
     * @param $id_user
     * @param $control_user_id
     * @return bool
     */
    public function checkUserInControl($id_user, $control_user_id)
    {
        $array_control_id = Admin::getControlUsersId($id_user);
        $found_key = in_array($control_user_id, array_column($array_control_id, 'control_user_id'));
        if($found_key){
            return true;
        }
        return false;
    }

    /**
     * Список пользователей находящихся в одной группе с данным пользователем
     * @param $id_user
     * @return mixed
     */
    public function idUsersInGroup($id_user)
    {
        $group = new Group();
        return $group->usersFromGroup($this->idGroupUser($id_user));
    }

    /**
     * ID группы в которой состоит пользователь
     * @param $id_user
     * @return mixed
     */
    public function idGroupUser($id_user)
    {
        $id_group = GroupModel::getIdGroupUser($id_user);
        return $id_group['id_group'];
    }


    /**
     * Возвращаем список складов для выбраного раздела
     * @param $id_user
     * @param $section
     * @return array
     */
    public function renderSelectStocks($id_user, $section)
    {
        $id_group = $this->idGroupUser($id_user);
        $group = new Group();
        $array_stock = $group->stocksFromGroup($id_group, 'name', $section);
        return $array_stock;
    }


    /**
     * При добавлении пользователя в группу, применяем для него все правила запрета  которые действуют на группу
     * @param $id_group
     * @param $id_user
     * @return bool
     */
    public function  addDeniedForGroupUser($id_group, $id_user)
    {
        $group = new Group();
        $list_denied = $group->deniedGroupPage($id_group);
        foreach ($list_denied as $denied){
            Denied::addDeniedSlugInUser($id_user, $denied['name'], $denied['slug'], $id_group);
        }
        return true;
    }


    /**
     * Удаляем все запрещенные страницы, которые относяться к группе в которой находился пользователь
     * @param $id_user
     * @param $id_group
     * @return bool
     */
    public function deleteDeniedForGroupUser($id_user, $id_group)
    {
        Denied::deleteDeniedUserFromGroup($id_user, $id_group);
        return true;
    }


    /**
     * Список адресов доставки привязанных к партнеру
     * @param $id_user
     * @return array
     */
    public function getDeliveryAddress($id_user)
    {
        $delivery_address = DeliveryAddress::getAddressByPartner($id_user);
        return array_column($delivery_address, 'address');
    }


    /**
     * Ссылка на скачивание файла с прайсом
     * @return null|string
     */
    public function linkDownloadAllPrice()
    {
        switch ($this->group_name)
        {
            case 'Electrolux':
                return 'Price_Electrolux.xlsx';
                break;
            case 'GE':
                return 'Electrolux_Prices_GE.xlsx';
                break;
            default:
                return null;
        }
    }
}