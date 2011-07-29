<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('USU').'class.usuarios.inc.php';

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del usuario.';

	if (!empty ($error)){
		$oSmarty->assign('stTITLE'  , 'Borrar usuario');
		$oSmarty->assign('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	$oUsuario = new clsUsuarios();
	foreach ($IDs as $id){	
		if (!$oUsuario->findId($id)){
			$oSmarty->assign ('stTITLE'  , 'Borrar usuario');
			$oSmarty->assign ('stMESSAGE', $oUsuario->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		if ($oUsuario->Login != SUPER_ADMIN){
			if (!$oUsuario->Borrar()){
				$oSmarty->assign ('stTITLE'  , 'Borrar usuario');
				$oSmarty->assign ('stMESSAGE', $oUsuario->getErrores());
				$oSmarty->display('information.tpl.html');
				exit();
			}
		}
	}
	redireccionar(getWeb('USU').'usu.listar.php');
?>