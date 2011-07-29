<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PRI').'class.principios.inc.php';

	$stDICHO = '';
	$stEXPLICACION = '';
	$stERROR = '';

	if (isset($_POST['btn_accion'])){
		$stDICHO = $_POST['dicho'];
		$stEXPLICACION = $_POST['explicacion'];

		$oPrincipio = new clsPrincipios();
		$oPrincipio->clearErrores();

		$oPrincipio->setDicho($stDICHO);
		$oPrincipio->setExplicacion($stEXPLICACION);

		if (!$oPrincipio->hasErrores() && $oPrincipio->Registrar()){
			redireccionar(getWeb('PRI').'pri.listar.php');
		}
		$stERROR = $oPrincipio->getErrores();
	}
	$oSmarty->assign('stERROR', $stERROR);
	$oSmarty->assign('stDICHO', $stDICHO);
	$oSmarty->assign('stEXPLICACION', $stEXPLICACION);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo principio');
	$oSmarty->assign('stBTN_ACTION', 'Nuevo');

	$oSmarty->display('pri.registrar.tpl.html');
?>