<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';

	$xUsuActivo = $_SESSION['_usuActivo'];

	$aOrder = array('sigla'   =>'valSigla',
									'moneda'  =>'monCodigo',
									'mercado' =>'bolCodigo',
									'fcompra' =>'cmpFecha',
									'pcompra' =>'cmpPrecio',
									'precio'  =>'valUltima');
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
					  "valSigla, " .
					  "valUltima, ".
					  "cmpId, "    .
					  "cmpFecha, " .
					  "cmpPrecio, ".
					  "monCodigo, ".
					  "bolId, "    .
					  "bolCodigo " .
					  "FROM compras ".
					  "LEFT JOIN bolsas ON cmpBolsa = bolId ".
					  "LEFT JOIN valores ON cmpValor = valId ".
					  "LEFT JOIN especies ON valEspecie = espId ".
					  "LEFT JOIN paises ON valPais = paiId ".
					  "LEFT JOIN sectores ON valSector = secId ".
					  "LEFT JOIN monedas ON valMoneda = monId ".
					  "WHERE cmpEstado = 'A' $stSFILT ".
			  	  "AND valUsuario = $xUsuActivo ".
			  	  "ORDER BY $aOrder[$stORD] $stSNT;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'ren');
		$oSmarty->assign ('stMESSAGE', 'No hay compras registradas.');
		$oSmarty->assign ('stTITLE'  , 'An&aacute;lisis de valores');
		$oSmarty->display('information.tpl.html');
		exit();
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
		$aValores[$xc]['Id']  = $fila['valId'].'_'.$fila['cmpId'];
		$aValores[$xc]['Cod'] = $fila['valId'];
		$aValores[$xc]['CompraId'] = $fila['cmpId'];
		$aValores[$xc]['Sigla']    = $fila['valSigla'];
		$aValores[$xc]['Moneda']   = $fila['monCodigo'];
		$aValores[$xc]['FechaCmp'] = $oMyDB->convertDate($fila['cmpFecha']);
		$aValores[$xc]['PrecioCmp']= $fila['cmpPrecio'];
		$aValores[$xc]['Ultima']   = $fila['valUltima'];
		$aValores[$xc]['id_bolsa'] = $fila['bolId'];
		$aValores[$xc]['nombre_bolsa'] = $fila['bolCodigo'];
		$aValores[$xc]['Excelente'] = 0;
		$aValores[$xc]['MBuena']   = 0;
		$aValores[$xc]['Buena']   = 0;
		$aValores[$xc]['Mala']   = 0;

		$aValores[$xc]['Edad'] = getEdadAccion($fila['cmpFecha']);
		$xEdad = $aValores[$xc]['Edad'] > 0 ? $aValores[$xc]['Edad'] : 1;

		$aDatosTemp = getDatosTemp($fila['valUltima'], $fila['cmpPrecio'], $aTasas, $xEdad);

		$aValores[$xc]['Utilidad'] = $aDatosTemp['Utilidad'];
		/**/
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
		/**/
		$aValores[$xc]['Diaria'] = $aValores[$xc]['Utilidad'] / $xEdad;
		$aValores[$xc]['Mensual']= $aValores[$xc]['Diaria'] * 30;
		$aValores[$xc]['Anual']  = $aValores[$xc]['Diaria'] * 365;
		$aValores[$xc]['Anual']  = $aValores[$xc]['Anual'] <= (-100)?(-100):$aValores[$xc]['Anual'];
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
	$oSmarty->assign('stFECHACORTA_HOY', date('d/m/Y'));
	$oSmarty->assign('stTITLE' , 'An&aacute;lisis de valores');
	$oSmarty->assign('stSNT'   , $stSNT);
	$oSmarty->assign('stFLYSNT', $stFLYSNT);
	$oSmarty->assign('stSTEXT' , $stSTEXT);
	$oSmarty->assign('stSELECT', $stSELECT);

	$oSmarty->display('ren.listar.tpl.html');
?>