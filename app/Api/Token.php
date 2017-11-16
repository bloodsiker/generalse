<?php

namespace Umbrella\app\Api;


class Token
{

    public function __construct()
    {

    }


    /**
     * generate token for user
     * @return string
     */
    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}