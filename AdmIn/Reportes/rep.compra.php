<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';
	require_once getLocal('ADMIN').'arr_multisort.class.php';
	
	$xUsuActivo = $_SESSION['_usuActivo'];	
//
	$stSELECT = 'valSigla';
	$stSTEXT  = '';
	$stSFILT  = '';
	$sSectors = '';
	$aSectors = empty($_POST['sectores'])?array():$_POST['sectores'];
	$sFiltros = empty($_REQUEST['filtros'])?redireccionar('rep.inicio.php'):$_REQUEST['filtros'];

	if (!empty($_POST['sectores'])){
		foreach ($aSectors as $sector){
			$sSectors .= "$sector,";
		}
		$sSectors = substr($sSectors, 0, -1);
	} elseif (!empty($_GET['sectores'])) {
		$sSectors = $_GET['sectores'];
	}
	$filSects = !empty($sSectors) ? "AND valSector IN ($sSectors) " : '';

//busqueda
	if(!empty($_REQUEST['t'])){
		$stSTEXT = $_REQUEST['t'];
		$stSELECT= $_REQUEST['h'];
		$stSFILT.= " AND $stSELECT LIKE '$stSTEXT%' ";
	}

	$aFiltros = explode('_', $sFiltros);
	if (empty($aFiltros)){
		redireccionar('rep.inicio.php');
	}
	$sQUERY = "SELECT valId, ".
						"valSigla, ".
						"valLiquidez, ".
						"valUltima, ".
						"valTabla, ".
						"valVariacion, ".
						"valRendim, "   .
						"valDivAccion, "   .
						"valCapitalizacion, ".
						"valPG, "       .
						"valGanAccion, ".
						"cmpFecha, " .
					  "cmpPrecio ".
						"FROM compras ".
						"LEFT JOIN valores ON cmpValor = valId ".
						"LEFT JOIN especies ON valEspecie = espId ".
						"LEFT JOIN paises ON valPais = paiId ".
						"LEFT JOIN sectores ON valSector = secId ".
						"LEFT JOIN monedas ON valMoneda = monId ".
						"WHERE valUsuario = $xUsuActivo ".
						"AND cmpEstado = 'A' ".
						$filSects . $stSFILT.
						"ORDER BY valSigla;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'val');
		$oSmarty->assign ('stMESSAGE', 'No hay resultados para este reporte.');
		$oSmarty->assign ('stLINKS'  , array(array('desc'=>'<b>Volver a la configuraci&oacute;n</b>', 'link'=>'rep.inicio.php')));
		$oSmarty->assign ('stTITLE'  , 'Reportes');
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
//datos tabla tasas
	$aTasas = array();
	$rTasas = $oMyDB->Query("SELECT * FROM tasas WHERE tasUsuario = $xUsuActivo;");
	if ($rTasas){
		$dTasas = mysql_fetch_assoc($rTasas);

		$aTasas['entrada']  = $dTasas['tasEntrada'];
		$aTasas['salida']   = $dTasas['tasSalida'];
		$aTasas['buena']    = $dTasas['tasTBDBuena'];
		$aTasas['muybuena'] = $dTasas['tasTBDMBuena'];
		$aTasas['excelente']= $dTasas['tasTBDExcelente'];
	}
