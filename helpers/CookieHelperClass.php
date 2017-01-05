<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 05.01.2017
 * Time: 19:12
 */
class CookieHelper
{
    /**
     * Очищает установленные куки
     *
     * @return bool
     */
    public static function ClearCookies(){
        $expired = time() - 3600;
        setcookie("login", "", $expired, "/");
        setcookie("hash", "", $expired, "/");
        setcookie("ts", "", $expired, "/");
        return true;
    }


    /**
     * Устанавливает необходимые куки
     *
     * @param $user User
     * @return bool
     */
    public static function SetUserSession($user){
        $ts = time();
        $expired  = time()+COOKIES_EXPIRED_TIME;
        
        setcookie("login", $user->login, $expired, "/");
        setcookie("hash", $user->hash, $expired, "/");
        setcookie("ts", $ts, $expired, "/");
        return true;
    }
}