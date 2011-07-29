<?php
	include_once '../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';

	if (isset($_SESSION['_usuId'])){$_SESSION = array();}

	session_destroy();
	redireccionar(getWeb('ADMIN').'sesion.php');
?>