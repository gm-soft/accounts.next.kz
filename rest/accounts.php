<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
header('Content-Type: application/json');

$response = array(
    "result" => false,
    "date" => new DateTime()
);
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : NULL;

if (is_null($action)) {
    $response["error"] = "null action";
    echo json_encode($response);
    die();
}

$mysql = MysqlHelper::getNewInstance();

switch ($action){
    case "test":
        $user = $mysql->getUser("maximgorbatyuk");
        $response["result"] = $user;
        break;

    case "date":
    	$datetime = new DateTime();
    	$response["datetime"] = $datetime;
        $response["datetime_formated"] = date("Y-m-d H:i:s", $datetime->getTimestamp());
    	$response["result"] = DateTime::createFromFormat("Y-m-d H:i:S", $response["datetime_formated"]);
        break;
}

echo json_encode($response);