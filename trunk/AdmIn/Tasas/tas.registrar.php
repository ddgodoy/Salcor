<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';

	$stBUENA    = 0;
	$stMBUENA   = 0;
	$stEXCELENTE= 0;
	$stENTRADA  = 0;
	$stSALIDA   = 0;
	$stERROR    = '';

	if (isset($_POST['btn_accion'])){
		require_once getLocal('TAS').'class.tasas.inc.php';

		$stBUENA    = $_POST['buena'];
		$stMBUENA   = $_POST['mbuena'];
		$stEXCELENTE= $_POST['excelente'];
		$stENTRADA  = $_POST['entrada'];
		$stSALIDA   = $_POST['salida'];

		$oTasa = new clsTasas();
		$oTasa->clearErrores();

		$oTasa->setBuena    ($stBUENA);
		$oTasa->setMBuena   ($stMBUENA);
		$oTasa->setExcelente($stEXCELENTE);
		$oTasa->setEntrada  ($stENTRADA);
		$oTasa->setSalida   ($stSALIDA);
		$oTasa->setUsuario  ($_SESSION['_usuActivo']);

		if (!$oTasa->hasErrores() && $oTasa->Registrar()){
			/*------------------------------------------------------*/
			$oTasa->setArrayTasas();
			$oMyDB->Command("UPDATE tasas SET tasTBDBuena = ".$oTasa->TasaBuena['D'].", ".
						    "tasTBDMBuena = ".$oTasa->TasaMBuena['D'].", ".
						    "tasTBDExcelente = ".$oTasa->TasaExcelente['D']." ".
						    "WHERE tasId = $oTasa->Id;");
			/*------------------------------------------------------*/
			redireccionar(getWeb('TAS').'tas.listar.php?mensaje=ok');
		}
		$stERROR = $oTasa->getErrores();
	}
	$oSmarty->assign('stERROR'    , $stERROR);
	$oSmarty->assign('stBUENA'    , $stBUENA);
	$oSmarty->assign('stMBUENA'   , $stMBUENA);
	$oSmarty->assign('stEXCELENTE', $stEXCELENTE);
	$oSmarty->assign('stENTRADA'  , $stENTRADA);
	$oSmarty->assign('stSALIDA'   , $stSALIDA);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Registrar rentabilidad esperada');
	$oSmarty->assign('stBTN_ACTION', 'Registrar');

	$oSmarty->display('tas.registrar.tpl.html');
?>