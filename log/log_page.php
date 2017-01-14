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
        <div class="mt-2">
            <h1><?= $page_header ?></h1>
        </div>


        <div class="btn-group">
            <a class="btn btn-secondary" href="../log/log_page.php?type=errors" >errors.log</a>
            <a class="btn btn-secondary" href="../log/log_page.php?type=process_events" >process_events.log</a>
            <a class="btn btn-secondary" href="../log/log_page.php?type=debug">debug.log</a>
        </div>

        <div>
            <p><a href="..<?= $link_to_file ?>">Открыть</a> текст логов</p>
            <pre><?= $log_text ?></pre>
        </div>
    </div>



<?php
     require_once($_SERVER["DOCUMENT_ROOT"]."/shared/footer.php"); 
     ?>
