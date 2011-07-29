<?php
	require_once getLocal('COMMONS').'func.html.inc.php';
	noCache();

	if (!isset($_SESSION['_usuId'])){redireccionar(getWeb('ADMIN').'index.php');}
?>