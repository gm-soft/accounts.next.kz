<form class="form-horizontal" id="form" method="post" action="<?= $formAction ?>">

    <input type="hidden" name="actionPerformed" value="dataInput">
    <fieldset>
        <legend>Пользователь системы</legend>

        <?php
        if (isset($formData)){
            ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="userId">ID</label>
                <div class="col-sm-10">
                    <input type="number" id="userId" class="form-control" value="<?= $formData["user_id"] ?>" disabled>
                </div>
                <input type="hidden" name="id" value="<?= $formData["user_id"] ?>">
            </div>
            <?php
        }
        ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="userLogin">Логин пользователя</label>
            <div class="col-sm-10">
                <input type="text" id="userLogin" name="userLogin" class="form-control" required placeholder="Введите логин (32)" maxlength="32" value="<?= $formData["user_login"] ?>" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="userPassword">Пароль пользователя</label>
            <div class="col-sm-10">

                <div class="input-group">
                    <input type="password" id="userPassword" name="userPassword" class="form-control" <?= isset($formData) ? "" : "required" ?> placeholder="Введите пароль (32)" maxlength="32" value="<?= $formData["account_password"] ?>" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" id="showPassword" class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </span>
                    <span class="input-group-btn">
                        <button type="button" id="generatePassword" class="btn btn-secondary">Сгенерировать</button>
                    </span>
                </div>


            </div>
        </div>

        <?php
        if (isset($setPermission) && $setPermission == true){
            ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="permission">Уровень доступа</label>
                <div class="col-sm-10">
                    <select class="form-control" id="permission" name="permission" required>
                        <?php
                        $permission = isset($formData["user_permission"]) ? $formData["user_permission"] : "1";
                        ?>
                        <option value="1" <?=$permission == "1" ? "selected" : "" ?>>Сис.админ</option>
                        <option value="2" <?=$permission == "2" ? "selected" : "" ?>>Редактор</option>
                        <?php
                        if ($godPermission == true)
                            $selected = $formData["user_permission"] == 4 ? "selected" : "";

                            echo "<option value='4' $selected>Бог</option>";

                        ?>

                    </select>
                </div>
            </div>
        <?php
        }  else {
            ?>
            <input type="hidden" name="permission" value="1">
            <?php
        }?>


    </fieldset>

    <div class="form-group row">
        <div class="col-sm-12">
            <div class="float-sm-right">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
                <a href="../users/" class="btn btn-secondary">Отмена</a>
            </div>
        </div>
    </div>

</form>

<script type="text/javascript">
    var passShowBtn = $('#showPassword');
    var passInput = $('#userPassword');
    var generatePassBtn = $('#generatePassword');

    passShowBtn.mousedown(function(){
        passInput.prop("type", "text");
    });
    passShowBtn.mouseup(function(){
        passInput.prop("type", "password");
    });

    generatePassBtn.on("click", function(){
        var pass = generateRandomPass();
    });

    function generateRandomPass(length){
        length = typeof length !== 'undefined' ? length : 6;

    }


    $('#form').submit(function(){
        $("#submit-btn").prop('disabled',true);
    });
</script>