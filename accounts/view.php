<?php

require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
$id = isset($_REQUEST["id"]) && $_REQUEST["id"] != ""  ? $_REQUEST["id"] : null;
if (is_null($id)) ApplicationHelper::redirect("/");


$mysql = MysqlHelper::getNewInstance();
$instance = $mysql->getSteamAccount($id);

if (is_null($instance)) {
    $_SESSION["errors"] = array("Аккаунт с ID".$id." не найден в базе данных");
    ApplicationHelper::redirect("/");
}
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
?>
    <div class="container">
        <h1>Информация об аккаунте</h1>
        <dl class="row">
            <dt class="col-sm-3">ID</dt>
            <dd class="col-sm-9"><?= $instance->id ?></dd>

            <dt class="col-sm-3">Логин</dt>
            <dd class="col-sm-9"><?= $instance->login ?></dd>

            <dt class="col-sm-3">Пароль</dt>
            <dd class="col-sm-9"><?= $instance->password ?></dd>

            <dt class="col-sm-3">Доступность для клиентов</dt>
            <dd class="col-sm-9"><?= $instance->available == true ? "Доступен" : "Занят" ?></dd>

            <dt class="col-sm-3">Забанен в VAC</dt>
            <dd class="col-sm-9"><?= $instance->vacBanned == true ? "Забанен" : "Без бана" ?></dd>

            <dt class="col-sm-3">Был использован</dt>
            <dd class="col-sm-9"><?= $instance->usageTimes ?> раз</dd>

            <dt class="col-sm-3">Компьютер, взявший аккаунт</dt>
            <dd class="col-sm-9"><?= !is_null($instance->computerName) ? "$instance->computerName" : " - " ?></dd>

            <dt class="col-sm-3">Последняя операция</dt>
            <dd class="col-sm-9"><?= $instance->lastOperation ?></dd>

            <dt class="col-sm-3">Обновлен</dt>
            <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->updatedAt->getTimestamp())  ?></dd>

            <dt class="col-sm-3">Был создан</dt>
            <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->createdAt->getTimestamp())  ?></dd>
        </dl>

        <div class="float-sm-left">
            <a href="../accounts/"  class="btn btn-secondary"><i class="fa fa-chevron-circle-left"  aria-hidden="true"></i> В список</a>
        </div>
        <div class="float-sm-right">

            <a href="../accounts/edit.php?id=<?= $instance->id?>"  class="btn btn-secondary"><i class="fa fa-pencil"  aria-hidden="true"></i> Редактировать</a>
            <a href="../accounts/remove.php?id=<?= $instance->id?>"  class="btn btn-danger"><i class="fa fa-remove"  aria-hidden="true"></i> Удалить</a>
        </div>

    </div>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php");