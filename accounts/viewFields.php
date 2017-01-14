<dl class="row">
    <dt class="col-sm-3">ID</dt>
    <dd class="col-sm-9"><?= $instance->id ?></dd>

    <dt class="col-sm-3">Логин</dt>
    <dd class="col-sm-9"><?= $instance->login ?></dd>

    <?php


    ?>

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

    <dt class="col-sm-3">Центр, взявший аккаунт</dt>
    <dd class="col-sm-9"><?= !is_null($instance->center) ? "$instance->center" : " - " ?></dd>

    <dt class="col-sm-3">Последняя операция</dt>
    <dd class="col-sm-9"><?= $instance->lastOperation ?></dd>

    <dt class="col-sm-3">Обновлен</dt>
    <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->updatedAt->getTimestamp() + 6 * 3600)  ?></dd>

    <dt class="col-sm-3">Был создан</dt>
    <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->createdAt->getTimestamp()+ 6 * 3600)  ?></dd>
</dl>