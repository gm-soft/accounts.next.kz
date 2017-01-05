<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
require($_SERVER["DOCUMENT_ROOT"]."/models/UserClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/MysqlHelperClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/CookieHelperClass.php");

if (!isset($_COOKIE["login"])) redirect("../index.php");

CookieHelper::ClearCookies();
redirect("../index.php");