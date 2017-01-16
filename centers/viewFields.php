
<dl class="row">
    <dt class="col-sm-3">ID</dt>
    <dd class="col-sm-9"><?= $instance->id ?></dd>

    <dt class="col-sm-3">Название</dt>
    <dd class="col-sm-9"><?= $instance->name ?></dd>

    <dt class="col-sm-3">Кодовое имя</dt>
    <dd class="col-sm-9"><?= $instance->code ?></dd>

    <dt class="col-sm-3">Лимит</dt>
    <dd class="col-sm-9"><?= $instance->limit ?></dd>

    <dt class="col-sm-3">Кол-во аккаунтов</dt>
    <dd class="col-sm-9"><?= $instance->count ?></dd>

    <dt class="col-sm-3">Описание</dt>
    <dd class="col-sm-9"><?= $instance->description ?></dd>

    <dt class="col-sm-3">Последнее обновление</dt>
    <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->updatedAt->getTimestamp()+ 6 * 3600)  ?></dd>

    <dt class="col-sm-3">Был создан</dt>
    <dd class="col-sm-9"><?= date("Y-m-d H:i:s", $instance->createdAt->getTimestamp()+ 6 * 3600)  ?></dd>

    <?php
    if ($instance->count > 0) {
        ?>
        <dt class="col-sm-3">Список аккаунтов</dt>
        <dd class="col-sm-9">

            <table class='table table-hover'>
                <thead><tr><th>ID</th><th>Логин</th><th>Время обновления</th></tr></thead>
                <tbody>
                    <?php
                    foreach ($accounts as $account){
                        echo "<tr>";

                        echo "<td>".$account->id."</td><td>".$account->login."</td><td>".date("Y-m-d H:i:s", $account->updatedAt->getTimestamp() + 6*3600)."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </dd>
    <?php } ?>




</dl>