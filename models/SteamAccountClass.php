<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 06.01.2017
 * Time: 8:49
 */
class SteamAccount
{
    /**
     * ID аккаунта в системе
     * @var int
     */
    public $id;

    /**
     * Логин аккаунта
     * @var string
     */
    public $login;

    /**
     * Пароль аккаунта
     * @var string
     */
    public $password;

    /**
     * Статус доступности аккаунта. true, если свободен, и false - если занят
     * @var bool
     */
    public $available;

    /**
     * Имя компьютера, взявшего аккаунт
     * @var string
     */
    public $computerName;

    /**
     * Статус VAC-бана аккаунта. true, если забанен, и false - если нет
     * @var bool
     */
    public $vacBanned;

    /**
     * Время последнего обновления аккаунта
     * @var DateTime
     */
    public $updatedAt;

    /**
     * Время создания аккаунта
     * @var DateTime
     */
    public $createdAt;

    function __construct($id = -1)
    {
        $this->id = $id;
        $this->login = null;
        $this->password = null;
        $this->available = true;
        $this->computerName = null;
        $this->vacBanned = false;

        $this->updatedAt = new DateTime();
        $this->createdAt = new DateTime();
    }

    protected function fill( array $row ) {
        $this->id = $row["account_id"];
        $this->login = $row["account_login"];
        $this->password = $row["account_password"];
        $this->available = $row["account_available"];
        $this->computerName = $row["account_computer_name"];

        $this->updatedAt = DateTime::createFromFormat("Y-m-d H:m:S", $row["updated_at"]); // 2017-01-05 14:17:19
        $this->createdAt = DateTime::createFromFormat("Y-m-d H:m:S", $row["created_at"]);

        // $this->updatedAt->setTimezone(new DateTimeZone('Asia/Almaty'));
        // $this->createdAt->setTimezone(new DateTimeZone('Asia/Almaty'));
    }

    /**
     * Создает аккаунт из строки базы данных
     *
     * @param array $databaseRow
     * @return SteamAccount
     */
    public static function fromDatabase(array $databaseRow)
    {
        $instance = new self();
        $instance->fill( $databaseRow );
        return $instance;
    }

    public static function fromJson($jsonString){
        
    }

    /**
     * Создает аккаунт из логина и пароля с другими полями по дефолту
     *
     * @param $login
     * @param $password
     * @return SteamAccount
     */
    public static function fromData($login, $password)
    {
        $instance = new self();
        $instance->login = $login;
        $instance->password = $password;

        return $instance;
    }

    public function getJson(){
        $jsonString = "{".
            "Id : ".$this->id.",".
            "Login : \"".$this->login."\",".
            "Password : \"".$this->password."\",".
            "Available : ".$this->available.",".
            "ComputerName : \"".$this->computerName."\",".
            "VacBanned : ".$this->vacBanned.",".
            "}";
        return $jsonString;
    }

}