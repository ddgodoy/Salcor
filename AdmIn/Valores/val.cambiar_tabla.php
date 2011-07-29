<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'JSON.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';

	$oMyDB = new clsMyDB();
	$oJson = new Services_JSON();

	$ids = $_POST['ids'];
	$tabla = $_POST['tabla'];
	$aDatos = $oJson->decode($ids);

	foreach ($aDatos as $dato){
		@$oMyDB->Query("UPDATE valores SET valTabla = '$tabla' WHERE valId = $dato;");
	}
	echo 'ok';
?>