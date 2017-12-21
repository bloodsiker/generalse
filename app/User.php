<?php

namespace Umbrella\app;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\Services\crm\StockService;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\DeliveryAddress;
use Umbrella\models\Denied;
use Umbrella\models\File;
use Umbrella\models\GroupModel;
use Umbrella\models\api\hr\Auth;
use Umbrella\models\Innovation;
use Umbrella\models\Stocks;

/**
 * Class User
 */
class User
{
    /**
     * @var
     */
    public $id_user;

    /**
     * @var
     */
    public $name_partner;

    /**
     * @var mixed
     */
    private $infoUser;



    /**
     * User constructor.
     * @param $id_user
     */
    public function __construct($id_user){
        $this->id_user = $id_user;
        $this->infoUser = $this->getInfoUser();
        $this->name_partner = $this->infoUser['name_partner'];
        $this->email = $this->infoUser['email'];
        $this->login = $this->infoUser['login'];
        $this->role = $this->infoUser['role'];
        $this->name_role = $this->infoUser['name_role'];
        $this->country = $this->infoUser['full_name'];
        $this->id_group = $this->infoUser['id_group'];
        $this->group_name = $this->infoUser['group_name'];
        $this->coefficient = $this->infoUser['kpi_coefficient'];
        $this->login_url = $this->infoUser['login_url'];
        $this->is_active = $this->infoUser['is_active'];
        $this->token = $this->getToken();

        $this->activeUser();
    }


    /**
     * Get user ID
     * @return int
     */
    public function getId()
    {
        return $this->id_user;
    }


    /**
     * Get name user
     * @return string
     */
    public function getName()
    {
        return $this->infoUser['name_partner'];
    }


    /**
     * get URL after user authorization
     * @return string
     */
    public function getUrlAfterLogin()
    {
        return $this->infoUser['login_url'];
    }


    /**
     * get user role
     * @return mixed
     */
    public function getRole()
    {
        return $this->infoUser['role'];
    }

    /**
     *
     * Role Administrator
     * @return bool
     */
    public function isAdmin()
    {
        if($this->getRole() == 'administrator' || $this->getRole() == 'administrator-fin'){
            return true;
        }
        return false;
    }


    /**
     * Role Manager
     * @return bool
     */
    public function isManager()
    {
        if($this->getRole() == 'manager'){
            return true;
        }
        return false;
    }


    /**
     * Role Partner
     * @return bool
     */
    public function isPartner()
    {
        if($this->getRole() == 'partner'){
            return true;
        }
        return false;
    }


    /**
     * get group name
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->infoUser['group_name'];
    }


    /**
     * @param $token
     * @return mixed
     */
    public function setToken($token)
    {
        return Auth::updateToken($this->id_user, $token);
    }


    /**
     * return user auth token
     * @return mixed
     */
    public function getToken()
    {
        $user = Admin::getAdminById($this->id_user);
        return isset($user['token']) ? $user['token'] : null;
    }


    /**
     * Доступ к авторизации к проектам (Umbrella, HR)
     * @param $permission
     * @return mixed
     */
    public function getAuthProject($permission)
    {
        $user = Admin::getAdminById($this->id_user);

        if(is_array($permission)){
            if(in_array($user['project'], $permission)){
                return true;
            }
            return false;
        } else {
            if ($user['project'] == $permission) {
                return true;
            }
            return false;
        }
    }


    /**
     * flag, informs that the user's account is not banned
     * @return int
     */
    public function isActive()
    {
        return $this->infoUser['is_active'];
    }


    /**
     * saved user information in session
     * @return array
     * @throws \Exception
     */
    public function getInfoUser() :array
    {
        if(Session::get('info_user') && !empty(Session::get('info_user'))){
            $user = Session::get('info_user');
        } else {
            $userGS = Admin::getAdminById($this->id_user);
            $userGM = Admin::getInfoGmUser($this->id_user);
            if($userGM){
                $userGS['gm'] = Decoder::arrayToUtf($userGM);
            }
            Session::set('info_user', $userGS);
            $user = Session::get('info_user');
        }
        return $user;
    }


