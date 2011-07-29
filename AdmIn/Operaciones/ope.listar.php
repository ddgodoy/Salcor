<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';
	require_once getLocal('ADMIN').'special_functions.php';

	$xUsuActivo = $_SESSION['_usuActivo'];

	$aOrder = array('especie' =>'espCodigo',
									'sigla'   =>'valSigla',
									'moneda'  =>'monCodigo',
									'mercado' =>'bolCodigo',
									'fcompra' =>'venFCompra',
									'pcompra' =>'venPCompra',
									'fecha'   =>'venFecha',
									'precio'  =>'venPrecio');
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
						"valSigla, "  .
						"venId, "     .
						"venFecha, "  .
						"venPrecio, " .
						"venFCompra, ".
						"venPCompra, ".
						"espCodigo, " .
						"monCodigo, " .
						"bolCodigo "  .
						"FROM ventas ".
						"LEFT JOIN bolsas ON venBolsa = bolId ".
						"LEFT JOIN valores ON venValor = valId ".
						"LEFT JOIN especies ON valEspecie = espId ".
						"LEFT JOIN paises ON valPais = paiId ".
						"LEFT JOIN sectores ON valSector = secId ".
						"LEFT JOIN monedas ON valMoneda = monId ".
						"WHERE valUsuario = $xUsuActivo $stSFILT ".
			  		"ORDER BY $aOrder[$stORD] $stSNT;";
	$result = $oMyDB->Query($sQUERY);

	if (!$result){
		$oSmarty->assign ('stPREFIJO_ACCION', 'ope');
		$oSmarty->assign ('stMESSAGE', 'No hay operaciones registradas.');
		$oSmarty->assign ('stTITLE'  , 'An&aacute;lisis de valores');
		$oSmarty->display('information.tpl.html');
		exit();
	}
// resultados
	$xc = 0;
	while ($fila = mysql_fetch_assoc($result)){
		$aValores[$xc]['Id'] = $fila['valId'];
		$aValores[$xc]['VentaId'] = $fila['venId'];
		$aValores[$xc]['Especie'] = $fila['espCodigo'];
		$aValores[$xc]['Sigla']   = $fila['valSigla'];
		$aValores[$xc]['Moneda']  = $fila['monCodigo'];
		$aValores[$xc]['FCompra'] = $oMyDB->convertDate($fila['venFCompra']);
		$aValores[$xc]['PCompra'] = $fila['venPCompra'];
		$aValores[$xc]['Fecha']   = $oMyDB->convertDate($fila['venFecha']);
		$aValores[$xc]['Precio']  = $fila['venPrecio'];
		$aValores[$xc]['Bruta']   = (($fila['venPrecio'] / $fila['venPCompra']) - 1) * 100;
		$aValores[$xc]['Dias']    = getEdadAccion($fila['venFCompra'], $fila['venFecha']);
		$aValores[$xc]['bolsa']   = $fila['bolCodigo'];

		$xDias = $aValores[$xc]['Dias'] > 0 ? $aValores[$xc]['Dias']: 1;
		$aValores[$xc]['Diaria'] = $aValores[$xc]['Bruta'] / $xDias;
		$aValores[$xc]['Mensual']= $aValores[$xc]['Diaria'] * 30;
		$aValores[$xc]['Anual']  = $aValores[$xc]['Diaria'] * 365;
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
	$oSmarty->assign('stTITLE' , 'An&aacute;lisis de valores');

	$oSmarty->display('ope.listar.tpl.html');
?>