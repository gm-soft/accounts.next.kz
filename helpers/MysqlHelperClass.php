<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 17.11.2016
 * Time: 16:32
 */
class MysqlHelper
{
    const DB_NAME = "accounts.next.kz";
    const TABLE_ACCOUNTS = "accounts";
    const TABLE_USERS = "users";
    const TABLE_SETTINGS = "settings";
    const DB_HOST = "localhost";

    const TABLE_USERS_CREATE = "CREATE TABLE IF NOT EXISTS `".self::TABLE_USERS."` (".
    "`user_id` int(11) unsigned NOT NULL auto_increment, ".
    "`user_login` varchar(30) NOT NULL, ".
    "`user_password` varchar(32) NOT NULL, ".
    "`user_hash` varchar(32) NOT NULL, ".
    "`created_at` DATETIME DEFAULT CURRENT_TIMESTAMP, ".
    "`user_permission` int(10) unsigned NOT NULL default '1', ".
    "PRIMARY KEY (`user_id`), ".
    "UNIQUE (`user_login`)".
    ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

    const TABLE_ACCOUNTS_CREATE = "CREATE TABLE IF NOT EXISTS `".self::TABLE_ACCOUNTS."` (".
    "`account_id` int(11) unsigned NOT NULL auto_increment, ".
    "`account_login` varchar(30) NOT NULL, ".
    "`account_password` varchar(32) NOT NULL, ".
    "`account_available` int(2) NOT NULL DEFAULT 1, ".
    "`account_computer_name` TEXT DEFAULT NULL, ".
    "`account_vac_banned` int(2) NOT NULL DEFAULT 0, ".
    "`account_usage` int(11) NOT NULL DEFAULT 0, ".
    "`account_last_operation` TEXT DEFAULT NULL, ".
    "`created_at` DATETIME DEFAULT CURRENT_TIMESTAMP, ".
    "`updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP, ".
    "PRIMARY KEY (`account_id`), ".
    "UNIQUE (`account_login`)".
    ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

    const TABLE_SETTINGS_CREATE = "";

    private $context = null;


    function __construct($username, $password, $db_name) {
        $this->context = mysqli_connect(self::DB_HOST, $username, $password, self::DB_NAME);

        if (!$this->context){
            $this->context = null;
            return mysqli_connect_error();

        } else {
            $this->context->set_charset("utf8");

            //mysqli_set_charset($this->context, "utf8");
            return true;
        }
    }

    public static function getNewInstance(){
        $instance = new self(DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
        $instance->executeQuery(self::TABLE_USERS_CREATE);
        $instance->executeQuery(self::TABLE_ACCOUNTS_CREATE);
        return $instance;
    }

    /**
     * Функция возврата пользователя из базы данных по искомому значению.
     * Возвращает массив с полями result и data. Если запрос был успешен, то
     * result равен true. Если пользователь был найден, то data будет содержать этот объект,
     * иначе null
     *
     * @param $searchable - Искомое значение
     * @param string $field - названеи поля, по которому осуществлять поиск
     * @return User|null
     */
    public function getUser($searchable, $field = "user_login") {

        $query = "select * from ".self::TABLE_USERS." where ".$field."='".$searchable."'";
        $data = $this->executeQuery($query);

        if (
            $data["result"] != true ||
            is_null($data["data"])
        ) return null;

        return User::fromDatabase($data["data"]);
    }


    /**
     * Возвращает список пользователей системы
     *
     * @param bool $withPagination
     * @param int $startFrom
     * @param int $limit
     * @return User[]|null
     */
    public function getUsers($withPagination = false, $startFrom = 0, $limit = 50){
        $query = "select * from ".self::TABLE_USERS;
        if ($withPagination == true){
            $query .= " LIMIT $startFrom, $limit";
        }

        $query_result = $this->selectData($query);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {
                //$client = new Client();

                $instance = User::fromDatabase($value);
                array_push($instances, $instance);
            }
            $query_result = $instances;
        }

        return $query_result;
    }



