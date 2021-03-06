<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
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

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Создание сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Создание нового пользователя</h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/users/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $login = ApplicationHelper::ClearInputData($_REQUEST["userLogin"]);
        $password = ApplicationHelper::ClearInputData($_REQUEST["userPassword"]);

        $newInstance = User::fromUserData($login, $password);
        $result = $mysql->addUser($newInstance);
        if ($result["result"] == true){
            $newInstance->id = $result["data"];
            $_SESSION["success"] = array("Новый пользователь ID".$newInstance->id." создан");
            $url = "../users/view.php?id=".$newInstance->id;
        }
        else {
            $_SESSION["errors"] = array("Пользователь не был создан<br>".$result["data"]);
            $newInstance = $result["data"];
            $url = "../users/";
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