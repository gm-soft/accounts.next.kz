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

    case "account.list":
        $withPagination = isset($_REQUEST["withPagination"]) ? $_REQUEST["withPagination"] : false;
            $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : 50;
            $dbResponse = $mysql->getSteamAccounts($withPagination, $start, $limit);
            $instances = $dbResponse["data"];

            $response["result"] = $instances;
            $response["total"] = count($instances);

            if ($withPagination == true){
                $response["start"] = $start;
                $response["next"] = $start + $limit;
            }
        break;

    case "account.get":
        $field = isset($_REQUEST["field"]) ? $_REQUEST["field"] : null;
        $value = isset($_REQUEST["value"]) ? $_REQUEST["value"] : null;

        if (is_null($value)){
            $response["error"] = "search value is null";
            break;
        }

        if (is_null($field)){
            $fields = ["account_id", "account_login", "account_computer_name"];
            //---------------
            for($i = 0; $i < count($fields);$i++){
                $instance = $mysql->getSteamAccount($value, $fields[$i]);
                if (is_null($instance)) continue;
                $response["result"] = $instance;
                break;
            }
        }
        break;

    default:
        $response["error"] = "action_not_found";
        break;
}
echo json_encode($response);