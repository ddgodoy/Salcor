<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$rsQry = $oMyDB->Query("SELECT * FROM sectores ORDER BY secCodigo;");
	
	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nuevo sector</b>', 'link'=>'sec.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay sectores registrados.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'sec');
		$oSmarty->assign ('stTITLE', 'Listado de sectores');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['secId'];
		$aSectores[$Id]['Codigo'] = $oMyDB->forShow($fila['secCodigo']);
		$aSectores[$Id]['Nombre'] = $oMyDB->forShow($fila['secNombre']);
	}
	$oSmarty->assign_by_ref('stSECTORES', $aSectores);
	$oSmarty->assign('stTITLE', 'Listado de sectores');

	$oSmarty->display('sec.listar.tpl.html');
?>