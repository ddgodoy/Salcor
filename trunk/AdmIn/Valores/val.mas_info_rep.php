<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
/*------------------------------------------------------------------------------------------------*/
	function cleanTabla($cadena){
		$cadenaKEY = '<a';
		$inicioValor = strpos($cadena, $cadenaKEY);

		if ($inicioValor !== false){
			$auxLimite = strpos($cadena, 'd>', $inicioValor) + 1;
			$fixLimite = $auxLimite - $inicioValor;

			return substr_replace($cadena,'</td',$inicioValor,$fixLimite);
		}
		return $cadena;
	}
	function getInfoEnCelda($haystack, $ini, $fin){
		$resultado = '';
		$inicioValor = strpos($haystack, $ini);
		if ($inicioValor !== false){
			$inicioValor += strlen($ini);

			$auxLimite = strpos($haystack, $fin, $inicioValor);
			$fixLimite = $auxLimite - $inicioValor;
			$resultado = strip_tags(substr($haystack, $inicioValor, $fixLimite));
		}
		return $resultado;
	}
/*------------------------------------------------------------------------------------------------*/
	if (empty($_REQUEST['Id'])){
		$oSmarty->assign ('stTITLE'  , 'Detalle del valor');
		$oSmarty->assign ('stMESSAGE', 'No puede entrar a esta página directamente.');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$stID = $_REQUEST['Id'];

	require_once getLocal('VAL').'class.valores.inc.php';

	$oValor = new clsValores();
	if (!$oValor->findId($stID)){
		$oSmarty->assign ('stTITLE'  , 'Detalle del valor');
		$oSmarty->assign ('stMESSAGE', $oValor->getErrores());
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$stSIGLA = $oValor->getSigla();
	$stNOMBRE = $oValor->getNombre();
	$stPERIODO = empty($_REQUEST['periodo'])?'3m':$_REQUEST['periodo'];
/*------------------------------------------------------------------------------------------------*/
	if (@$handlePAGINA = fopen("http://ar.finance.yahoo.com/q?s=$stSIGLA&d=c&k=c4",'r')){
		$stTEXTO = '';
		while (!feof($handlePAGINA)){
			$stTEXTO .= fgets($handlePAGINA, 10240);
		}
		fclose($handlePAGINA);

		$cadenaINI = "&Uacute;ltima transacci&oacute;n";
		$cadenaFIN = "<\/td><\/tr><tr><td><small>";

		preg_match("/$cadenaINI(.*)$cadenaFIN/s", $stTEXTO, $xRESULT);

		if (!empty($xRESULT[1])){
			$aux_tabla = '<table><tr align=center valign=top><td nowrap>&Uacute;ltima transacci&oacute;n';
			$fix_tabla = cleanTabla($aux_tabla.rtrim($xRESULT[1]));
			//
			/*
			$stRENDIM        = getInfoEnCelda($xRESULT[1], 'Rendim.', '</');
			$stDIVACCION     = getInfoEnCelda($xRESULT[1], 'Div/Acci&oacute;n', '</');
			$stCAPITALIZACION= getInfoEnCelda($xRESULT[1], 'Capitalizaci&oacute;n', '</');
			$stPG            = getInfoEnCelda($xRESULT[1], 'P/G', '</');
			$stGANACCION     = getInfoEnCelda($xRESULT[1], 'Gan./Acci&oacute;n', '</');
			
			
			echo '<p style="color:#FFFFFF;">';
			echo 'rendim: <b>'.$stRENDIM.'</b><br />';
			echo 'div_accion: <b>'.$stDIVACCION.'</b><br />';
			echo 'capializacion: <b>'.$stCAPITALIZACION.'</b><br />';
			echo 'p/g: <b>'.$stPG.'</b><br />';
			echo 'gan_accion: <b>'.$stGANACCION.'</b>';
			echo '</p>';
			*/
			//
			$oSmarty->assign('stTABLA', $fix_tabla);
		}
	}
/*------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stSIGLA'  , $stSIGLA);
	$oSmarty->assign('stNOMBRE' , $stNOMBRE);
	$oSmarty->assign('stPERIODO', $stPERIODO);
	$oSmarty->assign('stTITLE'  , 'Detalle del valor');

	$oSmarty->display('val.mas_info_rep.tpl.html');
?>