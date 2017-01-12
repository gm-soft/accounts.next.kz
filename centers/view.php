<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../centers/");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы просматривать запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../centers/");
}

$mysql = MysqlHelper::getNewInstance();
$currentUser = CookieHelper::GetCurrentUser($mysql);



if (is_null($currentUser) ){
    $_SESSION["errors"] = array("Авторизационный токен не найден. Авторизуйтесь снова");
    CookieHelper::ClearCookies();
    ApplicationHelper::redirect("../centers/");
}

$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

if ($viewPermission == false){
    $_SESSION["errors"] = array("У Вас недостаточно прав для этого действия");
    ApplicationHelper::redirect("../centers/");
}

$pageTitle = "Просмотр сущности NEXT.Accounts";
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
$instance = $mysql->getCenter($id, "center_id");
?>
    <div class="container">
        <div class="mt-2">
            <h1>Информация о центре</h1>
        </div>
        
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/centers/viewFields.php"; ?>

        <div class="float-sm-left">
            <a href="../centers/"  class="btn btn-secondary"><i class="fa fa-chevron-circle-left"  aria-hidden="true"></i> В список</a>
        </div>
        <div class="float-sm-right">

            <a href="../centers/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><i class="fa fa-pencil"  aria-hidden="true"></i> Редактировать</a>
            <a href="../centers/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><i class="fa fa-remove"  aria-hidden="true"></i> Удалить</a>
        </div>

    </div>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");