<?php
require ($_SERVER["DOCUMENT_ROOT"]."/include/constants.php");

// ^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$
/**
 * Производит перенаправление пользователя на заданный адрес
 *
 * @param string $url адрес
 */
function redirect($url)
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
function query($method, $url, $data = null)
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
    log_debug($result);
    return json_decode($result, 1);
}


/**
 * Преобразовывает строку в формате json в объект-json. Возвратит исходный объект в случае ошибки
 * @param $content - исходная строка
 * @return array
 */
function objectAsJson($content){

    try {
        $data = json_decode($content);
        $array = (array)$data;
        foreach($array as $key => &$field){
            if(is_object($field))$field = $this->objectToarray($field);
        }
        return $array;
    } catch(Exception $ex){

    }
    return $content;
}

function formatTime($format = null, $time = null) {

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
function process_exception($exception){

}

function process_error($error_text) {
    $filename = $_SERVER["DOCUMENT_ROOT"]."/log/errors.log";
    $text = "[".formatTime("atom")."] ".$error_text."\n";
    error_log($text, 3, $filename);
}

/**
 *
 *
 */
function log_event($event_text) {
    if ($event_text == "") return false;

    $filename = $_SERVER["DOCUMENT_ROOT"]."/log/process_events.log";
    $content = "[".formatTime("atom")."] ".$event_text."\n";
    $append = "APPEND";
    return writeToFile($filename, $content, $append);
}

function log_debug($something) {
    $filename = $_SERVER["DOCUMENT_ROOT"]."/log/debug.log";
    $content = "[".formatTime("atom")."]".$something."\n";
    return writeToFile($filename, $content);
}

/**
 * Записывает содержимое в файл. Возвращает результат записи
 * @param $filename - имя файла, в который будет осуществляться запись
 * @param $content - содержимое
 * @param null $append
 * @return bool
 */
function writeToFile($filename, $content, $append = null){
    try {
        if (is_null($append)) file_put_contents($filename,  $content);
        else file_put_contents($filename,  $content, FILE_APPEND);
        return true;
    } catch(Exception $ex){ process_exception($ex); }
    return false;
}

/**
 * Читает содержимое файла. Возвращает содержимое либо NULL, если возникла какая-то ошибка
 * @param $filename - имя файла
 * @return null|string
 */
function readFromFile($filename){
    try {
        $content = file_get_contents($filename);
        return $content;
    } catch(Exception $ex){ process_exception($ex); }
    return NULL;
}

function reverseArray($array) {
        $result = array();
        for($i = count($array) - 1; $i >= 0;$i--) {
            $result[] = $array[$i];
        }
        return $result;
    }
