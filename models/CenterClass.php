<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 12.01.2017
 * Time: 7:47
 */
class Center
{
    /**
     * ID объекта
     * @var int
     */
    public $id;

    /**
     * Название объекта
     * @var string
     */
    public $name;

    /**
     * Стринговый код объекта
     * @var string
     */
    public $code;

    /**
     * Лимит аккаунтов объекта
     * @var int
     */
    public $limit;

    /**
     * Кол-во используемых аккаунтов
     * @var int
     */
    public $count;

    /**
     * Описание объекта. Не является обязательным
     * @var string
     */
    public $description;

    /**
     * Когда был создан
     * @var DateTime
     */
    public $createdAt;

    /**
     * Когда был обновлен в последний раз
     * @var DateTime
     */
    public $updatedAt;

    function __construct($id = -1)
    {
        $this->id = $id;
        $this->name = null;
        $this->code = null;
        $this->limit = 50;
        $this->count = 0;
        $this->description = null;

        $this->createdAt = new DateTime();
        $this->updatedAt = $this->createdAt;
    }

    protected function fill( array $row ) {
        $this->id = $row["center_id"];
        $this->name = $row["center_name"];
        $this->code = $row["center_code"];
        $this->limit = intval($row["center_limit"]);
        $this->count = intval($row["center_count"]);
        $this->description = $row["center_description"];


        $this->updatedAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["updated_at"]); // 2017-01-05 14:17:19
        $this->createdAt = DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]);

        // $this->updatedAt->setTimezone(new DateTimeZone('Asia/Almaty'));
        // $this->createdAt->setTimezone(new DateTimeZone('Asia/Almaty'));
    }

    /**
     * Создает объект из строки базы данных
     *
     * @param array $databaseRow
     * @return Center
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
     * @param $name
     * @param $code
     * @param int $limit
     * @return Center
     */
    public static function fromData($name, $code, $limit = 50)
    {
        $instance = new self();
        $instance->name = $name;
        $instance->code = $code;
        $instance->limit = $limit;

        return $instance;
    }

    /**
     * Возвращает массив с данными для заполнения формы
     *
     * @return array
     */
    public function getAsFormData(){

        $formData = [
            "center_id" => $this->id,
            "center_name" => $this->name,
            "center_code" => $this->code,
            "center_limit" => $this->limit,
            "center_count" => $this->count,
            "center_description" => $this->description
        ];
        return $formData;
    }
}