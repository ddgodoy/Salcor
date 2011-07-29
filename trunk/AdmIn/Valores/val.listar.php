<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';
	
	$xUsuActivo = $_SESSION['_usuActivo'];	

	$aOrder = array('sigla'   =>'valSigla',
									'especie' =>'espCodigo',
									'moneda'  =>'monCodigo',
									'liquidez'=>'valLiquidez',
									'empresa' =>'valNombre',
									'sector'  =>'secCodigo',
									'pais'    =>'paiCodigo',
									'tabla'   =>'valTabla',
									'volumen' =>'valVolumen',
									'estado'  =>'valEstado');
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
						"valTabla, "    .
						"valVariacion, ".
						"valSigla, "    .
						"valLiquidez, " .
						"valNombre, "   .
						"valVolumen, "  .
						"valUltima, "   .
						"valEstado, "   .
						"espCodigo, "   .
						"monCodigo, "   .
						"secCodigo, "   .
						"paiCodigo "    .
						"FROM valores " .
						"LEFT JOIN especies ON valEspecie = espId ".
						"LEFT JOIN paises ON valPais = paiId ".
						"LEFT JOIN sectores ON valSector = secId ".
						"LEFT JOIN monedas ON valMoneda = monId ".
			  		"WHERE valUsuario = $xUsuActivo $stSFILT ".
			  		"ORDER BY $aOrder[$stORD] $stSNT;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'val');
		$oSmarty->assign ('stMESSAGE', 'No hay valores registrados.');
		$oSmarty->assign ('stLINKS'  , array(array('desc'=>'<b>Nuevo valor</b>', 'link'=>'val.registrar.php')));
		$oSmarty->assign ('stTITLE'  , 'Listado de valores');
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
// resultados
	$xc = 0;
	while ($fila = mysql_fetch_assoc($result)){
		$aAuxRangos = getCalculoRangos($fila['valTabla'], $fila['valVariacion'], $aRiesgo);

		$aValores[$xc]['Id'] = $fila['valId'];
		$aValores[$xc]['Ultima']  = $fila['valUltima'];
		$aValores[$xc]['Especie'] = $oMyDB->forShow($fila['espCodigo']);
		$aValores[$xc]['Sigla']   = $oMyDB->forShow($fila['valSigla']);
		$aValores[$xc]['Moneda']  = $oMyDB->forShow($fila['monCodigo']);
		$aValores[$xc]['Liquidez']= $fila['valLiquidez'];
		$aValores[$xc]['Empresa'] = $oMyDB->forShow($fila['valNombre']);
		$aValores[$xc]['Sector']  = $oMyDB->forShow($fila['secCodigo']);
		$aValores[$xc]['Pais']    = $oMyDB->forShow($fila['paiCodigo']);
		$aValores[$xc]['Tabla']   = $fila['valTabla'];
		$aValores[$xc]['RangoCB'] = $aAuxRangos['CB'];
		$aValores[$xc]['RangoCMB']= $aAuxRangos['CMB'];
		$aValores[$xc]['RangoCE'] = $aAuxRangos['CE'];
		$aValores[$xc]['Volumen'] = $fila['valVolumen'];
		$aValores[$xc]['Estado']  = $fila['valEstado']=='A'?'activa':'inactiva';
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
	$oSmarty->assign('stTITLE' , 'Listado de valores');
	$oSmarty->assign('stVALFECHADIA', date('d/m/Y'));

	$oSmarty->display('val.listar.tpl.html');
?>