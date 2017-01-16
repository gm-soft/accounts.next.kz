<div class="container">

    <div class="mt-2">
        <h1>Регистрация</h1>
    </div>



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