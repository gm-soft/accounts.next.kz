<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("../centers/");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы редактировать запись, вы должны быть залогинены");
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
$pageTitle = "Редактирование сущности NEXT.Accounts";

switch ($actionPerformed){
    case "initiated":

        $instance = $mysql->getCenter($id, "center_id");

        if (is_null($instance)) {
            $_SESSION["errors"] = array("Центр с ID".$id." не найден в базе данных");
            ApplicationHelper::redirect("../centers/");
        }

        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "edit.php";
        $formData = $instance->getAsFormData();

        ?>
        <div class="container">
            <div class="mt-2">
                <h1>Редактирование центра (объекта) <?= $instance->name ?></h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/centers/formFields.php"; ?>
        </div>
        <?php
        break;

    case "dataInput":


        $id = $_REQUEST["id"];
        $instance = $mysql->getCenter($id, "center_id");

        $name = ApplicationHelper::ClearInputData($_REQUEST["centerName"]);
        $code = ApplicationHelper::ClearInputData($_REQUEST["centerCode"]);
        $limit = intval($_REQUEST["centerLimit"]);
        $count = intval($_REQUEST["accountCount"]);
        $description = ApplicationHelper::ClearInputData($_REQUEST["centerDescription"]);




        $instance->name = $name;
        $instance->code = $code;
        $instance->limit = $limit;
        $instance->count = $count;
        $instance->description = $description;

        $updateResult = $mysql->updateCenter($instance);

        if ($updateResult["result"] == false){
            $_SESSION["errors"] = ["Возникла ошибка при сохранении данных<br>".var_export($updateResult["data"], true)];
            $url = "../centers/edit.php?id=".$_REQUEST["id"];
        } else {
            $_SESSION["success"] = ["Данные успешно обновлены"];
            $url = "../centers/view.php?id=".$_REQUEST["id"];

        }
        ApplicationHelper::redirect($url);

        break;

    default:
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        echo "<div class='container'>Неизвестное действие</div>";
        echo "<pre class='container'>".var_export($_REQUEST, true)."</pre>";
        break;
}

?>


<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");