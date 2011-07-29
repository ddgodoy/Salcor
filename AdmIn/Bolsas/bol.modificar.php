<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('BOL').'class.bolsas.inc.php';

	$stID = 0;
	$stCODIGO = '';
	$stNOMBRE = '';
	$stDESDEH = '';
	$stDESDEM = '';
	$stHASTAH = '';
	$stHASTAM = '';
	$stLAPSO  = '0';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];
		$stDESDEH = $_POST['desdeh'];
		$stDESDEM = $_POST['desdem'];
		$stHASTAH = $_POST['hastah'];
		$stHASTAM = $_POST['hastam'];
		$stLAPSO  = $_POST['lapso'];

		$oBolsa = new clsBolsas();
		$oBolsa->clearErrores();

		if ($oBolsa->findId($stID)){
			$oBolsa->setCodigo($stCODIGO);
			$oBolsa->setNombre($stNOMBRE);
			$oBolsa->setCodigo($stCODIGO);
			$oBolsa->setNombre($stNOMBRE);
			$oBolsa->setDesdeH($stDESDEH);
			$oBolsa->setDesdeM($stDESDEM);
			$oBolsa->setHastaH($stHASTAH);
			$oBolsa->setHastaM($stHASTAM);
			$oBolsa->setLapso ($stLAPSO);

			if (!$oBolsa->hasErrores() && $oBolsa->Modificar()){
				redireccionar(getWeb('BOL').'bol.listar.php');
			}
		}
		$stERROR = $oBolsa->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oBolsa = new clsBolsas();
		$oBolsa->clearErrores();

		if (!$oBolsa->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar una bolsa');
			$oSmarty->assign ('stMESSAGE', $oBolsa->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stCODIGO = $oBolsa->editCodigo();
		$stNOMBRE = $oBolsa->editNombre();
		$stDESDEH = $oBolsa->getDesdeH();
		$stDESDEM = $oBolsa->getDesdeM();
		$stHASTAH = $oBolsa->getHastaH();
		$stHASTAM = $oBolsa->getHastaM();
		$stLAPSO  = $oBolsa->getLapso();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar una bolsa');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);
	$oSmarty->assign('stDESDEH', $stDESDEH);
	$oSmarty->assign('stDESDEM', $stDESDEM);
	$oSmarty->assign('stHASTAH', $stHASTAH);
	$oSmarty->assign('stHASTAM', $stHASTAM);
	$oSmarty->assign('stLAPSO' , $stLAPSO);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar bolsa');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('bol.registrar.tpl.html');
?>