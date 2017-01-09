<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
header('Content-Type: application/json');

$response = null;

$ip = $_SERVER["REMOTE_ADDR"];
$apiRequest = ApiMessage::fromJson($_REQUEST["json"]);

switch ($apiRequest->RequestType){
    case "GetAccount":
        $account = SteamAccount::fromData("login11122", "asdqwe123", true);
        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;

    case "ReleaseAccount":
        $account = SteamAccount::fromData("login11122", "asdqwe123", true);
        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;

    case "UsingAccount":
        $account = SteamAccount::fromData("login11122", "asdqwe123", true);
        $apiMessage = ApiMessage::createApiMessage($apiRequest->RequestType, $account->getJson(), "this is test");
        $response = $apiMessage;
        break;
}

ApplicationHelper::debug(var_export($apiRequest, true));
echo json_encode($response);