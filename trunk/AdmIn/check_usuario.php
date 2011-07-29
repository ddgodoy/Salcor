<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	if (empty($_SESSION['_usuActivo'])){
		$oSmarty->assign ('stTITLE', 'Se requiere un usuario registrado');
		$oSmarty->assign ('stMESSAGE', 'No hay usuarios registrados.<br />Registre alguno para continuar.');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oMyDB = new clsMyDB();

	if ($_SESSION['_usuLogin'] == SUPER_ADMIN){
		$arUsersDB = array();
		$rsUsersDB = $oMyDB->Query("SELECT * FROM usuarios WHERE usuLogin != 'admin' ORDER BY usuApellido;");
		while ($rsUsersDB && $userDB = mysql_fetch_assoc($rsUsersDB)){
			$arUsersDB[$userDB['usuId']] = $oMyDB->forShow($userDB['usuApellido'].', '.$userDB['usuNombre']);
		}
		if(!empty($_REQUEST['usuario_db'])){
			$_SESSION['_usuActivo'] = $_REQUEST['usuario_db'];
		}
		if (count($arUsersDB) > 0){$oSmarty->assign('stUSUARIOS_DB', llenarSelect($arUsersDB, $_SESSION['_usuActivo']));}
	}
?>