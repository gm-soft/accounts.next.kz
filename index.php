<?php
	//session_start();
	require_once $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";

	$mysql = MysqlHelper::getNewInstance();
	$centers = $mysql->getCenters();
	$accounts = $mysql->getSteamAccounts();

    $accountCount = count($accounts);
    $freeAccounts = [];
    $takenAccounts = [];
    $vacBanned = [];
    $vacBannedFree = [];

    foreach ($accounts as $account){
        if ($account->available == true) $freeAccounts[] = $account;
        else $takenAccounts[] = $account;

        if ($account->vacBanned == true) $vacBanned[] = $account;
        else $vacBannedFree[] = $account;
    }
    ?>
    <div class="container">
        <p class="mt-2">
            <h1>Система раздачи лицензий</h1>
        </p>

        <p>
        <p>
            <h3>Загрузка центров</h3>
        </p>
            <div class="row">

                <?php
                foreach ($centers as $center){
                    ?>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title"><?= $center->name ?></h4>
                                <h6 class="card-subtitle mb-2 text-muted">Индивидуальный код <b><?= $center->code ?></b></h6>
                                <p class="card-text">
                                    Лимит центра: <?= $center->limit ?> <br>
                                    Аккаунты центра: <?= $center->count ?> <br>
                                </p>
                                <a href="../centers/view.php?id=<?= $center->id?>" class="card-link">Просмотреть информацию</a>
                            </div>
                        </div>
                    </div>

                    <?php } ?>
            </div>
        </p>

        <div class="row">

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Занятость аккаунтов</h4>
                        <h6 class="card-subtitle mb-2 text-muted">График доступности/занятости аккаунтов </h6>
                        <p class="card-text">

                            <?php

                            $freeCount = count($freeAccounts);
                            $takenCount = count($takenAccounts);

                            $freePercent = intval(round(($freeCount / $accountCount) * 100));
                            $takenPercent = 100 - $freePercent;

                            ?>

                            Свободных аккаунтов: <?= $freeCount ?> (~<?= $freePercent ?>%)<br>
                            Занятых аккаунтов: <?= $takenCount ?> (~<?= $takenPercent ?>%)<br>

                            <div class="c100 p<?= $freePercent?> green">
                                <span><?= $freePercent?>%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>

                            <div class="c100 p<?= $takenPercent?> red">
                                <span><?= $takenPercent?>%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>




                        </p>
                    </div>
                </div>
            </div>


            <div class="col-sm-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">VAC - статусы аккаунтов</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Соотношение забаненных аккаунтов</h6>
                        <p class="card-text">

                            <?php

                            $freeCount = count($vacBannedFree);
                            $takenCount = count($vacBanned);

                            $freePercent = intval(round(($freeCount / $accountCount) * 100, PHP_ROUND_HALF_DOWN));
                            $takenPercent = 100 - $freePercent;


                            ?>

                            Аккаунты без бана: <?= $freeCount ?> (~<?= $freePercent ?>%)<br>
                            Аккаунты с баном: <?= $takenCount ?> (~<?= $takenPercent ?>%)<br>

                            <div class="c100 p<?= $freePercent?> green">
                                <span><?= $freePercent?>%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>

                            <div class="c100 p<?= $takenPercent?> red">
                                <span><?= $takenPercent?>%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>




                        </p>
                    </div>
                </div>
            </div>

        </div>




    </div>

    <?php
	require_once $_SERVER["DOCUMENT_ROOT"]."/shared/footer.php";