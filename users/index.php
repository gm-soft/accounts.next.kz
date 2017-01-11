<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";
$mysql = MysqlHelper::getNewInstance();

$startFrom = isset($_GET["p"]) ? intval($_GET["p"]) : 0;
$rowLimit = isset($_GET["limit"]) ? intval($_GET["limit"]) : 50;

$instances = $mysql->getUsers();

?>
    <div class="container">
        <h1>Список пользователей системы</h1>

        <div class="row">
            <div id="pageNavigation" class="col-sm-3">

                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Навигация и фильтр</h4>
                        <p class="card-text">
                            Навигация по списку:

                            <div class="btn-group" role="group" aria-label="Navigation">
                                <?php
                                $prevUrl = $startFrom != 0 ? "../accounts/?p=".($startFrom - $rowLimit)."&limit=".$rowLimit : null;
                                $nextUrl = count($instances) == 50 ? "../accounts?p=".($startFrom + $rowLimit)."&limit=".$rowLimit : null;


                                ?>

                                <a href="<?= $prevUrl ?>" class="btn btn-outline-secondary <?= is_null($prevUrl) ? "disabled" : "" ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                                <a href="<?= $nextUrl ?>" class="btn btn-outline-secondary <?= is_null($nextUrl) ? "disabled" : "" ?>"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                            </div>
                            <hr>
                            <div class="btn-group-vertical">
                                <a class="btn btn-secondary" href="../users/">Все записи</a>
                                <a class="btn btn-secondary" href="../users/create.php"><i class="fa fa-plus"  aria-hidden="true"></i> Создать новую запись</a>
                            </div>

                        </p>
                    </div>
                </div>
            </div>


            <!---------->
            <div id="pageContent" class="col-sm-9">
                <div id="outputDiv">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>Уровень доступа</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        for ($i = 0; $i < count($instances); $i++){

                            $value = $instances[$i];
                            switch ($value->permission){
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
                            <tr>
                                <!--th><?= $i + 1 ?></th-->
                                <td><?= $value->id ?></td>
                                <td><a href="../users/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->login ?></a></td>
                                <td><?= $permission ?></td>
                            </tr>

                            <?php
                        }
                        ?>

                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/footer.php";