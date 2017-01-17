<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------


$mysql = MysqlHelper::getNewInstance();

$startFrom = isset($_GET["p"]) ? intval($_GET["p"]) : 0;
$rowLimit = isset($_GET["limit"]) ? intval($_GET["limit"]) : 50;

$instances = $mysql->getCenters();
$pageTitle = "Список сущностей NEXT.Accounts";
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";
?>
    <div class="container">
        <div class="mt-2">
            <h1>Список центров (объектов)</h1>
        </div>
        

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
                                <a class="btn btn-secondary" href="../centers/">Все записи</a>
                                <a class="btn btn-secondary" href="../centers/create.php"><i class="fa fa-plus"  aria-hidden="true"></i> Создать новую запись</a>
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
                            <th>Название</th>
                            <th>Код объекта</th>
                            <th>Лимит</th>
                            <th>Взял акков</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        for ($i = 0; $i < count($instances); $i++){

                            $value = $instances[$i];

                            ?>
                            <tr>
                                <td><?= $value->id ?></td>
                                <td><a href="../centers/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->name ?></a></td>
                                <td><?= $value->code ?></td>
                                <td><?= $value->limit ?></td>
                                <td><?= $value->count ?></td>
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