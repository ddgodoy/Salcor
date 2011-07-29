<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PAI').'class.paises.inc.php';

	$stID = 0;
	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oPais = new clsPaises();
		$oPais->clearErrores();

		if ($oPais->findId($stID)){
			$oPais->setCodigo($stCODIGO);
			$oPais->setNombre($stNOMBRE);

			if (!$oPais->hasErrores() && $oPais->Modificar()){
				redireccionar(getWeb('PAI').'pai.listar.php');
			}
		}
		$stERROR = $oPais->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oPais = new clsPaises();
		$oPais->clearErrores();

		if (!$oPais->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar un pa&iacute;s');
			$oSmarty->assign ('stMESSAGE', $oPais->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stCODIGO = $oPais->editCodigo();
		$stNOMBRE = $oPais->editNombre();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar un pa&iacute;s');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar pa&iacute;s');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('pai.registrar.tpl.html');
?>