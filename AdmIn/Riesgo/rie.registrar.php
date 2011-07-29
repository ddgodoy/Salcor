<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';

	$stC_BUENO    = 0;
	$stC_MUYBUENO = 0;
	$stC_EXCELENTE= 0;
	$stA_BUENO    = 0;
	$stA_MUYBUENO = 0;
	$stA_EXCELENTE= 0;
	$stP_BUENO    = 0;
	$stP_MUYBUENO = 0;
	$stP_EXCELENTE= 0;
	$stERROR = '';

	if (isset($_POST['btn_accion'])){
		require_once getLocal('RIE').'class.riesgo.inc.php';

		$stC_BUENO    = $_POST['c_bueno'];
		$stC_MUYBUENO = $_POST['c_muybueno'];
		$stC_EXCELENTE= $_POST['c_excelente'];
		$stA_BUENO    = $_POST['a_bueno'];
		$stA_MUYBUENO = $_POST['a_muybueno'];
		$stA_EXCELENTE= $_POST['a_excelente'];
		$stP_BUENO    = $_POST['p_bueno'];
		$stP_MUYBUENO = $_POST['p_muybueno'];
		$stP_EXCELENTE= $_POST['p_excelente'];

		$oRiesgo = new clsRiesgo();
		$oRiesgo->clearErrores();

		$oRiesgo->setC_bueno    ($stC_BUENO);
		$oRiesgo->setC_muybueno ($stC_MUYBUENO);
		$oRiesgo->setC_excelente($stC_EXCELENTE);
		$oRiesgo->setA_bueno    ($stA_BUENO);
		$oRiesgo->setA_muybueno ($stA_MUYBUENO);
		$oRiesgo->setA_excelente($stA_EXCELENTE);
		$oRiesgo->setP_bueno    ($stP_BUENO);
		$oRiesgo->setP_muybueno ($stP_MUYBUENO);
		$oRiesgo->setP_excelente($stP_EXCELENTE);
		$oRiesgo->setUsuario    ($_SESSION['_usuActivo']);

		if (!$oRiesgo->hasErrores() && $oRiesgo->Registrar()){
			redireccionar(getWeb('RIE').'rie.listar.php?mensaje=ok');
		}
		$stERROR = $oRiesgo->getErrores();
	}
	$oSmarty->assign('stERROR'      , $stERROR);
	$oSmarty->assign('stC_BUENO'    , $stC_BUENO);
	$oSmarty->assign('stC_MUYBUENO' , $stC_MUYBUENO);
	$oSmarty->assign('stC_EXCELENTE', $stC_EXCELENTE);
	$oSmarty->assign('stA_BUENO'    , $stA_BUENO);
	$oSmarty->assign('stA_MUYBUENO' , $stA_MUYBUENO);
	$oSmarty->assign('stA_EXCELENTE', $stA_EXCELENTE);
	$oSmarty->assign('stP_BUENO'    , $stP_BUENO);
	$oSmarty->assign('stP_MUYBUENO' , $stP_MUYBUENO);
	$oSmarty->assign('stP_EXCELENTE', $stP_EXCELENTE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Registrar rango de compra');
	$oSmarty->assign('stBTN_ACTION', 'Registrar');

	$oSmarty->display('rie.registrar.tpl.html');
?>