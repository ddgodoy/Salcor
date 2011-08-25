<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';

	$xUsuActivo = $_SESSION['_usuActivo'];	

	$aOrder = array('sigla'   =>'valSigla',
									'moneda'  =>'monCodigo',
									'precio'  =>'valUltima',
									'liquidez'=>'valLiquidez',
									'tabla'   =>'valTabla',
									'empresa' =>'valNombre',
									'sector'  =>'secCodigo',
									'pais'    =>'paiCodigo');
	$stSELECT= 'valSigla';
	$stSTEXT = '';
	$stSFILT = '';
	$stFLYSNT= false;
	$stSNT = 'ASC';
	$stORD = !empty($_REQUEST['orden']) ? $_REQUEST['orden'] : 'sigla';

//busqueda
	if(!empty($_REQUEST['stext'])){
		$stSTEXT = $_REQUEST['stext'];
		$stSELECT= $_REQUEST['sselect'];
		$stSFILT.= " AND $stSELECT LIKE '$stSTEXT%' ";
	}
//ordenamiento
	if (!empty($_REQUEST['sentido'])){
		$stSNT = 'ASC'; $stFLYSNT = false; if ($_REQUEST['sentido']=='ASC'){$stSNT = 'DESC'; $stFLYSNT = true;}
	}
	$sQUERY = "SELECT valId, ".
						"valTabla, ".
						"valVariacion, ".
						"valUltima, ".
						"valSigla, ".
						"valLiquidez, ".
						"valNombre, ".
						"valIsValid, "  .
						"monCodigo, ".
						"secCodigo, ".
						"paiCodigo ".
						"FROM valores ".
						"LEFT JOIN especies ON valEspecie = espId ".
						"LEFT JOIN paises ON valPais = paiId ".
						"LEFT JOIN sectores ON valSector = secId ".
						"LEFT JOIN monedas ON valMoneda = monId ".
						"WHERE valUsuario = $xUsuActivo ".
						"AND valEstado = 'A' $stSFILT ".
						"ORDER BY $aOrder[$stORD] $stSNT;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'cmp');
		$oSmarty->assign ('stMESSAGE', 'No hay valores activos.');
		$oSmarty->assign ('stTITLE'  , 'Analisis de valores');
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
		$aValores[$xc]['Precio']  = $fila['valUltima'];
		$aValores[$xc]['Sigla']   = $oMyDB->forShow($fila['valSigla']);
		$aValores[$xc]['Moneda']  = $oMyDB->forShow($fila['monCodigo']);
		$aValores[$xc]['Liquidez']= $fila['valLiquidez'];
		$aValores[$xc]['Empresa'] = $oMyDB->forShow($fila['valNombre']);
		$aValores[$xc]['Sector']  = $oMyDB->forShow($fila['secCodigo']);
		$aValores[$xc]['Pais']    = $oMyDB->forShow($fila['paiCodigo']);
		$aValores[$xc]['Tabla']   = $fila['valTabla'];
		$aValores[$xc]['esValido']= $fila['valIsValid'];
		$aValores[$xc]['Promedio']= $aAuxRangos['PROM'];
		$aValores[$xc]['Division']= 0;
		$aValores[$xc]['RangoCE'] = 0;
		$aValores[$xc]['RangoCMB']= 0;
		$aValores[$xc]['RangoCB'] = 0;

		if ($fila['valUltima'] == $aAuxRangos['CE'] || $fila['valUltima'] < $aAuxRangos['CE']){
			$aValores[$xc]['RangoCE'] = $aAuxRangos['CE'];
		} elseif ($fila['valUltima'] > $aAuxRangos['CE'] && $fila['valUltima'] <= $aAuxRangos['CMB']){
			$aValores[$xc]['RangoCMB']= $aAuxRangos['CMB'];
		} elseif ($fila['valUltima'] > $aAuxRangos['CMB'] && $fila['valUltima'] <= $aAuxRangos['CB']){
			$aValores[$xc]['RangoCB'] = $aAuxRangos['CB'];
		}		
		if ($aValores[$xc]['Precio'] > 0){
			$aValores[$xc]['Division'] = $aValores[$xc]['Promedio'] / $aValores[$xc]['Precio'];
		}
		$xc++;
	}
	//---------------------------------------------------------------------------------------------------------
	if (!empty($_REQUEST['fly_ord'])){
		$xCampo = $_REQUEST['fly_ord'];

		foreach($aValores as $res){
			$sortAux[] = $res[$xCampo];
		}
		$stFLYSNT ? array_multisort($sortAux,SORT_ASC,$aValores) : array_multisort($sortAux,SORT_DESC,$aValores);
	}
	//---------------------------------------------------------------------------------------------------------
	$oSmarty->assign_by_ref('stVALORES', $aValores);
	$oSmarty->assign('stSNT'   , $stSNT);
	$oSmarty->assign('stFLYSNT', $stFLYSNT);
	$oSmarty->assign('stSTEXT' , $stSTEXT);
	$oSmarty->assign('stSELECT', $stSELECT);
	$oSmarty->assign('stBOLSA' , $aBolsas);
	$oSmarty->assign('stFECHACORTA_HOY', date('d/m/Y'));
	$oSmarty->assign('stTITLE', 'An&aacute;lisis de valores');

	$oSmarty->display('cmp.listar.tpl.html');
?>