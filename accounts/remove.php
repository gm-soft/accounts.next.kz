<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../accounts/");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы удалить запись, вы должны быть залогинены");
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
    $_SESSION["errors"] = array("Клиент с ID".$id." не найден в базе данных");
    ApplicationHelper::redirect("../accounts/");
}

if (!isset($_POST["confirmed"])){
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Удаление записи <?= $instance->login ?> (<?= $instance->id ?>)</h1>
        </div>
        <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/accounts/viewFields.php"; ?>

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

    $user = $mysql->getUser($_COOKIE["hash"], "hash");
    if (is_null($user)) {
        $_SESSION["error"] = array("Пользователь с указанным хэшем не найден в базе");
        ApplicationHelper::redirect("../accounts/");
    }

    if ( $user->checkPermission(4)){
        $_SESSION["errors"] = array("У вас недостаточно прав для совершения этого действия");
        ApplicationHelper::redirect("../accounts/");
    }

    $deleteResult = $mysql->deleteAccount($instance);
    if ($deleteResult["result"] == true){
        $_SESSION["success"] = array("Аккаунт ".$instance->login." (ID".$instance->id.") удален успешно");
    } else {
        $_SESSION["error"] = array("Возникла неожиданная ошибка при удалении сущности");
    }
    ApplicationHelper::redirect("../accounts/");
}