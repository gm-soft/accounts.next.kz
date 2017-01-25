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

    case "api_test":
        $apiMessage = new ApiMessage();
        $account = SteamAccount::fromData("login11122", "asdqwe123", true);
        $apiMessage = ApiMessage::createApiMessage("GetAccounts", $account->getJson(), "this is test");
        $response = $apiMessage;

        break;

    case "releaseUsed":
        // TIMESTAMPDIFF(SECOND,updated_at, NOW())
        // "TIME_TO_SEC(NOW() - updated_at) > 240"
        $instances = $mysql->filterSteamAccounts(["account_available=0", "TIMESTAMPDIFF(SECOND,updated_at, NOW()) > 2400"]);
        $result = true;
        foreach($instances as $instance){
            $instance->available = true;
            $instance->center = "";
            $instance->lastOperation = "Аккаунт освобожден принудительно. Последняя операция над аккаунтов: ".$instance->lastOperation;
            $updateResult = $mysql->updateSteamUsing($instance);
            $result = $result && $updateResult["result"];
        }

        $response["total"] = count($instances);
        //$response["result"] = $instances;
        $response["result"] = $result;
        break;

    case "updateCenters":
        $result = true;
        $centers = $mysql->getCenters();
        $accounts = [];
        foreach ($centers as $center){
            $centerAccounts = $mysql->filterSteamAccounts(["account_center='".$center->code."'"]);
            $center->count = count( $centerAccounts);
            $accounts[] = $centerAccounts;
            $updateResult = $mysql->updateCenter($center, false);
            $centerAccounts = [];
            $result = $result && $updateResult["result"];
        }

        $response["total"] = count($centers);
        $response["result"] = $result;
        $response["accounts"] = $accounts;
        break;
}

echo json_encode($response);