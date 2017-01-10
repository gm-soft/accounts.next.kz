<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 19.11.2016
 * Time: 10:11
 */
class User
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var int
     */
    public $permission;

    /**
     * @var DateTime
     */
    public $created_at;

    function __construct($id = -1)
    {
        $this->id = $id;
        $this->login = null;
        $this->password = null;
        $this->permission = 1;
        $this->created_at = time();
        $this->hash = null;
    }

    protected function fill( array $row ) {
        $this->id = $row["user_id"];
        $this->login = $row["user_login"];
        $this->password = $row["user_password"];
        $this->permission = $row["user_permission"];
        $this->hash = $row["user_hash"];

        //$this->created_at = $row["created_at"];
        $this->created_at = DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]);
    }

    public static function fromDatabase(array $databaseRow)
    {
        $instance = new self();
        $instance->fill( $databaseRow );
        return $instance;
    }

    public static function fromUserData($login, $password)
    {
        $instance = new self();
        $instance->login = $login;
        $instance->password = md5(md5($password));

        $instance->hash = md5(self::generateCode(10));
        return $instance;
    }

    public function updateHash(){
        $this->hash = md5(self::generateCode(10));
    }

    /**
     * Генерирует строку со случайным набором чисел и символов
     *
     * @param int $length
     * @return string
     */
    public static function generateCode($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }

        return $code;

    }

    /**
     * Возвращает true/false в зависимости от равенства введенного пароля и пароля юзера
     *
     * @param $password - пароль в исходном виде
     * @return bool
     */
    public function validatePassword($password){
        $password = md5(md5($password));
        $check = $this->password == $password;
    
        return $check;
    }

    public function checkPermission($requiredLevel){
        return $this->permission >= $requiredLevel;
    }





}