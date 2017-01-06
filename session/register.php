<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
//---------------------------------------------
if(!isset($_SESSION)) session_start();



$performed = isset($_POST["performed"]) ? $_POST["performed"] : false;

if ($performed == true) {
    $err = array();
    $login = User::ClearInputData($_POST["login"]);
    $password = User::ClearInputData($_POST["password"]);
    $password_conf = User::ClearInputData($_POST["password_confirm"]);

    //if (preg_match("/^[a-zA-Z0-9]+$/", $login)) $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    if ($password != $password_conf) $err[] = "Введенные пароли не совпадают";
    if (strlen($login) >30 || strlen($login) <3) $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";


    $mysql = new MysqlHelper(DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

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
    ?>
    <div class="container">
        <h1>Регистрация</h1>
        <form method="post" action="../session/register.php" class="form-horizontal">
            <input type="hidden" name="performed" value="true">

            <div class="form-group row">
                <label class="col-form-label col-sm-2" for="login">Логин:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="login" name="login" placeholder="Введите свой логин"  maxlength="30" minlength="3" required>
                </div>
                <div class="col-sm-4">
                    <small id="loginHelp" class="form-text text-muted">Логин будет использован для входа на сайт</small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2" for="password">Пароль:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
                </div>
                <div class="col-sm-4">
                    <small id="loginHelp" class="form-text text-muted">Пароль должен быть не менее 6 символов</small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2" for="password_confirm">Подтвердите пароль:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Введите пароль повторно" required>
                    <span id='pwd_message'></span>
                </div>

            </div>

            <div class="form-group row">
                <div class="offset-sm-2 col-sm-6">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </div>
            </div>

        </form>
    </div>
    <script>
        $('#password, #password_confirm').on('keyup', function () {
            if ($('#password').val() == $('#password_confirm').val()) {
                $('#pwd_message').html('Пароли совпадают').css('color', 'green');
            } else
                $('#pwd_message').html('Пароли не совпадают').css('color', 'red');
        });

    </script>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
}