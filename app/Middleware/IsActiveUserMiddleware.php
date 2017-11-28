<?php
namespace Umbrella\app\Middleware;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\User;

class IsActiveUserMiddleware
{
    /**
     * @var User
     */
    private $user;

    /**
     * IsActiveUserMiddleware constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->handle();
    }

    public function handle()
    {
        if($this->user instanceof User){
            //Проверка на доступ к кабинету
            if($this->user->isActive() == 0) {
                Session::destroy('user');
                Url::redirect('/');
            }
        } else {
            throw new \Exception('This object is not an instance of a class');
        }
    }
}