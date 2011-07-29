<?php
	require_once 'cOmmOns/config.inc.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$oMyDB  = new clsMyDB();
	$oValor = new clsValores();

	$rValores = $oMyDB->Query("SELECT DISTINCT valSigla FROM valores WHERE valEstado = 'A' AND (valUpdated_at < CURDATE() OR valUpdated_at is null) LIMIT 200;");

	if ($rValores) {
		while ($fila = mysql_fetch_assoc($rValores)) {
			$aSiglas[] = $fila['valSigla'];
		}
		$aInfo = $oValor->getUltimosDatosBySiglas($aSiglas, getLocal('FINANCE'));
		//
		$r = $oMyDB->Query("SELECT valId, valSigla FROM valores WHERE valEstado = 'A' AND (valUpdated_at < CURDATE() OR valUpdated_at is null) LIMIT 100;");

		while ($r && $fila = mysql_fetch_assoc($r)) {
			$campos   = '';
			$id_valor = $fila['valId'];
			$valSigla = $fila['valSigla'];

			if (isset($aInfo[$valSigla]))
			{
				$oValor->findId($id_valor);
				$liquidez = $oValor->getValorLiquidez($aInfo[$valSigla]['volumen']);

				$campos .= !empty($aInfo[$valSigla]['ultima'])    ? "valUltima = '".   $aInfo[$valSigla]['ultima']."',"    : '';
				$campos .= !empty($aInfo[$valSigla]['volumen'])   ? "valVolumen = '".  $aInfo[$valSigla]['volumen']."',"   : '';
				$campos .= !empty($aInfo[$valSigla]['variacion']) ? "valVariacion = '".$aInfo[$valSigla]['variacion']."'," : '';
				$campos .= !empty($aInfo[$valSigla]['nombre'])    ? "valNombre = '".   $aInfo[$valSigla]['nombre']."',"    : '';
				$campos .= !empty($aInfo[$valSigla]['rendim'])    ? "valRendim = '".   $aInfo[$valSigla]['rendim']."',"    : '';
				$campos .= !empty($aInfo[$valSigla]['divaccion']) ? "valDivAccion = '".$aInfo[$valSigla]['divaccion']."'," : '';
				$campos .= !empty($aInfo[$valSigla]['pg'])        ? "valPG = '".       $aInfo[$valSigla]['pg']."',"        : '';
				$campos .= !empty($aInfo[$valSigla]['ganaccion']) ? "valGanAccion = '".$aInfo[$valSigla]['ganaccion']."'," : '';
				$campos .= !empty($aInfo[$valSigla]['capitalizacion']) ? "valCapitalizacion = '".$aInfo[$valSigla]['capitalizacion']."',": '';
				$campos .= !empty($liquidez) ? "valLiquidez = '".$liquidez."',": '';
			}
			if ($campos != '') {
				$oMyDB->Command("UPDATE valores SET $campos valUpdated_at = NOW() WHERE valId = $id_valor;");
			}
		}
	}
?>