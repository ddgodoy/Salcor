<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';

	$QUERY = "SELECT divisores.*, monNombre FROM divisores, monedas WHERE divMoneda = ".
			 		 "monId AND divUsuario = ".$_SESSION['_usuActivo']." ORDER BY monNombre;";
	$rsQry = $oMyDB->Query($QUERY);

	if (!$rsQry){
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nuevo divisor</b>', 'link'=>'div.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay divisores registrados.');
		$oSmarty->assign ('stPREFIJO_ACCION', 'div');
		$oSmarty->assign('stTITLE', 'Listado de divisores');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	while ($fila = mysql_fetch_assoc($rsQry)){
		$Id = $fila['divId'];
		$aDivisores[$Id]['Valor'] = $fila['divValor'];
		$aDivisores[$Id]['Moneda'] = $fila['monNombre'];
	}
	$oSmarty->assign_by_ref('stDIVISORES', $aDivisores);
	$oSmarty->assign('stTITLE', 'Listado de divisores');

	$oSmarty->display('div.listar.tpl.html');
?>