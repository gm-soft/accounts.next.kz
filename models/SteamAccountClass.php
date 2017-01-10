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
     * Хранит количество "использований" на ПК
     *
     * @var int
     */
    public $usageTimes;

    /**
     * Хранит строку с последним действием с аккаунтом
     *
     * @var string
     */
    public $lastOperation;

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

        $this->usageTimes = 0;
        $this->lastOperation = null;

        $this->updatedAt = new DateTime();
        $this->createdAt = new DateTime();
    }

    protected function fill( array $row ) {
        $this->id = $row["account_id"];
        $this->login = $row["account_login"];
        $this->password = $row["account_password"];
        $this->available = filter_var($row["account_available"], FILTER_VALIDATE_BOOLEAN);
        $this->computerName = $row["account_computer_name"];
        $this->vacBanned = filter_var($row["account_vac_banned"], FILTER_VALIDATE_BOOLEAN);

        $this->usageTimes = intval($row["account_usage"]);
        $this->lastOperation = $row["account_last_operation"];

        $this->updatedAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["updated_at"]); // 2017-01-05 14:17:19
        $this->createdAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]);

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

    /**
     * Создает аккаунт из логина и пароля с другими полями по дефолту
     *
     * @param $login
     * @param $password
     * @param bool $vacBanned
     * @return SteamAccount
     */
    public static function fromData($login, $password, $vacBanned = false)
    {
        $instance = new self();
        $instance->login = $login;
        $instance->password = $password;
        $instance->vacBanned = $vacBanned;

        return $instance;
    }

    /**
     * Возвращает JSON-формат некоторых полей для передачи клиенту
     *
     * @return string
     */
    public function getJson(){
        $banned = $this->vacBanned == true ? "true" : "false" ;
        $jsonString = "{".
            "Id : ".$this->id.",".
            "Login : \"".$this->login."\",".
            "Password : \"".$this->password."\",".
            "Available : ".$this->available.",".
            "ComputerName : \"".$this->computerName."\",".
            "VacBanned : ".$banned."".
            "}";
        return $jsonString;
    }

    /**
     * Возвращает массив с данными для заполнения формы
     *
     * @return array
     */
    public function getAsFormData(){
        $createdAt = date("Y-m-d H:i:s", $this->createdAt->getTimestamp());
        $updatedAt = date("Y-m-d H:i:s", $this->updatedAt->getTimestamp());

        $available = $this->available == true ? "true" : "false";
        $vacBanned = $this->vacBanned == true ? "true" : "false";

        $formData = [
            "account_id" => $this->id,
            "account_login" => $this->login,
            "account_password" => $this->password,
            "account_available" => $available,
            "account_vac_banned" => $vacBanned,
            "account_computer_name" => $this->computerName
        ];
        return $formData;
    }


}