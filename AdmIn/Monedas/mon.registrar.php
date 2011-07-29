<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('MON').'class.monedas.inc.php';

	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oMoneda = new clsMonedas();
		$oMoneda->clearErrores();

		$oMoneda->setCodigo($stCODIGO);
		$oMoneda->setNombre($stNOMBRE);

		if (!$oMoneda->hasErrores() && $oMoneda->Registrar()){
			redireccionar(getWeb('MON').'mon.listar.php');
		}
		$stERROR = $oMoneda->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nueva moneda');
	$oSmarty->assign('stBTN_ACTION', 'Nueva');

	$oSmarty->display('mon.registrar.tpl.html');
?>