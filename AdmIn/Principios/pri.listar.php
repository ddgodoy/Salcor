<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$rsQry = $oMyDB->Query("SELECT * FROM principios ORDER BY priDicho;");

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nuevo principio</b>', 'link'=>'pri.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay principios del sistema.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'pri');
		$oSmarty->assign ('stTITLE', 'Listado de principios');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['priId'];
		$aPrincipios[$Id]['Dicho'] = $oMyDB->forShow($fila['priDicho']);
		$aPrincipios[$Id]['Explicacion'] = $oMyDB->forShow($fila['priExplicacion']);
	}
	$oSmarty->assign_by_ref('stPRINCIPIOS', $aPrincipios);
	$oSmarty->assign('stTITLE', 'Listado de principios');

	$oSmarty->display('pri.listar.tpl.html');
?>