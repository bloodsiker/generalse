<?php

namespace Umbrella\app;

use Umbrella\models\Denied;

/**
 * Запрет пользователю просматривать раздел
 * Class UserAccess
 */
class UserDenied
{

    /**
     * @var User
     */
    private $user;

    /**
     * UserDenied constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Проверяем, находиться ли данный раздел в списке закрытых для этого пользователя
     * @param $section
     * @param $column
     * @return bool
     */
    public function checkUserInDeniedList($section, $column)
    {
        $list_denied = self::deniedSection($this->user->id_user);

        if(is_array($list_denied)){
            $array_slug = array_column($list_denied, $column);

            if(in_array($section, $array_slug)){
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * Получаем массив с разделами закрытыми для посещения
     * @param $id_user
     * @return array
     */
    public function deniedSection($id_user)
    {
        return Denied::getDeniedByUser($id_user);
    }
}