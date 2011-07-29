<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('SEC').'class.sectores.inc.php';

	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oSector = new clsSectores();
		$oSector->clearErrores();
		
		$oSector->setCodigo($stCODIGO);
		$oSector->setNombre($stNOMBRE);

		if (!$oSector->hasErrores() && $oSector->Registrar()){
			redireccionar(getWeb('SEC').'sec.listar.php');
		}
		$stERROR = $oSector->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo sector');
	$oSmarty->assign('stBTN_ACTION', 'Nuevo');

	$oSmarty->display('sec.registrar.tpl.html');
?>