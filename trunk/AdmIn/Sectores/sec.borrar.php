<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('SEC').'class.sectores.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del sector.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un sector');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oSector = new clsSectores();
	foreach ($IDs as $id){
		if (!$oSector->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar un sector');
			$oSmarty->assign ('stMESSAGE', $oSector->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oSector->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar un sector');
			$oSmarty->assign ('stMESSAGE', $oSector->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('SEC').'sec.listar.php');
?>