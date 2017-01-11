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
        setcookie("expired", "", $expired, "/");
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
        setcookie("expired", $expired, $expired, "/");
        return true;
    }

    /**
     * Возвращает текущего залогиненного пользователя
     *
     * @param MysqlHelper|null $mysql
     * @return null|User
     */
    public static function GetCurrentUser($mysql = null){
        $mysql = is_null($mysql) ? MysqlHelper::getNewInstance() : $mysql;
        $hash = isset($_COOKIE["hash"]) ? $_COOKIE["hash"] : null;

        if (is_null($hash)) return null;
        $user = $mysql->getUser($hash, "user_hash");
        return $user;
    }
}