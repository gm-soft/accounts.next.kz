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

if (isset($_GET["center"])){
    $_GET["center"] = ApplicationHelper::ClearInputData($_GET["center"]);
    $url .= "&center=".$_GET["center"];
    $tableTitle = "Аккаунты в центре ".$_GET["center"];
}

$accountsResponse = ApplicationHelper::query($url, null, "GET");

$accounts = $accountsResponse["result"];
$total = $accountsResponse["total"];

$centers = $mysql->getCenters();



$pageTitle = "Список сущностей NEXT.Accounts";
require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";
?>
    <div class="container">
        <div class="mt-2">
            <h1><?= $tableTitle ?></h1>
        </div>

        <p>
            <h4>Результат выборки. Кол-во записей: <?= $total ?></h4>
        </p>

            <!---------->
        <div id="pageContent">

            <div class="row">

                <div class="col-sm-8">
                    <div class="btn-group">
                        <a class="btn btn-secondary" href="../accounts/">Все записи</a>

                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Доступ
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="../accounts/?available=true">Свободные</a>
                                <a class="dropdown-item" href="../accounts/?available=false">Занятые</a>
                            </div>
                        </div>

                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop2" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                VAC-статус
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                                <a class="dropdown-item" href="../accounts/?banned=true">C баном</a>
                                <a class="dropdown-item" href="../accounts/?banned=false">Без бана</a>
                            </div>
                        </div>

                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop3" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                В объектах
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
                                <?php
                                foreach ($centers as $center){
                                    echo "<a class=\"dropdown-item\" href=\"../accounts/?center=".$center->code."\">".$center->name."</a>";
                                }


                                ?>
                            </div>
                        </div>

                    </div>

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
                        <th>Доступ</th>
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