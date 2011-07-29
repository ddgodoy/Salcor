<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';

	$xUsuActivo = $_SESSION['_usuActivo'];	

	$stSELECT= 'valSigla';
	$stSTEXT = '';
	$stSFILT = '';

//busqueda
	if(!empty($_REQUEST['stext'])){
		$stSTEXT = $_REQUEST['stext'];
		$stSELECT= $_REQUEST['sselect'];
		$stSFILT.= " AND $stSELECT LIKE '$stSTEXT%' ";
	}	
	$sQUERY = "SELECT valId, ".
						"valSigla, ".
						"valUltima, ".
						"valLiquidez, ".
						"valTabla, ".
						"valVariacion, ".
						"monCodigo, ".
						"bolCodigo, ".
						"espCodigo, ".
						"sugId, ".
						"sugSugirio, ".
						"sugFecAlta, ".
						"sugPrecioAlta, ".
						"sugFecSug, ".
						"sugPrecioSug ".
						"FROM sugeridos ".
						"LEFT JOIN valores ON sugValor = valId ".
						"LEFT JOIN especies ON valEspecie = espId ".
						"LEFT JOIN paises ON valPais = paiId ".
						"LEFT JOIN sectores ON valSector = secId ".
						"LEFT JOIN monedas ON valMoneda = monId ".
						"LEFT JOIN bolsas ON valBolsa = bolId ".
						"WHERE valUsuario = $xUsuActivo ".
						"AND valEstado = 'A' $stSFILT ".
						"ORDER BY valSigla;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'sug');
		$oSmarty->assign ('stMESSAGE', 'No hay valores sugeridos.');
		$oSmarty->assign ('stTITLE'  , 'An&aacute;lisis de valores sugeridos');
		$oSmarty->display('information.tpl.html');
		exit();
	}
//datos tabla riesgo
	$aRiesgo = array();
	$rRiesgo = $oMyDB->Query("SELECT * FROM riesgo WHERE rieUsuario = $xUsuActivo;");
	if ($rRiesgo){
		$dRiesgo = mysql_fetch_assoc($rRiesgo);

		$aRiesgo['C'] = array($dRiesgo['rieC_bueno'], $dRiesgo['rieC_muybueno'], $dRiesgo['rieC_excelente']);
		$aRiesgo['A'] = array($dRiesgo['rieA_bueno'], $dRiesgo['rieA_muybueno'], $dRiesgo['rieA_excelente']);
		$aRiesgo['P'] = array($dRiesgo['rieP_bueno'], $dRiesgo['rieP_muybueno'], $dRiesgo['rieP_excelente']);
	}
//datos bolsas
	$aBolsas = array();
	$rBolsas = $oMyDB->Query("SELECT bolId, bolCodigo FROM bolsas;");

	while ($rBolsas && $dBolsas = mysql_fetch_assoc($rBolsas)){
		$aBolsas[$dBolsas['bolId']]['Id'] = $dBolsas['bolId'];
		$aBolsas[$dBolsas['bolId']]['Nombre'] = $dBolsas['bolCodigo'];
	}