    /**
     * Функция добавляет нового пользователя в систему. Возвращает id последней добавленной записи
     *
     * @param $user User
     * @return array(
     *      "result" => true/false,
     *      "data" => id/ошибка
     * )|null
     */
    public function addUser($user){

        $query = "insert into `".self::TABLE_USERS."` (`user_login`, `user_password`, `user_hash`, `user_permission`) values (".
            "'".$user->login."', '".$user->password."', '".$user->hash."', ".$user->permission." )";
        $query_result = $this->executeQuery($query);
        if ($query_result["result"] != true) {
            return $query_result;
        }
        $query_result["data"] = mysqli_insert_id($this->context);;
        return $query_result;
    }

    public function updateUserHash($user){
        $query = "UPDATE `".self::TABLE_USERS."` SET ".
            "`user_hash`='".$user->hash."'".
            " where user_id=".$user->id;
        $query_result = $this->executeQuery($query);

        if ($query_result["result"] != true) {
            return $query_result;
        }
        //$query_result["data"] = mysqli_insert_id($this->context);;
        //$id = mysqli_insert_id($this->context);
        return $query_result;
    }

    /**
     * Обновляет пользовательские данные
     *
     * @param $user User
     * @return array|null
     */
    public function updateUser($user){
        $query = "update `".self::TABLE_USERS."` set ".
            "`user_login`='".$user->login."', ".
            "`user_password`='".$user->password."', ".
            "`user_permission`='".$user->permission."', ".
            "`user_hash`='".$user->hash."'".
            " where id=".$user->id;
        $query_result = $this->executeQuery($query);

        if ($query_result["result"] != true) {
            return $query_result;
        }
        //$query_result["data"] = mysqli_insert_id($this->context);;
        //$id = mysqli_insert_id($this->context);
        return $query_result;
    }

    /**
     * Удаляет пользователя из системы
     *
     * @param $user
     * @return array|null
     */
    public function deleteUser($user){
        $searchable = $user->id;
        $field = "id";
        $tableName = self::TABLE_USERS;
        return $this->deleteInstance($searchable, $field, $tableName);
    }


    /**
     * Возвращает массив всех стим-аккаунтов
     *
     * @param bool $withPagination
     * @param int $startFrom
     * @param int $limit
     * @return SteamAccount[] |null
     */
    public function getSteamAccounts($withPagination = false, $startFrom = 0, $limit = 50){
        $query = "select * from ".self::TABLE_ACCOUNTS;
        if ($withPagination == true){
            $query .= " LIMIT $startFrom, $limit";
        }

        $query_result = $this->selectData($query);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {
                //$client = new Client();

                $instance = SteamAccount::fromDatabase($value);
                array_push($instances, $instance);
            }
            $query_result = $instances;
        }

