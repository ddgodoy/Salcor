<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ESP').'class.especies.inc.php';

	$stID = 0;
	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oEspecie = new clsEspecies();
		$oEspecie->clearErrores();

		if ($oEspecie->findId($stID)){
			$oEspecie->setCodigo($stCODIGO);
			$oEspecie->setNombre($stNOMBRE);

			if (!$oEspecie->hasErrores() && $oEspecie->Modificar()){
				redireccionar(getWeb('ESP').'esp.listar.php');
			}
		}
		$stERROR = $oEspecie->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oEspecie = new clsEspecies();
		$oEspecie->clearErrores();

		if (!$oEspecie->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar una especie');
			$oSmarty->assign ('stMESSAGE', $oEspecie->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stCODIGO = $oEspecie->editCodigo();
		$stNOMBRE = $oEspecie->editNombre();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar una especie');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar especie');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('esp.registrar.tpl.html');
?>