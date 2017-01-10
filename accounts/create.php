<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");

if (!isset($_COOKIE["hash"])){
    $_SESSION["errors"] = array("Чтобы создать новую запись, вы должны быть залогинены");
    ApplicationHelper::redirect("../accounts/");
}

$mysql = MysqlHelper::getNewInstance();

$actionPerformed = isset($_REQUEST["actionPerformed"]) ? $_REQUEST["actionPerformed"] : "initiated";

switch ($actionPerformed){
    case "initiated":
        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        $formAction = "create.php";

        ?>
        <div class="container">
            <div class="page-header">
                <h1>Создание новой записи об аккаунте</h1>
            </div>
            <?php require_once $_SERVER["DOCUMENT_ROOT"]."/accounts/formFields.php"; ?>
        </div>
        <?php
        break;

    default:

        $login = ApplicationHelper::ClearInputData($_REQUEST["accountLogin"]);
        $password = ApplicationHelper::ClearInputData($_REQUEST["accountPassword"]);

        $newInstance = SteamAccount::fromData($login, $password);
        $result = $mysql->addSteamAccount($newInstance);
        if ($result["result"] == true){
            $newInstance->id = $result["data"];
        }
        else {
            $newInstance = $result["data"];
        }


        require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
        echo "<pre class='container'>".var_export($newInstance, true)."</pre>";
        break;
}

?>


<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");