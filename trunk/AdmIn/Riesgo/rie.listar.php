<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';

	$mensaje_update = !empty($_GET['mensaje'])?'?mensaje=ok':'';

	$srFactor = $oMyDB->Query("SELECT * FROM riesgo WHERE rieUsuario = ".$_SESSION['_usuActivo']);
	if (!$srFactor){
		$oSmarty->assign ('stPREFIJO_ACCION', 'rie');
		$oSmarty->assign ('stTITLE'  , 'Rango de Compra');
		$oSmarty->assign ('stLINKS'  , array(array('desc'=>'<b>Agregar rango de compra</b>', 'link'=>'rie.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay valores registrados');
		$oSmarty->display('information.tpl.html');
		exit();
	} else {
		redireccionar('rie.modificar.php'.$mensaje_update);
	}
?>