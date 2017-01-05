<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
require($_SERVER["DOCUMENT_ROOT"]."/models/UserClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/MysqlHelperClass.php");
require($_SERVER["DOCUMENT_ROOT"]."/helpers/CookieHelperClass.php");
//---------------------------------------------
if(!isset($_SESSION)) session_start();

if (!isset($_COOKIE["hash"]) || $_COOKIE["hash"] == "") {
    $_SESSION["error"] = array("Вы должны быть авторизованы на сайте");
    redirect("../session/login.php");
}


$mysql = new MysqlHelper(DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
$user = $mysql->getUser($_COOKIE["hash"], "user_hash");

if (!is_null($user)) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>
    <div class="container">
        <h1>Профайл пользователя</h1>

        <div class="row">
            <dt  class="col-sm-3">ID пользователя</dt><dd class="col-sm-9"><?= $user->id?></dd>
            <dt class="col-sm-3">Логин</dt><dd class="col-sm-9"><?= $user->login?></dd>
            <dt class="col-sm-3">Группа</dt><dd class="col-sm-9"><?= $user->permission?></dd>
            <dt class="col-sm-3">Создан</dt><dd class="col-sm-9"><?= $user->created_at?></dd>
        </div>
    </div>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");
} else {
    //CookieHelper::ClearCookies();
    
    $_SESSION["error"] = array("Возникла какая-то ошибка. Пользователь не найден");
    redirect("../index.php");
}

