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
    const DB_HOST = "localhost";

    const TABLE_USERS_CREATE = "CREATE TABLE IF NOT EXISTS `".self::TABLE_USERS."` (".
    "`user_id` int(11) unsigned NOT NULL auto_increment, ".
    "`user_login` varchar(30) NOT NULL, ".
    "`user_password` varchar(32) NOT NULL, ".
    "`user_hash` varchar(32) NOT NULL, ".
    "`created_at` DATETIME DEFAULT CURRENT_TIMESTAMP, ".
    "`user_permission` int(10) unsigned NOT NULL default '1', ".
    "PRIMARY KEY (`user_id`) ".
    ") ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;";

    const TABLE_ACCOUNTS_CREATE = "CREATE TABLE IF NOT EXISTS `".self::TABLE_ACCOUNTS."` (".
    "`account_id` int(11) unsigned NOT NULL auto_increment, ".
    "`account_login` varchar(30) NOT NULL, ".
    "`account_password` varchar(32) NOT NULL, ".
    "`user_hash` varchar(32) NOT NULL, ".
    "`user_permission` int(10) unsigned NOT NULL default '1', ".
    "PRIMARY KEY (`user_id`) ".
    ") ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 IF NOT EXISTS;";

    private $context = null;


    function __construct($username, $password, $db_name) {
        $this->context = mysqli_connect(self::DB_HOST, $username, $password, self::DB_NAME);

        if (!$this->context){
            $this->context = null;
            return mysqli_connect_error();

        } else {
            $this->context->set_charset("utf8");
            $this->executeQuery(self::TABLE_USERS_CREATE);
            //mysqli_set_charset($this->context, "utf8");
            return true;
        }
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
     * Функция добавляет нового пользователя в систему. Возвращает id последней добавленной записи
     *
     * @param $user User
     * @return array(
     *      "result" => true/false,
     *      "data" => id/ошибка
     * )|null
     */
    public function addUser($user){

        $query = "insert into `".self::TABLE_USERS."` (`user_login`, `user_password`, `user_hash`) values (".
            "'".$user->login."', '".$user->password."', '".$user->hash."' )";
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

    public function executeQuery($query) {

        if (is_null($this->context )) return null;
        $data = mysqli_query($this->context, $query);

        if ($data) {

            $data = mysqli_fetch_assoc($data);
            log_debug("query = ".$query." data = ".var_export($data, true));
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