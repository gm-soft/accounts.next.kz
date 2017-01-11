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
            <div class="page-header">
                <h1>Редактирование записи <?= $instance->login ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/accounts/formFields.php"; ?>
        </div>
        <?php
        break;

    default:
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        echo "<pre class='container'>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");