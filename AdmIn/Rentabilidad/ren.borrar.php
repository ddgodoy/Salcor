<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';

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
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$ID = $_REQUEST['Id'];
	$oMyDB = new clsMyDB();

	if (!$oMyDB->Command("DELETE FROM compras WHERE cmpId = $ID;")){
		$oSmarty->assign ('stTITLE'  , 'Borrar una compra');
		$oSmarty->assign ('stMESSAGE', 'No es posible borrar esta compra.');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	redireccionar(getWeb('REN').'ren.listar.php');
?>