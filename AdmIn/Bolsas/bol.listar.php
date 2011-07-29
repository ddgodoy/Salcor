<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();
	$stQry = "SELECT bolId, bolCodigo, bolNombre, CONCAT(bolDesdeH,':',bolDesdeM) as bolDesde, ".
					 "CONCAT(bolHastaH,':',bolHastaM) as bolHasta FROM bolsas ORDER BY bolCodigo;";
	$rsQry = $oMyDB->Query($stQry);

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nueva bolsa</b>', 'link'=>'bol.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay bolsas registradas.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'bol');
		$oSmarty->assign('stTITLE', 'Listado de bolsas');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['bolId'];
		$aBolsas[$Id]['Codigo'] = $oMyDB->forShow($fila['bolCodigo']);
		$aBolsas[$Id]['Nombre'] = $oMyDB->forShow($fila['bolNombre']);
		$aBolsas[$Id]['Desde']  = $oMyDB->forShow($fila['bolDesde']);
		$aBolsas[$Id]['Hasta']  = $oMyDB->forShow($fila['bolHasta']);
	}
	$oSmarty->assign_by_ref('stVALORES', $aBolsas);
	$oSmarty->assign('stTITLE', 'Listado de bolsas');

	$oSmarty->display('bol.listar.tpl.html');
?>