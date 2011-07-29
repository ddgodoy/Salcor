<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
/*moneda-----------------------------------------------------------------------------*/
	$arMoneda = array();
	$rsMoneda = $oMyDB->Query("SELECT * FROM monedas ORDER BY monNombre;");
	while ($rsMoneda && $mFila = mysql_fetch_assoc($rsMoneda)){
		$arMoneda[$mFila['monId']] = $oMyDB->forShow($mFila['monNombre']);
	}
/*-----------------------------------------------------------------------------------*/
	$stMONEDA = llenarSelect($arMoneda);
	$stVALOR  = 0;
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		require_once getLocal('DIV').'class.divisores.inc.php';

		$stMONEDA = $_POST['moneda'];
		$stVALOR  = $_POST['valor'];

		$oDivisor = new clsDivisores();
		$oDivisor->clearErrores();

		$oDivisor->setValor  ($stVALOR);
		$oDivisor->setMoneda ($stMONEDA);
		$oDivisor->setUsuario($_SESSION['_usuActivo']);

		if (!$oDivisor->hasErrores() && $oDivisor->Registrar()){
			redireccionar(getWeb('DIV').'div.listar.php');
		}
		$stMONEDA = llenarSelect($arMoneda);
		$stERROR = $oDivisor->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stVALOR' , $stVALOR);
	$oSmarty->assign('stMONEDA', $stMONEDA);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo divisor');
	$oSmarty->assign('stBTN_ACTION', 'Nuevo');

	$oSmarty->display('div.registrar.tpl.html');
?>