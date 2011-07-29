<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$stID = 0;
	$stSIGLA     = '';
	$stRANGO_CB  = 0;
	$stRANGO_CMB = 0;
	$stRANGO_CE  = 0;
	$stESTADO    = '';
	$stESPECIE   = '';
	$stSECTOR    = '';
	$stMONEDA    = '';
	$stPAIS      = '';
	$stBOLSA     = '';
	$stTABLA     = '';
	$stERROR     = '';
	$stCOMENTARIO= '';
	$stRECOMENDADO = '';
	$stRIESGO_TB = '';
	$stRIESGO_TMB= '';
	$stRIESGO_TE = '';
	$stRUTA_VOLVER = '';

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
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
		$stRUTA_VOLVER = $_POST['ruta_volver'];
		$stRECOMENDADO = isset($_POST['recomendado'])?'S':'N';

		$oValor = new clsValores();
		$oValor->clearErrores();

		if ($oValor->findId($stID)){
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

			if (!$oValor->hasErrores() && $oValor->Modificar()){
				redireccionar($stRUTA_VOLVER);
			}
		}
		$stESPECIE= llenarSelect($oValor->getEspecies(), $oValor->Especie);
		$stSECTOR = llenarSelect($oValor->getSectores(), $oValor->Sector);
		$stMONEDA = llenarSelect($oValor->getMonedas() , $oValor->Moneda);
		$stPAIS   = llenarSelect($oValor->getPaises()  , $oValor->Pais);
		$stBOLSA  = llenarSelect($oValor->getBolsas()  , $oValor->Bolsa);
		$stTABLA  = llenarSelect($oValor->getTablas()  , $oValor->Tabla);
		$stRECOMENDADO = isset($_POST['recomendado'])?'CHECKED':'';
		$stERROR  = $oValor->getErrores();
		/*-----------------------------------------------------------------------*/
		$arPorcentajes = $oValor->getPorcentajes($oValor->Tabla, $_SESSION['_usuActivo']);

		$stRIESGO_TB = $arPorcentajes['b'];
		$stRIESGO_TMB= $arPorcentajes['mb'];
		$stRIESGO_TE = $arPorcentajes['e'];
		/*-----------------------------------------------------------------------*/
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oValor = new clsValores();
		$oValor->clearErrores();

		if (!$oValor->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar un valor');
			$oSmarty->assign ('stMESSAGE', $oValor->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stSIGLA    = $oValor->getSigla();
		$stRANGO_CB = $oValor->getRangoCB();
		$stRANGO_CMB= $oValor->getRangoCMB();
		$stRANGO_CE = $oValor->getRangoCE();
		$stESTADO   = $oValor->getEstado();
		$stCOMENTARIO = $oValor->editComentario();
		$stRECOMENDADO = $oValor->getRecomendado()=='S'?'CHECKED':'';
		$stESPECIE  = llenarSelect($oValor->getEspecies(), $oValor->Especie);
		$stSECTOR   = llenarSelect($oValor->getSectores(), $oValor->Sector);
		$stMONEDA   = llenarSelect($oValor->getMonedas() , $oValor->Moneda);
		$stPAIS     = llenarSelect($oValor->getPaises()  , $oValor->Pais);
		$stBOLSA    = llenarSelect($oValor->getBolsas()  , $oValor->Bolsa);
		$stTABLA    = llenarSelect($oValor->getTablas()  , $oValor->Tabla);
		$stRUTA_VOLVER = empty($_GET['ruta_volver'])?getWeb('VAL').'val.listar.php':$_GET['ruta_volver'];
	/*-----------------------------------------------------------------------*/
		$arPorcentajes = $oValor->getPorcentajes($oValor->Tabla, $_SESSION['_usuActivo']);

		$stRIESGO_TB = $arPorcentajes['b'];
		$stRIESGO_TMB= $arPorcentajes['mb'];
		$stRIESGO_TE = $arPorcentajes['e'];
	/*-----------------------------------------------------------------------*/
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar un valor');
		$oSmarty->assign ('stMESSAGE', 'No puede ingresar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
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
	$oSmarty->assign('stRUTA_VOLVER', $stRUTA_VOLVER);
	$oSmarty->assign('stHIDDEN_USUARIO', $_SESSION['_usuActivo']);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar valor');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
	
	$oSmarty->display('val.registrar.tpl.html');
?>