<?php
namespace Umbrella\app\Middleware;

use Josantonius\Url\Url;
use Umbrella\app\User;

class PartnerRisksMiddleware
{
    /**
     * @var User
     */
    private $user;

    /**
     * PartnerRisksMiddleware constructor.
     *
     * @param User $user
     *
     * @throws \Exception
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->handle();
    }


    /**
     * @throws \Exception
     */
    public function handle()
    {
        if($this->user instanceof User){
            // Проверка на оплаченные счета
            if(Url::getUri() != '/adm/risks'){
                if($this->user->getUserBlockedGM() != 'active'){
                    if($this->user->getUserBlockedGM() == 'tomorrow'
                    || $this->user->getUserBlockedGM() == 'blocked'){

                    } else {
                        Url::redirect(config('app')['url_redirect']['user_risk']);
                    }
                }
            }
        } else {
            throw new \Exception('This object is not an instance of a class');
        }
    }
}