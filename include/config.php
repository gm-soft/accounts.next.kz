<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"]."/include/constants.php";
// models
require $_SERVER["DOCUMENT_ROOT"]."/models/CenterClass.php";
require $_SERVER["DOCUMENT_ROOT"]."/models/ApiMessageClass.php";
require $_SERVER["DOCUMENT_ROOT"]."/models/UserClass.php";
require $_SERVER["DOCUMENT_ROOT"]."/models/SteamAccountClass.php";

// helpers
require $_SERVER["DOCUMENT_ROOT"]."/helpers/ApplicationHelperClass.php";
require $_SERVER["DOCUMENT_ROOT"]."/helpers/MysqlHelperClass.php";
require $_SERVER["DOCUMENT_ROOT"]."/helpers/CookieHelperClass.php";


if (!CookieHelper::IsAuthorized()){

    if ($_SERVER['REQUEST_URI'] == "/session/login.php" ||
        strpos($_SERVER['REQUEST_URI'], '/rest/') !== false) return;

    ApplicationHelper::redirect("../session/login.php");
}




