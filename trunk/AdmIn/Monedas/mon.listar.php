<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$rsQry = $oMyDB->Query("SELECT * FROM monedas ORDER BY monCodigo;");

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nueva moneda</b>', 'link'=>'mon.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay monedas registradas.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'mon');
		$oSmarty->assign ('stTITLE', 'Listado de monedas');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['monId'];
		$aMonedas[$Id]['Codigo'] = $oMyDB->forShow($fila['monCodigo']);
		$aMonedas[$Id]['Nombre'] = $oMyDB->forShow($fila['monNombre']);
	}
	$oSmarty->assign_by_ref('stVALORES', $aMonedas);
	$oSmarty->assign('stTITLE', 'Listado de monedas');

	$oSmarty->display('mon.listar.tpl.html');
?>