<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../users/");


if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы редактировать запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../users/");
}

$mysql = MysqlHelper::getNewInstance();
$currentUser = CookieHelper::GetCurrentUser($mysql);


if (is_null($currentUser) ){
    $_SESSION["errors"] = array("Авторизационный токен не найден. Авторизуйтесь снова");
    CookieHelper::ClearCookies();
    ApplicationHelper::redirect("../users/");
}

$viewPermission = $currentUser->checkPermission(2);
$setPermission = $currentUser->checkPermission(3);
$godPermission = $currentUser->checkPermission(4);

if ($viewPermission == false){
    $_SESSION["errors"] = array("У Вас недостаточно прав для этого действия");
    ApplicationHelper::redirect("../users/");
}
$instance = $mysql->getUser($id, "user_id");

if (!isset($_POST["confirmed"])){
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Удаление пользователя <?= $instance->login ?> (<?= $instance->id ?>)</h1>
        </div>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/users/viewFields.php"; ?>

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

    $deleteResult = $mysql->deleteAccount($instance);
    if ($deleteResult["result"] == true){
        $_SESSION["success"] = array("Пользователь ".$instance->login." (ID".$instance->id.") удален успешно");
    } else {
        $_SESSION["error"] = array("Возникла неожиданная ошибка при удалении сущности");
    }
    ApplicationHelper::redirect("../users/");
}