//resultados
	$xc = 0;
	while ($fila = mysql_fetch_assoc($result)){
		$aAuxRangos = getCalculoRangos($fila['valTabla'], $fila['valVariacion'], $aRiesgo, 0);

		$aValores[$xc]['Id'] = $fila['valId'];
		$aValores[$xc]['Id_sug']  = $fila['sugId'];
		$aValores[$xc]['Especie'] = $oMyDB->forShow($fila['espCodigo']);
		$aValores[$xc]['Sigla']   = $oMyDB->forShow($fila['valSigla']);
		$aValores[$xc]['Moneda']  = $oMyDB->forShow($fila['monCodigo']);
		$aValores[$xc]['Mercado'] = $oMyDB->forShow($fila['bolCodigo']);
		$aValores[$xc]['Liquidez']= $fila['valLiquidez'];
		$aValores[$xc]['Sugirio'] = $oMyDB->forShow($fila['sugSugirio']);
		$aValores[$xc]['FecAlta'] = $oMyDB->convertDate($fila['sugFecAlta'],'d/m/y');
		$aValores[$xc]['FecSug']  = $oMyDB->convertDate($fila['sugFecSug'],'d/m/y');
		$aValores[$xc]['PreAlta'] = $fila['sugPrecioAlta'];
		$aValores[$xc]['PreSug']  = $fila['sugPrecioSug'];
		$aValores[$xc]['Ultima']  = $fila['valUltima'];
		$aValores[$xc]['Promedio']= $aAuxRangos['PROM'];
		//
		$aValores[$xc]['EdadSug'] = getEdadAccion($fila['sugFecAlta'], $fila['sugFecSug']);
		$aValores[$xc]['EdadHoy'] = getEdadAccion($fila['sugFecAlta']);
		$aValores[$xc]['CotizSug']= 0;
		$aValores[$xc]['CotizAct']= 0;
		$aValores[$xc]['Cprom']   = 0;
		$aValores[$xc]['Calta']   = 0;
		$aValores[$xc]['Csug']    = 0;
		$aValores[$xc]['Crecer']  = 0;

		if ($fila['sugPrecioAlta'] > 0){
			$aValores[$xc]['CotizSug'] = $fila['sugPrecioSug'] / $fila['sugPrecioAlta'];
		}
		if ($aValores[$xc]['EdadSug'] > 0){
			$aValores[$xc]['CotizAct'] = ($aValores[$xc]['CotizSug'] / $aValores[$xc]['EdadSug']) * $aValores[$xc]['EdadHoy'];
		}
		if ($aValores[$xc]['Promedio'] > 0){
			$aValores[$xc]['Cprom'] = $aValores[$xc]['Ultima'] / $aValores[$xc]['Promedio'];
		}
		if ($aValores[$xc]['PreAlta'] > 0){
			$aValores[$xc]['Calta'] = $aValores[$xc]['Ultima'] / $aValores[$xc]['PreAlta'];
		}
		if ($aValores[$xc]['PreSug'] > 0){
			$aValores[$xc]['Csug'] = $aValores[$xc]['Ultima'] / $aValores[$xc]['PreSug'];
		}
		if ($aValores[$xc]['Ultima'] > 0){
			$aValores[$xc]['Crecer'] = ($aValores[$xc]['PreSug'] - $aValores[$xc]['Ultima']) / $aValores[$xc]['Ultima'];
		}
		//
		$xc++;
	}
	//---------------------------------------------------------------------------------------------------------
	$stSNT = 'ASC';
	if (!empty($_REQUEST['orden'])){
		if ($_REQUEST['sentido']=='ASC'){
			$stSNT = 'DESC'; $stFLY = false;
		} else {
			$stSNT = 'ASC'; $stFLY = true;
		}
		$xCampo = $_REQUEST['orden'];

		foreach($aValores as $res){
			$sortAux[] = $res[$xCampo];
		}
		$stFLY ? array_multisort($sortAux,SORT_ASC,$aValores) : array_multisort($sortAux,SORT_DESC,$aValores);
	}
	//---------------------------------------------------------------------------------------------------------
	$oSmarty->assign_by_ref('stVALORES', $aValores);
	$oSmarty->assign('stBOLSA' , $aBolsas);
	$oSmarty->assign('stSTEXT' , $stSTEXT);
	$oSmarty->assign('stSELECT', $stSELECT);
	$oSmarty->assign('stSNT'   , $stSNT);

	$oSmarty->assign('stFECHACORTA_HOY', date('d/m/Y'));
	$oSmarty->assign('stTITLE', 'An&aacute;lisis de valores sugeridos');

	$oSmarty->display('sug.listar.tpl.html');
?>