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

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";
$pageTitle = "Редактирование сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":

        $instance = $mysql->getUser($id, "user_id");

        if (is_null($instance)) {
            $_SESSION["errors"] = array("Пользователь с ID".$id." не найден в базе данных");
            ApplicationHelper::redirect("../users/");
        }

        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "edit.php";
        $formData = $instance->getAsFormData();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование пользователя <?= $instance->login ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/users/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":
        $id = $_REQUEST["id"];
        $login = ApplicationHelper::ClearInputData($_REQUEST["userLogin"]);


        $instance = $mysql->getUser($id, "user_id");
        $instance->permission = intval($_REQUEST["permission"]);
        $instance->login = $login;

        if (!empty($_REQUEST["userPassword"])){
            $password = ApplicationHelper::ClearInputData($_REQUEST["userPassword"]);
            $instance->resetPassword($password);
        }

        $updateResult = $mysql->updateUser($instance);

        if ($updateResult["result"] == false){
            $_SESSION["errors"] = ["Возникла ошибка при сохранении данных<br>".var_export($updateResult["data"], true)];
            $url = "../users/edit.php?id=".$_REQUEST["id"];
        } else {
            $_SESSION["success"] = ["Данные успешно обновлены"];
            $url = "../users/view.php?id=".$_REQUEST["id"];

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