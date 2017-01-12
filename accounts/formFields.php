<form class="form-horizontal" id="form" method="post" action="<?= $formAction ?>">

    <input type="hidden" name="actionPerformed" value="dataInput">
    <fieldset>
        <legend>Аккаунт</legend>

        <?php
        if (isset($formData)){
            ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="accountId">ID</label>
                <div class="col-sm-10">
                    <input type="number" id="accountId" class="form-control" required maxlength="50" value="<?= $formData["account_id"] ?>" disabled>
                </div>
                <input type="hidden" name="id" value="<?= $formData["account_id"] ?>">
            </div>
            <?php
        }
        ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="accountLogin">Логин аккаунта</label>
            <div class="col-sm-10">
                <input type="text" id="accountLogin" name="accountLogin" class="form-control" required placeholder="Введите логин (32)" maxlength="32" value="<?= $formData["account_login"] ?>" >
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="accountPassword">Пароль аккаунта</label>
            <div class="col-sm-10">

                <div class="input-group">
                    <input type="password" id="accountPassword" name="accountPassword" class="form-control" required placeholder="Введите пароль (32)" maxlength="32" value="<?= $formData["account_password"] ?>">
                    <span class="input-group-btn">
                        <button type="button" id="showPassword" class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </span>
                </div>


            </div>
        </div>

        <?php
        if (isset($formData)){
            ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="available">Статус доступности (account_available)</label>
                <div class="col-sm-10">
                    <select class="form-control" id="available" name="available" required>
                        <?php
                        $available = isset($formData["account_available"]) ? $formData["account_available"] : "true";
                        ?>
                        <option value="true" <?=$available == "true" ? "selected" : "" ?>>Доступен</option>
                        <option value="false" <?=$available == "false" ? "selected" : "" ?>>Занят</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="vacBanned">Забанен в VAC (vac_banned)</label>
                <div class="col-sm-10">
                    <select class="form-control" id="vacBanned" name="vacBanned" required>
                        <?php
                        $vacBanned = isset($formData["account_vac_banned"]) ? $formData["account_vac_banned"] : "false";


                        ?>
                        <option value="false" <?=$vacBanned == "false" ? "selected" : "" ?>>Без бана</option>
                        <option value="true" <?=$vacBanned == "true" ? "selected" : "" ?>>Забанен</option>

                    </select>
                </div>
            </div>

            <div class="form-group  row">
                <label class="col-sm-2 col-form-label" for="computerName">Имя взявшего компьютера</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="computerName" name="computerName" placeholder="Имя компьютера" maxlength="50" value="<?= $formData["account_computer_name"] ?>">
                </div>
            </div>

            <div class="form-group  row">
                <label class="col-sm-2 col-form-label" for="centerName">Название центра, который взял аккаунт</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="centerName" name="centerName" placeholder="" maxlength="50" value="<?= $formData["account_center"] ?>">
                </div>
            </div>
        <?php
        } else {
            ?>
            <input type="hidden" name="available" value="true">
            <input type="hidden" name="vacBanned" value="false">
            <input type="hidden" name="computerName" value="">
            <input type="hidden" name="centerName" value="">


            <?php
        }?>


    </fieldset>

    <div class="form-group row">
        <div class="col-sm-12">
            <div class="float-sm-right">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
                <a href="../accounts/" class="btn btn-secondary">Отмена</a>
            </div>
        </div>
    </div>

</form>

<script type="text/javascript">
    var passShowBtn = $('#showPassword');
    var passInput = $('#accountPassword');
    passShowBtn.mousedown(function(){
        passInput.prop("type", "text");
    });
    passShowBtn.mouseup(function(){
        passInput.prop("type", "password");
    });


    $('#form').submit(function(){
        $("#submit-btn").prop('disabled',true);
    });
</script>