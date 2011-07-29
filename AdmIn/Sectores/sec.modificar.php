<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('SEC').'class.sectores.inc.php';

	$stID = 0;
	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oSector = new clsSectores();
		$oSector->clearErrores();

		if ($oSector->findId($stID)){
			$oSector->setCodigo($stCODIGO);
			$oSector->setNombre($stNOMBRE);

			if (!$oSector->hasErrores() && $oSector->Modificar()){
				redireccionar(getWeb('SEC').'sec.listar.php');
			}
		}
		$stERROR = $oSector->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oSector = new clsSectores();
		$oSector->clearErrores();

		if (!$oSector->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar un sector');
			$oSmarty->assign ('stMESSAGE', $oSector->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stCODIGO = $oSector->editCodigo();
		$stNOMBRE = $oSector->editNombre();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar un sector');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar sector');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('sec.registrar.tpl.html');
?>