<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
header('Content-Type: application/json');

$response = null;

$ip = $_SERVER["REMOTE_ADDR"];
$apiRequest = ApiMessage::fromJson($_REQUEST["json"]);
$sender = json_decode($apiRequest->JsonSender, true);
$accountJson = json_decode($apiRequest->JsonObject, true);

$mysql = MysqlHelper::getNewInstance();

switch ($apiRequest->RequestType){
    case "GetAccount":

        $filterArray = ["account_available=1"];
        $vacBanFreeRequest = $apiRequest->VacBanFree;
        if ($vacBanFreeRequest == true) {
            $filterArray[] = "account_vac_banned=0";
        }
        $freeAccounts = $mysql->filterSteamAccounts($filterArray, "AND");
        if (count($freeAccounts) > 0) {
            $account = SteamAccount::getRandomAccount($freeAccounts);

            $account->available = false;
            $account->lastOperation = "Аккаунт взят компьютером ".$sender["Name"]." в центр ".$sender["CenterName"]."";
            $account->computerName = $sender["Name"];
            $account->center = $sender["CenterName"];


            $mysql->updateSteamAccount($account);
            $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");

            ApplicationHelper::logEvent("Аккаунт ".$account->login."[".$account->id."] взят в центр ".$sender["CenterName"]."");
        } else {
            $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, null, "this is test", 404);
            ApplicationHelper::logEvent("Центр ".$sender["CenterName"]." запросил аккаунт, однако ни одного аккаунта не было найдено по запросу. vacBanFreeRequest = $vacBanFreeRequest");
        }




        $response = $apiMessage;
        break;

    case "ReleaseAccount":

        $account = SteamAccount::fromJson($accountJson);

        /*$accountId = $accountJson["Id"];
        $account = $mysql->getSteamAccount($accountId, "account_id");
        */

        $account->available = true;
        $account->center = "";
        $account->lastOperation = "Аккаунт освобожден компьютером ".$sender["Name"]." из центра ".$sender["CenterName"]."";
        $mysql->updateSteamUsing($account);

        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;

        ApplicationHelper::logEvent("Аккаунт ".$account->login."[".$account->id."] возвращен из центра ".$sender["CenterName"]."");
        break;

    case "UsingAccount":

        $account = SteamAccount::fromJson($accountJson);

        $account->lastOperation = "Аккаунт до сих пор используется компьютером ".$sender["Name"]." из центра ".$sender["CenterName"]."";
        $mysql->updateSteamUsing($account);

        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;

        break;
}
echo json_encode($response);