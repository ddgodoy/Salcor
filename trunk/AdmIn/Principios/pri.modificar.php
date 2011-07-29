<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PRI').'class.principios.inc.php';

	$stID = 0;
	$stDICHO = '';
	$stEXPLICACION = '';
	$stERROR = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stDICHO = $_POST['dicho'];
		$stEXPLICACION = $_POST['explicacion'];

		$oPrincipio = new clsPrincipios();
		$oPrincipio->clearErrores();

		if ($oPrincipio->findId($stID)){
			$oPrincipio->setDicho($stDICHO);
			$oPrincipio->setExplicacion($stEXPLICACION);

			if (!$oPrincipio->hasErrores() && $oPrincipio->Modificar()){
				redireccionar(getWeb('PRI').'pri.listar.php');
			}
		}
		$stERROR = $oPrincipio->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oPrincipio = new clsPrincipios();
		$oPrincipio->clearErrores();

		if (!$oPrincipio->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar un principio');
			$oSmarty->assign ('stMESSAGE', $oPrincipio->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stDICHO = $oPrincipio->editDicho();
		$stEXPLICACION = $oPrincipio->editExplicacion();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar un principio');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR', $stERROR);
	$oSmarty->assign('stDICHO', $stDICHO);
	$oSmarty->assign('stEXPLICACION', $stEXPLICACION);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar principio');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('pri.registrar.tpl.html');
?>