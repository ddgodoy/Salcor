<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$oValor = new clsValores();

	$stSIGLA    = '';
	$stRANGO_CB = 0;
	$stRANGO_CMB= 0;
	$stRANGO_CE = 0;
	$stESTADO   = 'A';
	$stRECOMENDADO = '';
	$stCOMENTARIO = '';
	$stESPECIE  = llenarSelect($oValor->getEspecies());
	$stSECTOR   = llenarSelect($oValor->getSectores());
	$stMONEDA   = llenarSelect($oValor->getMonedas());
	$stPAIS     = llenarSelect($oValor->getPaises());
	$stBOLSA    = llenarSelect($oValor->getBolsas());
	$stTABLA    = llenarSelect($oValor->getTablas());
	$stERROR    = '';
/*-----------------------------------------------------------------------*/
	$arPorcentajes = $oValor->getPorcentajes('C', $_SESSION['_usuActivo']);

	$stRIESGO_TB = $arPorcentajes['b'];
	$stRIESGO_TMB= $arPorcentajes['mb'];
	$stRIESGO_TE = $arPorcentajes['e'];
/*-----------------------------------------------------------------------*/
	if (isset($_POST['btn_accion'])){
		$stSIGLA    = $_POST['sigla'];
		$stRANGO_CB = $_POST['rango_cb'];
		$stRANGO_CMB= $_POST['rango_cmb'];
		$stRANGO_CE = $_POST['rango_ce'];
		$stESTADO   = $_POST['estado'];
		$stESPECIE  = $_POST['especie'];
		$stSECTOR   = $_POST['sector'];
		$stMONEDA   = $_POST['moneda'];
		$stPAIS     = $_POST['pais'];
		$stBOLSA    = $_POST['bolsa'];
		$stTABLA    = $_POST['tabla'];
		$stCOMENTARIO = $_POST['comentario'];
		$stRECOMENDADO = isset($_POST['recomendado'])?'S':'N';

		$oValor->clearErrores();

		$oValor->setSigla   ($stSIGLA);
		$oValor->setEspecie ($stESPECIE);
		$oValor->setSector  ($stSECTOR);
		$oValor->setMoneda  ($stMONEDA);
		$oValor->setPais    ($stPAIS);
		$oValor->setBolsa   ($stBOLSA);
		$oValor->setTabla   ($stTABLA);
		$oValor->setEstado  ($stESTADO);
		$oValor->setRangoCB ($stRANGO_CB);
		$oValor->setRangoCMB($stRANGO_CMB);
		$oValor->setRangoCE ($stRANGO_CE);
		$oValor->setUsuario ($_SESSION['_usuActivo']);
		$oValor->setComentario($stCOMENTARIO);
		$oValor->setRecomendado($stRECOMENDADO);

		if (!$oValor->hasErrores() && $oValor->Registrar()){
			redireccionar(getWeb('VAL').'val.listar.php');
		}
		$stESPECIE= llenarSelect($oValor->getEspecies());
		$stSECTOR = llenarSelect($oValor->getSectores());
		$stMONEDA = llenarSelect($oValor->getMonedas());
		$stPAIS   = llenarSelect($oValor->getPaises());
		$stBOLSA  = llenarSelect($oValor->getBolsas());
		$stTABLA  = llenarSelect($oValor->getTablas());
		$stRECOMENDADO = isset($_POST['recomendado'])?'CHECKED':'';
		$stERROR  = $oValor->getErrores();
	}
	$oSmarty->assign('stERROR'    , $stERROR);
	$oSmarty->assign('stSIGLA'    , $stSIGLA);
	$oSmarty->assign('stESPECIE'  , $stESPECIE);
	$oSmarty->assign('stSECTOR'   , $stSECTOR);
	$oSmarty->assign('stMONEDA'   , $stMONEDA);
	$oSmarty->assign('stPAIS'     , $stPAIS);
	$oSmarty->assign('stBOLSA'    , $stBOLSA);
	$oSmarty->assign('stTABLA'    , $stTABLA);
	$oSmarty->assign('stRANGO_CB' , $stRANGO_CB);
	$oSmarty->assign('stRANGO_CMB', $stRANGO_CMB);
	$oSmarty->assign('stRANGO_CE' , $stRANGO_CE);
	$oSmarty->assign('stESTADO'   , $stESTADO);
	$oSmarty->assign('stRECOMENDADO', $stRECOMENDADO);
	$oSmarty->assign('stCOMENTARIO', $stCOMENTARIO);
	$oSmarty->assign('stRIESGO_TB' , $stRIESGO_TB);
	$oSmarty->assign('stRIESGO_TMB', $stRIESGO_TMB);
	$oSmarty->assign('stRIESGO_TE' , $stRIESGO_TE);
	$oSmarty->assign('stRUTA_VOLVER', getWeb('VAL').'val.listar.php');
	$oSmarty->assign('stHIDDEN_USUARIO', $_SESSION['_usuActivo']);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo valor');
	$oSmarty->assign('stBTN_ACTION', 'Nuevo');

	$oSmarty->display('val.registrar.tpl.html');
?>