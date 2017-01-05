<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
require($_SERVER["DOCUMENT_ROOT"]."/models/UserClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/MysqlHelperClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/CookieHelperClass.php");
//---------------------------------------------
if(!isset($_SESSION)) session_start();



$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true){
    $err = array();
    $login = User::ClearInputData($_POST["login"]);
    $password = User::ClearInputData($_POST["password"]);

    // if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    // if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";


    $mysql = new MysqlHelper(DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

    $user = $mysql->getUser($login);
    if (is_null($user)) {
        $err[] = "Пользователь с таким логином отсутствует в базе";
        $_SESSION["errors"] = $err;
        redirect("../session/login.php");
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
        redirect("../index.php");

    } else {
        $_SESSION["errors"] = $err;
        redirect("../session/login.php");
    }

} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>
    <div class="container">
        <h1>Авторизация на сайте</h1>
        <form method="post" action="../session/login.php" class="form-horizontal">
            <input type="hidden" name="performed" value="true">
            <div class="form-group">
                <label class="control-label col-sm-2" for="login">Логин:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="login" name="login" placeholder="Введите свой логин"  maxlength="30" minlength="3" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Пароль:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="pwd" name="password" placeholder="Введите пароль" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Авторизоваться</button>
                </div>
            </div>

        </form>
    </div>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
}

