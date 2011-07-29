<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PRI').'class.principios.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del principio.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un principio');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oPrincipio = new clsPrincipios();
	foreach ($IDs as $id){
		if (!$oPrincipio->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar un principio');
			$oSmarty->assign ('stMESSAGE', $oPrincipio->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oPrincipio->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar un principio');
			$oSmarty->assign ('stMESSAGE', $oPrincipio->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('PRI').'pri.listar.php');
?>