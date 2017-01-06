<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 06.01.2017
 * Time: 8:11
 */
class ApplicationHelper
{
    /**
     * Производит перенаправление пользователя на заданный адрес
     *
     * @param string $url адрес
     */
    public static function redirect($url)
    {
        header("HTTP 302 Found");
        header("Location: ".$url);
        die();
    }

    /**
     * Совершает запрос с заданными данными по заданному адресу. В ответ ожидается JSON
     *
     * @param string $method GET|POST - тип запроса
     * @param string $url адрес
     * @param array|null $data параметры запроса: Post или Get аргументы
     *
     * @return array
     */
    public static function query($method, $url, $data = null)
    {
        $query_data = "";

        $curlOptions = array(
            CURLOPT_RETURNTRANSFER => true
        );

        if($method == "POST")
        {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
        }
        elseif(!empty($data))
        {
            $url .= strpos($url, "?") > 0 ? "&" : "?";
            $url .= http_build_query($data);
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt_array($curl, $curlOptions);
        $result = curl_exec($curl);
        //ApplicationHelper::debug($result);
        return json_decode($result, 1);
    }

    /**
     * @param null $format
     * @param null $time
     * @return false|null|string
     */
    public static function formatTime($format = null, $time = null) {

        $time = is_null($time) ? time() + (60*60*6) : $time;
        $result = null;
        switch ($format) {
            case "atom":
                $result = date("c", $time);
                break;

            default:
                $result = date("d.m.y - H:i", $time);
                break;
        }
        return $result;
    }

    /**
     * @param $exception
     */
    public static function processException($exception){

    }

    /**
     * Дозаписывает строку в файл /log/errors.log
     *
     * @param $errorText
     * @return bool
     */
    public static function processError($errorText) {
        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/errors.log";
        $content = "[".self::formatTime("atom")."] ".$errorText."\n";
        $append = "APPEND";
        return self::writeToFile($filename, $content, $append);
    }

    /**
     * Дозаписывает строку в файл /log/process_events.log
     *
     * @param $eventText
     * @return bool
     */
    public static function logEvent($eventText) {
        if ($eventText == "") return false;

        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/process_events.log";
        $content = "[".self::formatTime("atom")."] ".$eventText."\n";
        $append = "APPEND";
        return self::writeToFile($filename, $content, $append);
    }

    /**
     * Записывает объект/строку в файл /log/debug.log. Делает перезапись каждый раз
     *
     * @param $something
     * @return bool
     */
    public static function debug($something) {
        $filename = $_SERVER["DOCUMENT_ROOT"]."/log/debug.log";
        $content = "[".self::formatTime("atom")."]".$something."\n";
        return self::writeToFile($filename, $content);
    }

    /**
     * Записывает содержимое в файл. Возвращает результат записи
     * @param $filename - имя файла, в который будет осуществляться запись
     * @param $content - содержимое
     * @param null $append
     * @return bool
     */
    public static function writeToFile($filename, $content, $append = null){
        try {
            if (is_null($append)) file_put_contents($filename,  $content);
            else file_put_contents($filename,  $content, FILE_APPEND);
            return true;
        } catch(Exception $ex){ self::processException($ex); }
        return false;
    }

    /**
     * Читает содержимое файла. Возвращает содержимое либо NULL, если возникла какая-то ошибка
     * @param $filename - имя файла
     * @return null|string
     */
    public static function readFromFile($filename){
        try {
            $content = file_get_contents($filename);
            return $content;
        } catch(Exception $ex){ self::processException($ex); }
        return NULL;
    }

    public static function reverseArray($array) {
        $result = array();
        for($i = count($array) - 1; $i >= 0;$i--) {
            $result[] = $array[$i];
        }
        return $result;
    }
}