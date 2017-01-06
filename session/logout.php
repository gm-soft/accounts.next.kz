<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["login"])) ApplicationHelper::redirect("../index.php");

CookieHelper::ClearCookies();
ApplicationHelper::redirect("../index.php");