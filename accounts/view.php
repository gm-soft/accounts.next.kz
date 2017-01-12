<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../accounts/");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы выполнить это действие, вы должны быть залогинены");
    ApplicationHelper::redirect("../accounts/");
}

$mysql = MysqlHelper::getNewInstance();
$currentUser = CookieHelper::GetCurrentUser($mysql);

if (is_null($currentUser) ){
    $_SESSION["errors"] = array("Авторизационный токен не найден. Авторизуйтесь снова");
    CookieHelper::ClearCookies();
    ApplicationHelper::redirect("../accounts/");
}

$instance = $mysql->getSteamAccount($id);

if (is_null($instance)) {
    $_SESSION["errors"] = array("Аккаунт с ID".$id." не найден в базе данных");
    ApplicationHelper::redirect("/");
}
$pageTitle = "Просмотр сущности NEXT.Accounts";
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
?>
    <div class="container">
        <div class="mt-2">
            <h1>Информация об аккаунте</h1>
        </div>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/accounts/viewFields.php"; ?>

        <div class="float-sm-left">
            <a href="../accounts/"  class="btn btn-secondary"><i class="fa fa-chevron-circle-left"  aria-hidden="true"></i> В список</a>
        </div>
        <div class="float-sm-right">

            <a href="../accounts/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><i class="fa fa-pencil"  aria-hidden="true"></i> Редактировать</a>
            <a href="../accounts/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><i class="fa fa-remove"  aria-hidden="true"></i> Удалить</a>
        </div>

    </div>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");