    /**
     * information about user from GM
     * @return mixed
     */
    public function getInfoUserGM()
    {
        return $this->infoUser['gm'];
    }


    /**
     * Partner price status
     * @return bool
     */
    public function getUserPriceNameGM()
    {
        if($this->infoUser['gm']){
            return $this->infoUser['gm']['PriceName'];
        }
        return false;
    }


    /**
     * Blocked auth user in Umbrella from GM
     * @return bool
     */
    public function getUserBlockedGM()
    {
        if(isset($this->infoUser['gm'])){

            switch ($this->infoUser['gm']['blocked']){
                case 0:
                    return 'active';
                    break;
                case 1:
                    return 'tomorrow'; //tomorrow
                    break;
                case 2:
                    return 'blocked'; //blocked
                    break;
                default:
                    return 'active';
            }
        }
        return 'active';
    }


    /**
     * write record the last active user action
     * @return bool
     */
    public function activeUser()
    {
        return Admin::userLasTimeOnline($this->id_user, date('Y-m-d H:i:s'));
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
        if($control_users_id){
            $array = array_column($control_users_id, 'control_user_id');
            $new_user = new User($id_user);
            if($new_user->getRole() == 'partner'){
                $array[] = $id_user;
            }
            $new_array = array_reverse($array);
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
        $stockService = new StockService();
        $array_stock = $group->stocksFromGroup($id_group, 'name', $section);
        return $stockService->replaceNameStockInFilter($array_stock, 'replace', $this->getRole());
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
     * @return array
     * @throws \Exception
     */
    public function getDeliveryAddress()
    {
        $delivery_address = Decoder::arrayToUtf(DeliveryAddress::getAddressByPartnerMsSQL($this->id_user));
        return array_column($delivery_address, 'address');
    }


    /**
     * Список нововведений для пользователя
     * @return array|bool
     */
    public function checkNewInnovation()
    {
        $listInnovation = Innovation::getListNewInnovation($this->id_user);
        return count($listInnovation) > 0 ? $listInnovation : false;
    }


    /**
     * get info upload file for group
     * @param null $id_group
     * @param null $partner_status
     * @return mixed
     */
    public function infoFilePriceForUser($id_group = null, $partner_status = null)
    {
        $group = $id_group == null ? $this->id_group : $id_group;

        $partner_status = $partner_status == null ? $this->getUserPriceNameGM() : $partner_status;

        $infoFile = File::getLastUploadFileForGroup($group, $partner_status);

        return $infoFile;
    }


    /**
     * Ссылка на скачивание файла с прайсом
     * @param $id_group
     * @param null $partner_status
     * @return null|string
     */
    public function linkUrlDownloadAllPrice($id_group = null, $partner_status = null)
    {
        $infoFile = $this->infoFilePriceForUser($id_group, $partner_status);

        return $infoFile['file_path'] . $infoFile['file_name'];
    }

    /**
     * Временное решение
     * Название ссылки для скачивания
     * @param null $id_group
     * @param null $partner_status
     * @return mixed
     */
    public function linkNameDownloadAllPrice($id_group = null, $partner_status = null)
    {
        $infoFile = $this->infoFilePriceForUser($id_group, $partner_status);

        return $infoFile['file_name'];
    }

    /**
     * Временное решение
     * Дата последней загрузки файла
     * @param null $id_group
     * @param null $partner_status
     * @return mixed
     */
    public function lastUploadDateAllPrice($id_group = null, $partner_status = null)
    {
        $infoFile = $this->infoFilePriceForUser($id_group, $partner_status);

        return $infoFile['created_at'];
    }


    /**
     * User logout
     */
    public function logout()
    {
        Session::destroy('user');
        Session::destroy('_token');
        Session::destroy('info_user');
        $this->setToken(null);
        Url::redirect('/');
    }
}