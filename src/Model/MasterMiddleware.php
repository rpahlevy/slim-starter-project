<?php

namespace App\Model;

use App\Vars\HTTPCode;
use App\Vars\Session;
use App\Vars\Utils;

class MasterMiddleware
{
    const EXPIRED_TIME = 86400; // 3600 = 1hour

    private static $token_master = '';

    // slim container
    private $c;

    public static function generateToken($username, $password)
    {
        return md5($username . Session::APP_ID . $password);
    }

    public static function login($username, $password)
    {
        if (md5($username . Session::APP_ID . $password) === self::$token_master)
        {
            Session::getInstance()->token_master_created = time();
            return true;
        }

        return false;
    }

    public static function isLoggedIn()
    {
        $session = Session::getInstance();
        $token_master_created   = isset($session->token_master_created) ? $session->token_master_created : '';
        $now                    = time();

        return (
            !empty($token_master_created) &&
            $now - $token_master_created <= self::EXPIRED_TIME
        );
    }

    public function __invoke($req, $rsp, $next)
    {
        /**
         * BEFORE ROUTE
         */

        // check if has session                = time();
        if (!self::isLoggedIn())
        {
            return Utils::redirect($rsp,
                '/login',
                HTTPCode::INVALID_TOKEN,
                'Silahkan login untuk melanjutkan'
            );
        }

        $token_master_created = $now;

        /**
         * /BEFORE ROUTE
         */



        $rsp = $next($req, $rsp);
        


        /**
         * AFTER ROUTE
         */

        /**
         * /AFTER ROUTE
         */

        return $rsp;
    }

    // App's container is auto injected when this middleware added to route/group
    public function __construct($container)
    {
        $this->c = $container;
    }
}