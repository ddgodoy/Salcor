<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('BOL').'class.bolsas.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación de la bolsa.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar una bolsa');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oBolsa = new clsBolsas();
	foreach ($IDs as $id){
		if (!$oBolsa->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar una bolsa');
			$oSmarty->assign ('stMESSAGE', $oBolsa->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oBolsa->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar una bolsa');
			$oSmarty->assign ('stMESSAGE', $oBolsa->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('BOL').'bol.listar.php');
?>