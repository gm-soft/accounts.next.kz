<?php
require $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
//------------------------------------------------------

$mysql = MysqlHelper::getNewInstance();
$tableTitle = "Список аккаунтов";

$url = "http://accounts.next.kz/rest/ajax.requests.php?action=account.list";
if (isset($_GET["available"])) {
    $statement = $_GET["available"] == 'true' ? "true" : "false";
    $url .= "&available=".$statement;

    $tableTitle = $statement == "true" ? "Список свободных аккаунтов" : "Список занятых аккаунтов";
}

if (isset($_GET["banned"])) {
    $statement = $_GET["banned"] == 'true' ? "true" : "false";
    $url .= "&vac_banned=".$statement;
    $tableTitle = $statement == "true" ? "Список аккаунтов за баном" : "Список аккаунтов без бана";
}

$accountsResponse = ApplicationHelper::query($url, null, "GET");

$accounts = $accountsResponse["result"];
$total = $accountsResponse["total"];



$pageTitle = "Список сущностей NEXT.Accounts";
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";
?>
    <div class="container">
        <div class="mt-2">
            <h1><?= $tableTitle ?></h1>
        </div>
        

        <div class="row">
            <div id="pageNavigation" class="col-sm-3">

                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Навигация и фильтр</h4>
                        <p class="card-text">
                            <div class="btn-group-vertical">
                                <a class="btn btn-secondary" href="../accounts/">Все записи</a>
                                <a class="btn btn-secondary" href="../accounts/?available=true">Свободные аккаунты</a>
                                <a class="btn btn-secondary" href="../accounts/?available=false">Занятые аккаунты</a>
                                <a class="btn btn-secondary" href="../accounts/?banned=true">Аккаунты с баном</a>
                                <a class="btn btn-secondary" href="../accounts/?banned=false">Аккаунты без бана</a>
                                <a class="btn btn-primary" href="../accounts/create.php"><i class="fa fa-plus"  aria-hidden="true"></i> Создать новую запись</a>
                            </div>

                        </p>
                    </div>
                </div>
            </div>


            <!---------->
            <div id="pageContent" class="col-sm-9">

                <div class="row">

                    <div class="col-sm-8">
                        <h4>Результат выборки. Кол-во записей: <?= $total ?></h4>
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
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>Доступн</th>
                            <th>Статус vac-бана</th>
                            <th>Последнее обновление</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        for ($i = 0; $i < count($accounts); $i++){

                            $value = $accounts[$i];
                            ?>
                            <tr>
                                <th><?= $i + 1 ?></th>
                                <td><?= $value["id"] ?></td>
                                <td><a href="../accounts/view.php?id=<?= $value["id"]?>" title="Открыть"><?= $value["login"] ?></a></td>
                                <td><?= $value["available"] == true ? "Доступен" : "Занят" ?></td>
                                <td><?= $value["vacBanned"] == true ? "Забанен" : "Без бана" ?></td>
                                <?php

                                    $updatedAt = substr($value["updatedAt"]["date"], 0, strpos($value["updatedAt"]["date"], ".000000"));
                                    $date = DateTime::createFromFormat("Y-m-d H:i:s", $updatedAt);
                                ?>
                                <td><?= date("Y-m-d H:i:s", $date->getTimestamp() + 6*3600) ?></td>
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

        function LoadList(instances){
            var content = "<table class='table table-hover'>" +
                "<thead>" +
                "<tr>" +
                "<th>#</th>" +
                "<th>ID</th>" +
                "<th>Логин (login)</th>" +
                "<th>Доступность для клиентов (available)</th>" +
                "<th>Статус vac-бана (vac_banned)</th>" +
                "</tr>" +
                "</thead>";

            if (instances != null && instances.length > 0){
                content += "<tbody>";
                for (var i = 0; i < instances.length; i++){
                    var item = instances[i];

                    var available = item["available"] == true ? "Доступен" : "Занят";
                    var vacBanned = item["vacBanned"] == true ? "Забанен" : "Без бана";

                    content += "<tr>" +
                        "<th>"+(i+1)+"</th>" +
                        "<td>"+item["id"]+"</td>" +
                        "<td><a href='../accounts/view.php?id="+item["id"]+"'>"+item["login"]+"</a></td>" +
                        "<td>"+ available+"</td>" +
                        "<td>"+vacBanned+"</td>" +
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
            var url = "http://accounts.next.kz/rest/ajax.requests.php";
            var prms = {
                "action" : "account.get",
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
                    LoadList(null);
                } else {
                    var instances = [response["result"]];
                    LoadList(instances);
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