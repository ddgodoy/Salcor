<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PAI').'class.paises.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del pa&iacute;s.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un pa&iacute;s');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oPais = new clsPaises();
	foreach ($IDs as $id){
		if (!$oPais->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar un pa&iacute;s');
			$oSmarty->assign ('stMESSAGE', $oPais->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if (!$oPais->Borrar()){
			$oSmarty->assign ('stTITLE'  , 'Borrar un pa&iacute;s');
			$oSmarty->assign ('stMESSAGE', $oPais->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('PAI').'pai.listar.php');
?>