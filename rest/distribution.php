<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
header('Content-Type: application/json');

$response = null;

$ip = $_SERVER["REMOTE_ADDR"];
$apiRequest = ApiMessage::fromJson($_REQUEST["json"]);
$sender = json_decode($apiRequest->JsonSender, true);

$mysql = MysqlHelper::getNewInstance();

switch ($apiRequest->RequestType){
    case "GetAccount":

        $freeAccounts = $mysql->filterSteamAccounts(["account_available=1", "account_vac_banned=0"], "AND");

        //ApplicationHelper::debug(var_export($freeAccounts, true));
        $account = SteamAccount::getRandomAccount($freeAccounts);
        $account->available = false;
        $account->lastOperation = "Аккаунт взят компьютером ".$sender["Name"]." в центр ".$sender["CenterName"]."";
        $mysql->updateSteamAccount($account);

        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;

    case "ReleaseAccount":

        $accountJson = json_decode($apiRequest->JsonObject, true);
        $accountId = $accountJson["Id"];
        $account = $mysql->getSteamAccount($accountId, "account_id");

        $account->available = true;
        $account->lastOperation = "Аккаунт освобожден компьютером ".$sender["Name"]." из центра ".$sender["CenterName"]."";
        $mysql->updateSteamAccount($account);

        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;

    case "UsingAccount":
        $account = SteamAccount::fromData("login11122", "asdqwe123", true);
        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;
}

echo json_encode($response);