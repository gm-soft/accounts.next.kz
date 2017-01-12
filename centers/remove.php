<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../centers/");


if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы удалить запись, вы должны быть залогинены");
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
$instance = $mysql->getCenter($id, "center_id");
$pageTitle = "Удаление сущности NEXT.Accounts";

if (!isset($_POST["confirmed"])){
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>
    <div class="container">
        <div class="mt-2">
            <h1>Удаление центра (объекта) <?= $instance->name ?> (<?= $instance->id ?>)</h1>
        </div>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/centers/viewFields.php"; ?>

        <form method="post" action="">
            <input type="hidden" name="id" value="<?= $instance->id ?>">
            <input type="hidden" name="confirmed" value="true">
            <div class="checkbox">
                <label><input type="checkbox" required> Подтвердить удаление</label>
            </div>
            <button type="submit" class="btn btn-danger">Удалить запись</button>
        </form>

    </div>

    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");

} else {

    if ($currentUser->checkPermission(2)) {
        $_SESSION["error"] = array("Недостаточно прав для выполнения этого действия");
    } else {
        $deleteResult = $mysql->deleteAccount($instance);
        if ($deleteResult["result"] == true){
            $_SESSION["success"] = array("Центр ".$instance->name." (ID".$instance->id.") удален успешно");
        } else {
            $_SESSION["error"] = array("Возникла неожиданная ошибка при удалении сущности");
        }
    }


    ApplicationHelper::redirect("../users/");
}