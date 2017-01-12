<?php

    require($_SERVER["DOCUMENT_ROOT"]."/include/config.php");
    if(!isset($_SESSION)) session_start();



    $logtype = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

    switch ($logtype) {
        case 'errors':
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/errors.log";
            $pageTitle = "Логи NEXT.Accounts";
            break;
        case "process_events":
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/process_events.log";
            $pageTitle = "События в системе NEXT.Accounts";
            break;
        case "debug":
            $log_filename = $_SERVER["DOCUMENT_ROOT"]."/log/debug.log";
            $pageTitle = "Дебаггинг NEXT.Accounts";
            break;
        default:
            $log_filename = null;
            break;
    }

    $log_text = "empty log file";
    if (!is_null($log_filename)) {
        $filename = $log_filename;
        //log_event("Log filename ".$filename);
        $log_text = ApplicationHelper::readFromFile($filename);

        if ($logtype == "process_events" || $logtype == "errors") {
            $log_text_split = $split_array = explode("\n", $log_text);
            $log_text_split = ApplicationHelper::reverseArray($log_text_split);
            $log_text = join("\n", $log_text_split);
        }

    }

    $page_header = !is_null($log_filename) ? "Файл ".$log_filename : "Открыть файл логов";
    $link_to_file = str_replace("/var/www/accounts.next.kz", '', $log_filename);

    require_once($_SERVER["DOCUMENT_ROOT"]."/shared/header.php");
    ?>

    <div class="container">
        <h1><?= $page_header ?></h1>

        <div class="row">
            <div class="col-sm-10">
                <p><a href="..<?= $link_to_file ?>">Открыть</a> текст логов</p>
                <pre><?= $log_text ?></pre>
            </div>

            <div class="col-sm-2">
                <div class="list-group">
                    <a href="../log/log_page.php?type=errors" class="list-group-item list-group-item-action">errors.log</a>
                    <a href="../log/log_page.php?type=process_events" class="list-group-item list-group-item-action">events.log</a>
                    <a href="../log/log_page.php?type=debug" class="list-group-item list-group-item-action">debug.log</a>
                </div>
            </div>
        </div>
    </div>



<?php
     require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php"); 
     ?>
