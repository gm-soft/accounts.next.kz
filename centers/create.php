<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
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

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Создание сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Создание нового центра (объекта)</h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/centers/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $name = ApplicationHelper::ClearInputData($_REQUEST["centerName"]);
        $code = ApplicationHelper::ClearInputData($_REQUEST["centerCode"]);
        $limit = ApplicationHelper::ClearInputData($_REQUEST["centerLimit"]);

        $newInstance = Center::fromData($name, $code, $limit);
        $result = $mysql->addCenter($newInstance);
        if ($result["result"] == true){

            $newInstance->id = $result["data"];
            $_SESSION["success"] = array("Новый центр ID".$newInstance->id." создан");
            $url = "../centers/view.php?id=".$newInstance->id;
        }
        else {
            $_SESSION["errors"] = array("Объект не был создан<br>".$result["data"]);
            $newInstance = $result["data"];
            $url = "../centers/";
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