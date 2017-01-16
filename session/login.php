<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------



$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true){
    $err = array();
    $login = ApplicationHelper::ClearInputData($_POST["login"]);
    $password = ApplicationHelper::ClearInputData($_POST["password"]);

    // if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    // if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";


    $mysql = MysqlHelper::getNewInstance();

    $user = $mysql->getUser($login);
    if (is_null($user)) {
        $err[] = "Пользователь с таким логином отсутствует в базе";
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("../session/login.php");
    }

    if (!$user->validatePassword($password)) {
        $err[] = "Пароль не совпадает";
    }

    if(count($err) == 0)
    {
        $user->updateHash();
        $res = $mysql->updateUserHash($user);

        CookieHelper::SetUserSession($user);

        $_SESSION["success"] = array("Вы успешно авторизовались");
        ApplicationHelper::redirect("../index.php");

    } else {
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("../session/login.php");
    }

} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/session/loginPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
}

