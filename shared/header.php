
<?php
$pageTitle = isset($pageTitle) ? $pageTitle : "Next.Accounts";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $pageTitle ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <script src="../assets/js/tether.min.js"></script>
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
  
  </head>
  <body>

  <nav class="navbar navbar-dark">
      <div class="container">
          <a class="navbar-brand" href="../"><b>NEXT.Accounts</b></a>

          <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                  aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></button>
          <div class="collapse navbar-toggleable-md" id="navbarResponsive">

              <ul class="nav navbar-nav">

                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="accounts" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Объекты и пользователи</a>
                      <div class="dropdown-menu" aria-labelledby="accounts">

                          <h6 class="dropdown-header">Аккаунты Steam</h6>
                          <a class="dropdown-item" href="../accounts/">Список аккаунтов</a>
                          <a class="dropdown-item" href="../accounts/create.php">Добавить новый аккаунт</a>
                          <div class="dropdown-divider"></div>

                          <h6 class="dropdown-header">Центры (объекты)</h6>
                          <a class="dropdown-item" href="../centers/">Список центров</a>
                          <a class="dropdown-item" href="../centers/create.php">Добавить новый объект</a>
                              <div class="dropdown-divider"></div>

                          <h6 class="dropdown-header">Пользователи системы</h6>
                          <a class="dropdown-item" href="../users/">Список пользователей</a>
                          <a class="dropdown-item" href="../users/create.php">Добавить нового пользователя</a>

                      </div>
                  </li>

                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="logs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Логи</a>
                      <div class="dropdown-menu" aria-labelledby="logs">
                          <a class="dropdown-item" href="../log/log_page.php?type=errors">Ошибки <i class="fa fa-exclamation-triangle float-sm-right" aria-hidden="true"></i></a>
                          <a class="dropdown-item" href="../log/log_page.php?type=process_events">События <i class="fa fa-info float-sm-right" aria-hidden="true"></i></a>
                          <a class="dropdown-item" href="../log/log_page.php?type=debug">Дебаг <i class="fa fa-bug float-sm-right" aria-hidden="true"></i></a>
                      </div>
                  </li>

                  <li class="nav-item"><a class="nav-link" href="../phpmyadmin/" target="_blank"><i class="fa fa-database" aria-hidden="true"></i> База данных</a></li>

                  <?php
                  $username = isset($_COOKIE["login"]) ? $_COOKIE["login"] : null;
                  $expired = isset($_COOKIE["expired"]) ? $_COOKIE["expired"] : 3601;

                  if( is_null($username) || time() > $expired)
                  {
                    CookieHelper::ClearCookies();
                      ?>
                      <li class="nav-item  float-sm-right"><a class="nav-link" href="../session/register.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Регистрация</a></li>
                      <li class="nav-item  float-sm-right"><a class="nav-link" href="../session/login.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Войти</a></li>

                      <?php
                  } else {
                      ?>

                      <li class="nav-item dropdown  float-sm-right">
                          <a class="nav-link dropdown-toggle" href="#" id="profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-user" aria-hidden="true"></i> <?= $username ?>
                          </a>
                          <div class="dropdown-menu" aria-labelledby="profile">
                              <a class="dropdown-item" href="../session/profile.php"><i class="fa fa-cog" aria-hidden="true"></i> Профайл</a>
                              <a class="dropdown-item" href="../session/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Выйти</a>
                          </div>
                      </li>


                      <?php
                  }
                  ?>


              </ul>
          </div>
      </div>

  </nav>


<?php
    if (isset($_SESSION["errors"])) {
        ?>

        <div class="container">
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                foreach ($_SESSION["errors"] as $key => $value){
                    echo $value."<br>";
                }
                ?>
            </div>
        </div>
        <?php
        unset($_SESSION["errors"]);
    }

    if (isset($_SESSION["success"])) {
    ?>
    <div class="container">
      <div class="alert alert-success alert-dismissible fade in">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <?php
          foreach ($_SESSION["success"] as $key => $value){
              echo $value."<br>";
          }
          ?>
      </div>
    </div>
    <?php
    unset($_SESSION["success"]);
    }
?>