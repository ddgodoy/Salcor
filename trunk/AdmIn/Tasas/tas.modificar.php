<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('TAS').'class.tasas.inc.php';
	
	$oTasa = new clsTasas();
	$oTasa->clearErrores();
	
	$stBUENA    = 0;
	$stMBUENA   = 0;
	$stEXCELENTE= 0;
	$stENTRADA  = 0;
	$stSALIDA   = 0;
	$stERROR    = '';

	if (isset($_POST['btn_accion'])){
		$stBUENA    = $_POST['buena'];
		$stMBUENA   = $_POST['mbuena'];
		$stEXCELENTE= $_POST['excelente'];
		$stENTRADA  = $_POST['entrada'];
		$stSALIDA   = $_POST['salida'];

		if ($oTasa->findId($_SESSION['_usuActivo'])){
			$oTasa->setBuena    ($stBUENA);
			$oTasa->setMBuena   ($stMBUENA);
			$oTasa->setExcelente($stEXCELENTE);
			$oTasa->setEntrada  ($stENTRADA);
			$oTasa->setSalida   ($stSALIDA);
			$oTasa->setUsuario  ($_SESSION['_usuActivo']);
	
			if (!$oTasa->hasErrores() && $oTasa->Modificar()){
				/*------------------------------------------------------*/
				$oTasa->setArrayTasas();
				$oMyDB->Command("UPDATE tasas SET tasTBDBuena = ".$oTasa->TasaBuena['D'].", ".
							    "tasTBDMBuena = ".$oTasa->TasaMBuena['D'].", ".
							    "tasTBDExcelente = ".$oTasa->TasaExcelente['D']." ".
							    "WHERE tasId = $oTasa->Id;");
				/*------------------------------------------------------*/
				redireccionar(getWeb('TAS').'tas.listar.php?mensaje=ok');
			}
		}
		$stERROR = $oTasa->getErrores();
	} else {
		if (!$oTasa->findId($_SESSION['_usuActivo'])){
			$oSmarty->assign ('stTITLE'  , 'Modificar rentabilidad esperada');
			$oSmarty->assign ('stMESSAGE', $oTasa->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$oTasa->setArrayTasas();

		$stBUENA    = $oTasa->getBuena();
		$stMBUENA   = $oTasa->getMBuena();
		$stEXCELENTE= $oTasa->getExcelente();
		$stENTRADA  = $oTasa->getEntrada();
		$stSALIDA   = $oTasa->getSalida();
		$arTB_B  = $oTasa->TasaBuena;
		$arTB_MB = $oTasa->TasaMBuena;
		$arTB_E  = $oTasa->TasaExcelente;
	}
	$oSmarty->assign('stERROR'    , $stERROR);
	$oSmarty->assign('stBUENA'    , $stBUENA);
	$oSmarty->assign('stMBUENA'   , $stMBUENA);
	$oSmarty->assign('stEXCELENTE', $stEXCELENTE);
	$oSmarty->assign('stENTRADA'  , $stENTRADA);
	$oSmarty->assign('stSALIDA'   , $stSALIDA);
	$oSmarty->assign('stTBA_B'    , $arTB_B['A']);
	$oSmarty->assign('stTBM_B'    , $arTB_B['M']);
	$oSmarty->assign('stTBD_B'    , $arTB_B['D']);
	$oSmarty->assign('stTBA_MB'   , $arTB_MB['A']);
	$oSmarty->assign('stTBM_MB'   , $arTB_MB['M']);
	$oSmarty->assign('stTBD_MB'   , $arTB_MB['D']);
	$oSmarty->assign('stTBA_E'    , $arTB_E['A']);
	$oSmarty->assign('stTBM_E'    , $arTB_E['M']);
	$oSmarty->assign('stTBD_E'    , $arTB_E['D']);
/*------------------------------------------------------------------*/
	$oSmarty->assign('stVER_MENSAJE_UPDATE', !empty($_GET['mensaje'])?TRUE:FALSE);
/*------------------------------------------------------------------*/
	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar rentabilidad espearada');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('tas.registrar.tpl.html');
?>