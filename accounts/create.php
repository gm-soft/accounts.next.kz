<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
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
$pageTitle = "Создание сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Создание новой записи об аккаунте</h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/accounts/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $login = ApplicationHelper::ClearInputData($_REQUEST["accountLogin"]);
        $password = ApplicationHelper::ClearInputData($_REQUEST["accountPassword"]);

        $newInstance = SteamAccount::fromData($login, $password);
        $newInstance->lastOperation = "Аккаунт создан пользователем ".$currentUser->login;

        $result = $mysql->addSteamAccount($newInstance);
        if ($result["result"] == true){
            $newInstance->id = $result["data"];
            $_SESSION["success"] = array("Новый аккаунт ID".$newInstance->id." создан");
            $url = "../accounts/view.php?id=".$newInstance->id;
        }
        else {
            $_SESSION["errors"] = array("Аккаунт не был создан<br>".$result["data"]);
            $newInstance = $result["data"];
            $url = "../accounts/";
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