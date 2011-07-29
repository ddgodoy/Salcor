<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ESP').'class.especies.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación de la especie.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar una especie');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oEspecie = new clsEspecies();
	foreach ($IDs as $id){
		if (!$oEspecie->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar una especie');
			$oSmarty->assign ('stMESSAGE', $oEspecie->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oEspecie->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar una especie');
			$oSmarty->assign ('stMESSAGE', $oEspecie->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('ESP').'esp.listar.php');
?>