<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';

	$oMyDB = new clsMyDB();

	$stVALOR  = $_POST['valor'];
	$stFECALTA= $oMyDB->verifyDate($_POST['fecalta'],0,'',date('Y')+2);
	$stFECSUG = $oMyDB->verifyDate($_POST['fecsug'],0,'',date('Y')+5);
	$stPREALTA= $_POST['prealta'];
	$stPRESUG = $_POST['presug'];
	$stSUGIRIO= $_POST['sugirio'];

	$oMyDB->Command("INSERT INTO sugeridos (sugValor, sugFecAlta, sugFecSug, sugPrecioAlta, sugPrecioSug, ".
								  "sugSugirio) VALUES ($stVALOR, '$stFECALTA', '$stFECSUG', $stPREALTA, $stPRESUG, '$stSUGIRIO');");
	echo 'ok';
?>