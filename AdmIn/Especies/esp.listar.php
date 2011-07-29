<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$rsQry = $oMyDB->Query("SELECT * FROM especies ORDER BY espCodigo;");

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nueva especie</b>', 'link'=>'esp.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay especies registradas.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'esp');
		$oSmarty->assign ('stTITLE', 'Listado de especies');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['espId'];
		$aEspecies[$Id]['Codigo'] = $oMyDB->forShow($fila['espCodigo']);
		$aEspecies[$Id]['Nombre'] = $oMyDB->forShow($fila['espNombre']);
	}
	$oSmarty->assign_by_ref('stVALORES', $aEspecies);
	$oSmarty->assign('stTITLE', 'Listado de especies');

	$oSmarty->display('esp.listar.tpl.html');
?>