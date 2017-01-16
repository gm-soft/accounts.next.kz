<div class="container">

    <div class="mt-2">
        <h1>Авторизация на сайте</h1>
    </div>

    <form method="post" action="../session/login.php" class="form-horizontal">
        <input type="hidden" name="performed" value="true">
        <div class="form-group row">
            <label class="col-form-label col-sm-2" for="login">Логин:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="login" name="login" placeholder="Введите свой логин"  maxlength="30" minlength="3" required autofocus>
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
