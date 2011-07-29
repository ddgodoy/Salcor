<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('MON').'class.monedas.inc.php';

	$stID = 0;
	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oMoneda = new clsMonedas();
		$oMoneda->clearErrores();

		if ($oMoneda->findId($stID)){
			$oMoneda->setCodigo($stCODIGO);
			$oMoneda->setNombre($stNOMBRE);

			if (!$oMoneda->hasErrores() && $oMoneda->Modificar()){
				redireccionar(getWeb('MON').'mon.listar.php');
			}
		}
		$stERROR = $oMoneda->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oMoneda = new clsMonedas();
		$oMoneda->clearErrores();

		if (!$oMoneda->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar una moneda');
			$oSmarty->assign ('stMESSAGE', $oMoneda->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stCODIGO = $oMoneda->editCodigo();
		$stNOMBRE = $oMoneda->editNombre();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar una moneda');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar moneda');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('mon.registrar.tpl.html');
?>