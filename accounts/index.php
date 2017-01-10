<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";
$mysql = MysqlHelper::getNewInstance();

$startFrom = isset($_GET["p"]) ? intval($_GET["p"]) : 0;
$rowLimit = isset($_GET["limit"]) ? intval($_GET["limit"]) : 50;

$accounts = $mysql->getSteamAccounts();

?>
    <div class="container">
        <h1>Список аккаунтов</h1>

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
                                $nextUrl = count($accounts) == 50 ? "../accounts?p=".($startFrom + $rowLimit)."&limit=".$rowLimit : null;


                                ?>

                                <a href="<?= $prevUrl ?>" class="btn btn-outline-secondary <?= is_null($prevUrl) ? "disabled" : "" ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                                <a href="<?= $nextUrl ?>" class="btn btn-outline-secondary <?= is_null($nextUrl) ? "disabled" : "" ?>"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                            </div>
                            <hr>
                            <div class="btn-group-vertical">
                                <a class="btn btn-secondary" href="../accounts/">Все записи</a>
                                <a class="btn btn-secondary" href="../accounts/create.php"><i class="fa fa-plus"  aria-hidden="true"></i> Создать новую запись</a>
                            </div>

                        </p>
                    </div>
                </div>
            </div>


            <!---------->
            <div id="pageContent" class="col-sm-9">

                <div class="row">

                    <div class="col-sm-8">
                        <h4>Результат выборки</h4>
                    </div>
                    <div class="col-sm-4 float-sm-right">
                        <div class="form-group">

                            <div class="input-group">
                                <input type="text" class="form-control" id="searchValue" placeholder="Поиск значения">

                                <span class="input-group-btn">
                                    <button type="button" id="searchBtn" class="btn btn-secondary"><i class="fa fa-search"  aria-hidden="true"></i></button>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <div id="outputDiv">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>id</th>
                            <th>Логин (login)</th>
                            <th>Доступность для клиентов (available)</th>
                            <th>Статус vac-бана (vac_banned)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        for ($i = 0; $i < count($accounts); $i++){

                            $value = $accounts[$i];
                            ?>
                            <tr>
                                <th><?= $i + 1 ?></th>
                                <td><?= $value->id ?></td>
                                <td><a href="../accounts/view.php?id=<?= $value->id?>" title="Открыть"><?= $value->login ?></a></td>
                                <td><?= $value->available == true ? "Доступен" : "Занят" ?></td>
                                <td><?= $value->vacBanned == true ? "Забанен" : "Без бана" ?></td>
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
    <script>
        var outputDiv = $('#outputDiv');
        var searchBtn = $('#searchBtn');
        var searchValueInput = $('#searchValue');

        searchBtn.on("click", function(){
            var value = searchValueInput.val();
            if (value == "") return;
            SearchByField(value);
        });

        function LoadListOfClients(clients){
            var content = "<table class='table table-striped'>" +
                "<thead>" +
                "<tr>" +
                "<th>#</th>" +
                "<th>HABB ID</th>" +
                "<th>Полное имя</th>" +
                "<th>Телефон</th>" +
                "<th>Email</th>" +
                "</tr>" +
                "</thead>";

            if (clients != null && clients.length > 0){
                content += "<tbody>";
                for (var i = 0; i < clients.length; i++){
                    var item = clients[i];
                    content += "<tr>" +
                        "<th>"+(i+1)+"</th>" +
                        "<td>"+item["id"]+"</td>" +
                        "<td><a href='../clients/client.php?id="+item["id"]+"'>"+item["name"]+" "+item["last_name"]+"</a></td>" +
                        "<td>"+item["phone"]+"</td>" +
                        "<td>"+item["email"]+"</td>" +
                        "</tr>";
                }
                content += "</tbody>";
            }

            content += "</table>";
            outputDiv.html(content);
        }

        function SearchByField(value){
            searchBtn.prop('disabled', true);
            searchBtn.addClass('disabled');
            var url = "http://newb24.next.kz/rest/ajax.responses.php";
            var prms = {
                "action" : "client.get",
                "value" : value

            };
            var request = $.ajax({
                url : url,
                type: "post",
                data: prms
            });
            request.done(function (response, textStatus){
                console.log(response);
                if (response["result"] == false) {
                    LoadListOfClients(null);
                } else {
                    var clients = [response["result"]];
                    LoadListOfClients(clients);
                }

            });
            request.fail(function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });
            request.always(function () {
                searchBtn.prop('disabled', false);
                searchBtn.removeClass('disabled');
            });
        }


    </script>





<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/footer.php";