<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------


$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true) {
    $err = array();
    $login = ApplicationHelper::ClearInputData($_POST["login"]);
    $password = ApplicationHelper::ClearInputData($_POST["password"]);
    $password_conf = ApplicationHelper::ClearInputData($_POST["password_confirm"]);

    //if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    if ($password != $password_conf) $err[] = "Введенные пароли не совпадают";
    if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";


    $mysql = MysqlHelper::getNewInstance();

    $existedUser = $mysql->getUser($login, "username");
    if (!is_null($existedUser)) {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }
    if(count($err) == 0)
    {
        $newUser = User::fromUserData($login, $password);
        $res = $mysql->addUser($newUser);
        if ($res["result"] == true) {
            CookieHelper::SetUserSession($newUser);

            $_SESSION["success"] = array("Вы успешно зарегистрировались на сайте");
            ApplicationHelper::redirect("../index.php");
        } else {

            $_SESSION["errors"] = array($res["data"]);
            ApplicationHelper::redirect("../session/register.php");
        }
    } else {
        $_SESSION["errors"] = $err;
        ApplicationHelper::redirect("../session/register.php");
    }

} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/session/registerPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
}