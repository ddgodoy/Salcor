<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$oMyDB = new clsMyDB();

	$error = '';
	if (empty($_REQUEST['Id']))
		$error = 'Error al acceder a la página.';
	elseif (empty($_REQUEST['confirmado']))
		$error = 'Debe confirmar la eliminación del valor.';

	if (!empty($error)){
		$oSmarty->assign ('stTITLE'  , 'Borrar un valor sugerido');
		$oSmarty->assign ('stMESSAGE', $error);
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$IDs = is_array($_REQUEST['Id'])?$_REQUEST['Id']:array($_REQUEST['Id']);

	foreach ($IDs as $id){
		if (!$oMyDB->Command("DELETE FROM sugeridos WHERE sugId = $id;")){
			$oSmarty->assign ('stTITLE'  , 'Borrar un valor sugerido');
			$oSmarty->assign ('stMESSAGE', 'No fue posible eliminar el valor');
			$oSmarty->display('information.tpl.html');
			exit();
		}
	}
	redireccionar(getWeb('SUG').'sug.listar.php');
?>