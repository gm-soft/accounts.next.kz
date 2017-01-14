<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 09.01.2017
 * Time: 10:03
 */
class ApiMessage
{
    /**
     * Коды сообщений: 200 или 404, к примеру
     *
     * @var int
     */
    public $Code = 200;

    /**
     * Хранит код сообщения: GetAccount - взятие аккаунта, ReleaseAccount - освобождение аккаунта, UsingAccount - сообщение об использовании аккаунта
     *
     * @var string|null
     */
    public $RequestType = null;

    /**
     * Хранит json-закодированный объект: аккаунт, к примеру
     *
     * @var string|null
     */
    public $JsonObject = null;

    /**
     * Хранит json-закодированный объект отправителя сообщения
     *
     * @var string|null
     */
    public $JsonSender = null;

    /**
     * Для хранения некоего сообщения в строке
     *
     * @var string|null
     */
    public $StringMessage = null;

    /**
     * Требуется ли свободный от бана аккаунт
     *
     * @var bool
     */
    public $VacBanFree = false;

    function __construct()
    {

    }

    public static function createApiMessage($type = "GetAccount", $jsonObject = null, $message = null, $code = 200){
        $instance = new self();
        $instance->Code = $code;
        $me = [
            "IpAddress" => "http://accounts.next.kz/",
            "Name" => "http://accounts.next.kz/",
            "AppType" => "Server",
            "AppVersion" => "1"
        ];
        $instance->JsonSender = json_encode($me);
        $instance->RequestType = $type;
        $instance->JsonObject = $jsonObject;
        $instance->StringMessage = $message;
        return $instance;
    }

    public static function fromJson($jsonString){

        $json = json_decode($jsonString, true);
        if (is_null($json)) return null;
        $instance = new self();

        $instance->Code = $json["Code"];
        $instance->RequestType = $json["RequestType"];
        $instance->JsonObject = $json["JsonObject"];
        $instance->JsonSender = $json["JsonSender"];
        $instance->StringMessage = $json["StringMessage"];
        $instance->VacBanFree = filter_var($json["VacBanFree"], FILTER_VALIDATE_BOOLEAN);

        return $instance;
    }
}