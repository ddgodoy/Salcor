<?php
	include_once '../cOmmOns/config.inc.php';

	if (empty($_GET['codigo'])){
			$stMENSAJE = 'No puede ingresar a esta página directamente.';
	} else {
		require_once getLocal('COMMONS').'class.mysql.inc.php';
		$oMyDB = new clsMyDB();
	
		$stCODIGO = $_GET['codigo'];
		$rsCODIGO = $oMyDB->Query("SELECT * FROM usuarios_tmp WHERE tmpCodigo = '$stCODIGO';");
	
		if ($rsCODIGO){
			$dtCODIGO = mysql_fetch_assoc($rsCODIGO);
			$iUSUARIO = $dtCODIGO['tmpUsuario'];
	
			$oMyDB->Command("DELETE FROM usuarios_tmp WHERE tmpCodigo = '$stCODIGO';");
			$oMyDB->Command("UPDATE usuarios SET usuEnabled = 'S' WHERE usuId = $iUSUARIO;");
			
			$stMENSAJE = 'La activaci&oacute;n se complet&oacute; correctamente.<br />'.
									 'Puede acceder al sistema a trav&eacute;s de la p&aacute;gina de inicio.';
		} else {
			$stMENSAJE = 'La informaci&oacute;n proporcionada para la validaci&oacute;n no es correcta.';
		}
	}
	$oSmarty->assign ('stTITLE', 'Activación del Registro');
	$oSmarty->assign ('stMESSAGE', $stMENSAJE);
	$oSmarty->display('information.tpl.html');
?>