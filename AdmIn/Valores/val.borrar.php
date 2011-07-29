<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del valor.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un valor');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oValor = new clsValores();
	foreach ($IDs as $id){
		if (!$oValor->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar un valor');
			$oSmarty->assign ('stMESSAGE', $oValor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oValor->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar un valor');
			$oSmarty->assign ('stMESSAGE', $oValor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('VAL').'val.listar.php');
?>