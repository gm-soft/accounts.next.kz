<?php
	session_start();
	require_once $_SERVER["DOCUMENT_ROOT"]."/include/config.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/shared/header.php";

	echo "<h1 class=\"container\">Hello world</h1>";
	require_once $_SERVER["DOCUMENT_ROOT"]."/shared/footer.php";