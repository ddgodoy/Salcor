<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('MON').'class.monedas.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación de la moneda.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar una moneda');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oMoneda = new clsMonedas();
	foreach ($IDs as $id){
		if (!$oMoneda->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar una moneda');
			$oSmarty->assign ('stMESSAGE', $oMoneda->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oMoneda->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar una moneda');
			$oSmarty->assign ('stMESSAGE', $oMoneda->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('MON').'mon.listar.php');
?>