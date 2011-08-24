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
		$oSmarty->assign ('stMESSAGE', 'No puede entrar a esta p�gina directamente.');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$stID = $_REQUEST['Id'];

	require_once getLocal('VAL').'class.valores.inc.php';

	$oValor = new clsValores();

	if (!$oValor->findId($stID)) {
		$oSmarty->assign ('stTITLE'  , 'Detalle del valor');
		$oSmarty->assign ('stMESSAGE', $oValor->getErrores());
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$stSIGLA = $oValor->getSigla();
	$stNOMBRE = $oValor->getNombre();
	$stPERIODO = empty($_REQUEST['periodo']) ? '3m' : $_REQUEST['periodo'];
/*------------------------------------------------------------------------------------------------*/
	$aInfo = $oValor->getUltimosDatosBySiglas(array($stSIGLA), getLocal('FINANCE'));

	if (count($aInfo) > 0) {
		$oSmarty->assign('stDATOSTABLA', $aInfo);
	}
/*------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stSIGLA'  , $stSIGLA);
	$oSmarty->assign('stNOMBRE' , $stNOMBRE);
	$oSmarty->assign('stPERIODO', $stPERIODO);
	$oSmarty->assign('stTITLE'  , 'Detalle del valor');

	$oSmarty->display('val.mas_info_rep.tpl.html');
?>