<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../accounts/");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы редактировать запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../accounts/");
}

$mysql = MysqlHelper::getNewInstance();
$currentUser = CookieHelper::GetCurrentUser($mysql);



if (is_null($currentUser) ){
    $_SESSION["errors"] = array("Авторизационный токен не найден. Авторизуйтесь снова");
    CookieHelper::ClearCookies();
    ApplicationHelper::redirect("../accounts/");
}


$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Редактирование сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":

        $instance = $mysql->getSteamAccount($id);

        if (is_null($instance)) {
            $_SESSION["errors"] = array("Клиент с ID".$id." не найден в базе данных");
            ApplicationHelper::redirect("../accounts/");
        }

        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "edit.php";
        $formData = $instance->getAsFormData();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование записи <?= $instance->login ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/accounts/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":

        $id = $_REQUEST["id"];
        $instance = $mysql->getSteamAccount($id);

        $login = ApplicationHelper::ClearInputData($_REQUEST["accountLogin"]);
        $password = ApplicationHelper::ClearInputData($_REQUEST["accountPassword"]);
        $available = $_REQUEST["available"] == "true";
        $vacBanned = $_REQUEST["vacBanned"] == "true";
        $computerName = ApplicationHelper::ClearInputData($_REQUEST["computerName"]);
        $centerName = ApplicationHelper::ClearInputData($_REQUEST["centerName"]);



        $instance->login = $login;
        $instance->password = $password;
        $instance->available = $available;
        $instance->vacBanned = $vacBanned;
        $instance->computerName = $computerName;
        $instance->center = $centerName;

        $instance->lastOperation = "Аккаунт обновлен пользователем ".$currentUser->login;
        $updateResult = $mysql->updateSteamAccount($instance);

        if ($updateResult["result"] == false){
            $_SESSION["errors"] = ["Возникла ошибка при сохранении данных<br>".var_export($updateResult["data"], true)];
            $url = "../accounts/edit.php?id=".$_REQUEST["id"];
        } else {
            $_SESSION["success"] = ["Данные успешно обновлены"];
            $url = "../accounts/view.php?id=".$_REQUEST["id"];

        }
        ApplicationHelper::redirect($url);
        break;

    default:
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        echo "<div class='container'>Неизвестное действие</div>";
        echo "<pre>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");