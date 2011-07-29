<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
/*moneda-----------------------------------------------------------------------------*/
	$arMoneda = array();
	$rsMoneda = $oMyDB->Query("SELECT * FROM monedas ORDER BY monNombre;");
	while ($rsMoneda && $mFila = mysql_fetch_assoc($rsMoneda)){
		$arMoneda[$mFila['monId']] = '['.$mFila['monCodigo'].'] '.$oMyDB->forShow($mFila['monNombre']);
	}
/*-----------------------------------------------------------------------------------*/
	$stID = 0;
	$stMONEDA = '';
	$stVALOR  = 0;
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		require_once getLocal('DIV').'class.divisores.inc.php';

		$stID = $_POST['id'];
		$stMONEDA = $_POST['moneda'];
		$stVALOR  = $_POST['valor'];

		$oDivisor = new clsDivisores();
		$oDivisor->clearErrores();

		if ($oDivisor->findId($stID)){
			$oDivisor->setValor  ($stVALOR);
			$oDivisor->setMoneda ($stMONEDA);
			$oDivisor->setUsuario($_SESSION['_usuActivo']);

			if (!$oDivisor->hasErrores() && $oDivisor->Modificar()){
				redireccionar(getWeb('DIV').'div.listar.php');
			}
		}
		$stMONEDA = llenarSelect($arMoneda, $oDivisor->getMoneda());
		$stERROR = $oDivisor->getErrores();
	}
	elseif (isset($_GET['Id'])){
		require_once getLocal('DIV').'class.divisores.inc.php';

		$stID = $_GET['Id'];

		$oDivisor = new clsDivisores();
		$oDivisor->clearErrores();

		if (!$oDivisor->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar un divisor');
			$oSmarty->assign ('stMESSAGE', $oDivisor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stVALOR = $oDivisor->getValor();
		$stMONEDA = llenarSelect($arMoneda, $oDivisor->getMoneda());
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar un divisor');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stVALOR' , $stVALOR);
	$oSmarty->assign('stMONEDA', $stMONEDA);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar divisor');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('div.registrar.tpl.html');
?>