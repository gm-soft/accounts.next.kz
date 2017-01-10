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
    ?>
    <div class="container">
        <h1>Авторизация на сайте</h1>
        <form method="post" action="../session/login.php" class="form-horizontal">
            <input type="hidden" name="performed" value="true">
            <div class="form-group row">
                <label class="col-form-label col-sm-2" for="login">Логин:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="login" name="login" placeholder="Введите свой логин"  maxlength="30" minlength="3" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2" for="password">Пароль:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Авторизоваться</button>
                </div>
            </div>

        </form>
    </div>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
}

