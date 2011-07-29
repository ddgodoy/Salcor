<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';

	$_respuesta = '';

	if (!empty($_POST['valor'])){
		$oMyDB = new clsMyDB();
		$_valor = $_POST['valor'];
		
		if ($_POST['actual']=='S'){
			$_nuevo = 'N';
			$_image = 'star_disabled.gif';
		} else {
			$_nuevo = 'S';
			$_image = 'recomendado.gif';
		}
		if ($oMyDB->Command("UPDATE valores SET valRecomendado = '$_nuevo' WHERE valId = $_valor;")){
			$_respuesta = $_image;
		}
	}
	echo $_respuesta;
?>