//resultados
	$xc = 0;
	while ($fila = mysql_fetch_assoc($result)){
		$aAuxRangos = getCalculoRangos($fila['valTabla'], $fila['valVariacion'], $aRiesgo, 0);

		$aValores[$xc]['Id'] = $fila['valId'];
		$aValores[$xc]['Sigla']   = $oMyDB->forShow($fila['valSigla']);
		$aValores[$xc]['Ultima']  = $fila['valUltima']?$fila['valUltima']:0;
		$aValores[$xc]['Liquidez']= $fila['valLiquidez']?$fila['valLiquidez']:0;
		$aValores[$xc]['RangoCB'] = $aAuxRangos['CB'];
		$aValores[$xc]['RangoCMB']= $aAuxRangos['CMB'];
		$aValores[$xc]['RangoCE'] = $aAuxRangos['CE'];
		$aValores[$xc]['Division']= $fila['valUltima']>0 ? $aAuxRangos['PROM']/$fila['valUltima'] : 0;

		$aValores[$xc]['Divaccion']= $fila['valDivAccion']?str_replace(',','.',$fila['valDivAccion']):'--';
		$aValores[$xc]['Ganaccion']= $fila['valGanAccion']?str_replace(',','.',$fila['valGanAccion']):'--';
		$aValores[$xc]['PG']       = $fila['valPG']?str_replace(',','.',$fila['valPG']):'--';
		$aValores[$xc]['Rendim']   = $fila['valRendim']?str_replace(',','.',$fila['valRendim']):'--';
		$aValores[$xc]['Capitalizacion'] = $fila['valCapitalizacion']?str_replace(',','.',$fila['valCapitalizacion']):'--';

		$aValores[$xc]['FechaCmp'] = $oMyDB->convertDate($fila['cmpFecha'],'d/m/y');
		$aValores[$xc]['PrecioCmp'] = $fila['cmpPrecio'];
		$aValores[$xc]['Edad'] = getEdadAccion($fila['cmpFecha']);
		$aValores[$xc]['Excelente']= 0;
		$aValores[$xc]['MBuena'] = 0;
		$aValores[$xc]['Buena'] = 0;
		$aValores[$xc]['Mala'] = 0;

		$xEdad = $aValores[$xc]['Edad'] > 0 ? $aValores[$xc]['Edad'] : 1;
		$aDatosTemp = getDatosTemp($fila['valUltima'], $fila['cmpPrecio'], $aTasas, $xEdad);

		$hache = $aDatosTemp['Rangos']['E'] * $xEdad;
		if ($aDatosTemp['Zeta'] >= $hache){
			$aValores[$xc]['Excelente'] = $hache;
		}
		$ge = $aDatosTemp['Rangos']['MB'] * $xEdad;

		if ($aDatosTemp['Zeta'] >= $ge && $aDatosTemp['Zeta'] < $hache){
			$aValores[$xc]['MBuena'] = $ge;
		}
		$efe = $aDatosTemp['Rangos']['B'] * $xEdad;
		
		if ($aDatosTemp['Zeta'] >= $efe && $aDatosTemp['Zeta'] < $ge){
			$aValores[$xc]['Buena'] = $efe;
		}
		if ($aDatosTemp['Zeta'] < $efe){
			$aValores[$xc]['Mala'] = $aDatosTemp['Zeta'];
		}
		$xc++;
	}
// ordenamiento---------------------------------------------------------------------
	$stSNT = 'ASC';
	$srt = new arr_multisort();
	$srt->setArray($aValores);

	$aAuxOrdn = array(SRT_ASC, SRT_DESC);
	//
	if (!empty($_REQUEST['orden'])){
		if ($_REQUEST['sentido']=='ASC'){
			$stSNT = 'DESC'; $stFLY = 1;
		} else {
			$stSNT = 'ASC'; $stFLY = 0;
		}
		$srt->addColumn($_REQUEST['orden'], $aAuxOrdn[$stFLY]);
	} else {	
		$aIndices = array(1=>'Sigla', 'RangoCB', 'RangoCMB', 
												 'RangoCE', 'Liquidez', 'Ultima', 
												 'Division', 'Mala', 'Buena', 'MBuena',
												 'Excelente', 'FechaCmp', 'PrecioCmp', 
												 'Edad', 'Divaccion', 'Ganaccion', 'PG',
												 'Rendim', 'Capitalizacion');
		for ($i=1; $i<count($aFiltros); $i++){
			$xOrd = explode('|', $aFiltros[$i]);
			$srt->addColumn($aIndices[$xOrd[0]], $aAuxOrdn[$xOrd[1]]);
		}
	}
  $aValores = $srt->sort();
//----------------------------------------------------------------------------------
	$oSmarty->assign_by_ref('stVALORES', $aValores);
	$oSmarty->assign('stSTEXT'  , $stSTEXT);
	$oSmarty->assign('stSELECT' , $stSELECT);
	$oSmarty->assign('stFILTROS', $sFiltros);
	$oSmarty->assign('stSECTORS', $sSectors);
	$oSmarty->assign('stSNT'    , $stSNT);
	$oSmarty->assign('stTITLE'  , 'Reporte An&aacute;lisis de Compra');

	$oSmarty->display('rep.compra.tpl.html');
?>