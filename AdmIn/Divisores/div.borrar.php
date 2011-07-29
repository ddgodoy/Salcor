<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('DIV').'class.divisores.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del divisor.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un divisor');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oDivisor = new clsDivisores();
	foreach ($IDs as $id){
		if (!$oDivisor->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar un divisor');
			$oSmarty->assign ('stMESSAGE', $oDivisor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oDivisor->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar un divisor');
			$oSmarty->assign ('stMESSAGE', $oDivisor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('DIV').'div.listar.php');
?>