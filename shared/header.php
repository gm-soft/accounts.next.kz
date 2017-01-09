<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Next.Accounts</title>
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
                      <a class="nav-link dropdown-toggle" href="#" id="logs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Логи</a>
                      <div class="dropdown-menu" aria-labelledby="logs">
                          <a class="dropdown-item" href="../log/log_page.php?type=errors">Ошибки (errors.log)</a>
                          <a class="dropdown-item" href="../log/log_page.php?type=process_events">События (process_events.log)</a>
                          <a class="dropdown-item" href="../log/log_page.php?type=debug">Дебаг (debug.log)</a>
                      </div>
                  </li>

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
                              <a class="dropdown-item" href="../session/profile.php"><i class="fa fa-user" aria-hidden="true"></i> Профайл</a>
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