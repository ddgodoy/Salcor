<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$rsQry = $oMyDB->Query("SELECT * FROM paises ORDER BY paiCodigo;");

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nuevo pa&iacute;s</b>', 'link'=>'pai.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay pa&iacute;ses registrados.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'pai');
		$oSmarty->assign('stTITLE', 'Listado de pa&iacute;ses');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['paiId'];
		$aMonedas[$Id]['Codigo'] = $oMyDB->forShow($fila['paiCodigo']);
		$aMonedas[$Id]['Nombre'] = $oMyDB->forShow($fila['paiNombre']);
	}
	$oSmarty->assign_by_ref('stVALORES', $aMonedas);
	$oSmarty->assign('stTITLE', 'Listado de pa&iacute;ses');

	$oSmarty->display('pai.listar.tpl.html');
?>