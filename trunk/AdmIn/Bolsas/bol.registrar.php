<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('BOL').'class.bolsas.inc.php';

	$stCODIGO = '';
	$stNOMBRE = '';
	$stDESDEH = '00';
	$stDESDEM = '00';
	$stHASTAH = '00';
	$stHASTAM = '00';
	$stLAPSO  = '0';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];
		$stDESDEH = $_POST['desdeh'];
		$stDESDEM = $_POST['desdem'];
		$stHASTAH = $_POST['hastah'];
		$stHASTAM = $_POST['hastam'];
		$stLAPSO  = $_POST['lapso'];

		$oBolsa = new clsBolsas();
		$oBolsa->clearErrores();

		$oBolsa->setCodigo($stCODIGO);
		$oBolsa->setNombre($stNOMBRE);
		$oBolsa->setDesdeH($stDESDEH);
		$oBolsa->setDesdeM($stDESDEM);
		$oBolsa->setHastaH($stHASTAH);
		$oBolsa->setHastaM($stHASTAM);
		$oBolsa->setLapso ($stLAPSO);

		if (!$oBolsa->hasErrores() && $oBolsa->Registrar()){
			redireccionar(getWeb('BOL').'bol.listar.php');
		}
		$stERROR = $oBolsa->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);
	$oSmarty->assign('stDESDEH', $stDESDEH);
	$oSmarty->assign('stDESDEM', $stDESDEM);
	$oSmarty->assign('stHASTAH', $stHASTAH);
	$oSmarty->assign('stHASTAM', $stHASTAM);
	$oSmarty->assign('stLAPSO' , $stLAPSO);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nueva bolsa');
	$oSmarty->assign('stBTN_ACTION', 'Nueva');

	$oSmarty->display('bol.registrar.tpl.html');
?>