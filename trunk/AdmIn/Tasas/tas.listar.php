<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';

	$mensaje_update = !empty($_GET['mensaje'])?'?mensaje=ok':'';

	$srTasa = $oMyDB->Query("SELECT * FROM tasas WHERE tasUsuario = ".$_SESSION['_usuActivo']);
	if (!$srTasa){
		$oSmarty->assign ('stPREFIJO_ACCION', 'tas');
		$oSmarty->assign ('stTITLE'  , 'Rentabilidad esperada');
		$oSmarty->assign ('stLINKS'  , array(array('desc'=>'<b>Agregar rentabilidad esperada</b>', 'link'=>'tas.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay valores registrados');
		$oSmarty->display('information.tpl.html');
		exit();
	} else {
		redireccionar('tas.modificar.php'.$mensaje_update);
	}
?>