        return $query_result;
    }

    /**
     * Возвращает массив всех стим-аккаунтов с фильтрацией
     *
     * @param array $filterConditions - массив условий вида "id = 1"
     * @param string $condition - соединитель условий: AND/OR
     * @return null|SteamAccount[]
     */
    public function filterSteamAccounts(array $filterConditions, $condition = "and"){
        $query = "select * from ".self::TABLE_ACCOUNTS." where ";

        for ($i = 0; $i < count($filterConditions); $i++){
            $field = $filterConditions[$i];

            $query .= $field;
            if ($i == count($filterConditions) - 1) {
                $query .= " ".$condition. " ";
            }


        }

        $query_result = $this->selectData($query);
        if ($query_result["result"] == true) {
            $instances = array();
            foreach ($query_result["data"] as $key => $value) {
                //$client = new Client();

                $instance = SteamAccount::fromDatabase($value);
                array_push($instances, $instance);
            }
            $query_result = $instances;
        }

        return $query_result;
    }


    /**
     * Добавляет новый аккаунт в БД
     *
     * @param $instance SteamAccount
     * @return array("result" => true/false, "data" => id)|null
     */
    public function addSteamAccount($instance){
        $query = "insert into `".self::TABLE_ACCOUNTS."` (`account_login`, `account_password` ) values (".
            "'".$instance->login."', ".
            "'".$instance->password."' ".
            ")";
        $query_result = $this->executeQuery($query);
        if ($query_result["result"] != true) {
            return $query_result;
        }

        $query_result["data"] = mysqli_insert_id($this->context);;
        //$id = mysqli_insert_id($this->context);
        return $query_result;
    }

    /**
     * Обновляет запись аккаунта в БД
     *
     * @param $instance SteamAccount
     * @return array("result" => true/false, "data" => id)
     */
    public function updateSteamAccount($instance){
        $createDate = date("Y-m-d H:i:s", $instance->createdAt->getTimestamp());
        $updateDate = date("Y-m-d H:i:s", $instance->updatedAt->getTimestamp());

        $query = "UPDATE `".self::TABLE_ACCOUNTS."` SET ".
            "`account_login`='".$instance->login."', ".
            "`account_password`='".$instance->password."', ".
            "`account_available`=".$instance->available.", ".
            "`account_computer_name`='".$instance->computerName."',".
            "`account_vac_banned`=".$instance->vacBanned.", ".
            "`account_usage`=".$instance->usageTimes.", ".
            "`account_last_operation`='".$instance->lastOperation."'', ".
            "`created_at`=".$createDate.", ".
            "`updated_at`=".$updateDate." ".
            " where account_id=".$instance->id;
        $query_result = $this->executeQuery($query);

        if ($query_result["result"] != true) {
            return $query_result;
        }
        //$query_result["data"] = mysqli_insert_id($this->context);;
        //$id = mysqli_insert_id($this->context);
        return $query_result;
    }

    /**
     * Удаляет аккаунт
     *
     * @param $instance SteamAccount
     * @return array("result" => true/false, "data" => id)
     */
    public function deleteAccount($instance){
        return $this->deleteInstance($instance->id, "account_id", self::TABLE_ACCOUNTS);
    }

    /**
     * Возвращает аккаунт по полю поиска
     *
     * @param $searchable - искомое значение
     * @param string $field - поле бд для поиска
     * @return null|SteamAccount
     */
    public function getSteamAccount($searchable, $field = "account_id"){
        $query = "select * from ".self::TABLE_ACCOUNTS." where ".$field."='".$searchable."'";
        $query_result = $this->executeQuery($query);
        if ($query_result["result"] == true && !is_null($query_result["data"])) {
            $instance = SteamAccount::fromDatabase($query_result["data"]);
            return $instance;
        }
        return null;
    }

    private function deleteInstance($searchable, $field, $tableName){
        $query = "delete from ".$tableName." where ".$field."='".$searchable."'";
        return $this->executeQuery($query);
    }





    function selectData($query) {
        if (is_null($this->context )) return null;
        $data = mysqli_query($this->context, $query);

        if ($data) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($data)) {
                array_push($rows, $row);
            }
            $data = $rows;

            $result = true;

        } else {
            $data = mysqli_error($this->context);
            $result = false;
        }
        return array(
            "result" => $result,
            "data" => $data);
    }

    /**
     * Выполняет SQL запрос
     *
     * @param $query
     * @return array("result" => true/false, "data" => array())|null
     */
    public function executeQuery($query) {

        if (is_null($this->context )) return null;
        $data = mysqli_query($this->context, $query);

        if ($data) {

            $data = !is_bool($data) ? mysqli_fetch_assoc($data) : $data;
            //ApplicationHelper::debug("query = ".$query." data = ".var_export($data, true));
            $result = true;

        } else {
            $data = mysqli_error($this->context);
            $result = false;
        }
        return array("result" => $result, "data" => $data);

    }

    public function getCharset(){
        return $this->context->character_set_name();
    }
}