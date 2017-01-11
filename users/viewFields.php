
<?php
switch ($instance->permission){
    case 1:
        $permission = "Сис.админ";
        break;

    case 2:
        $permission = "Редактор";
        break;
    case 4:
        $permission = "Бог";
        break;
    default:
        $permission = "Не известно";
        break;
}

?>

<dl class="row">
    <dt class="col-sm-3">ID</dt>
    <dd class="col-sm-9"><?= $instance->id ?></dd>

    <dt class="col-sm-3">Логин</dt>
    <dd class="col-sm-9"><?= $instance->login ?></dd>

    <dt class="col-sm-3">Пароль</dt>
    <dd class="col-sm-9"><?= $instance->password ?></dd>

    <dt class="col-sm-3">Уровень доступа</dt>
    <dd class="col-sm-9"><?= $permission ?> (<?= $instance->permission ?>)</dd>

    <dt class="col-sm-3">Был создан</dt>
    <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->created_at->getTimestamp())  ?></dd>
</dl>