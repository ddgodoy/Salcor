<?php
	require_once 'cOmmOns/config.inc.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$oMyDB  = new clsMyDB();
	$oValor = new clsValores();

	$valores_IN = '';
	$rValores = $oMyDB->Query("SELECT DISTINCT valSigla FROM valores WHERE valEstado = 'A' ORDER BY valUpdated_at ASC LIMIT 200;");

	while ($rValores && $fila = mysql_fetch_assoc($rValores)) {
		$aSiglas[] = $fila['valSigla'];
		$valores_IN .= "'".$fila['valSigla']."',";
	}
	$valores_IN = substr($valores_IN, 0, -1);
	
	if (!empty($valores_IN)) {
		$aInfo = $oValor->getUltimosDatosBySiglas($aSiglas, getLocal('FINANCE'));

		$r = $oMyDB->Query("SELECT valId, valSigla FROM valores WHERE valSigla IN ($valores_IN);");
		
		while ($r && $fila = mysql_fetch_assoc($r)) {
			$campos   = '';
			$id_valor = $fila['valId'];
			$valSigla = $fila['valSigla'];

			if (isset($aInfo[$valSigla]))
			{
				$oValor->findId($id_valor);
				$liquidez = $oValor->getValorLiquidez($aInfo[$valSigla]['volumen']);

				if (!empty($aInfo[$valSigla]['ultima'])) {
					$campos .= " valUltima = '".$aInfo[$valSigla]['ultima']."', ";
				}
				if (!empty($aInfo[$valSigla]['volumen'])) {
					$campos .= " valVolumen = '".$aInfo[$valSigla]['volumen']."', ";
				}
				if (!empty($aInfo[$valSigla]['variacion']))
				{
					if (strpos($aInfo[$valSigla]['variacion'], 'N/A') === false && strpos($aInfo[$valSigla]['variacion'], '"') === false)
					{
						$campos .= " valVariacion = '".$aInfo[$valSigla]['variacion']."', ";
					}
				}
				if (!empty($aInfo[$valSigla]['nombre'])) {
					$campos .= " valNombre = '".$aInfo[$valSigla]['nombre']."', ";
				}
				if (!empty($aInfo[$valSigla]['rendim']))
				{
					if (strpos($aInfo[$valSigla]['rendim'], 'N/A') === false)
					{
						$campos .= " valRendim = '".$aInfo[$valSigla]['rendim']."', ";
					}
				}
				if (!empty($aInfo[$valSigla]['divaccion']))
				{
					if (strpos($aInfo[$valSigla]['divaccion'], 'N/A') === false)
					{
						$campos .= " valDivAccion = '".$aInfo[$valSigla]['divaccion']."', ";
					}
				}
				if (!empty($aInfo[$valSigla]['pg']))
				{
					if (strpos($aInfo[$valSigla]['pg'], 'N/A') === false)
					{
						$campos .= " valPG = '".$aInfo[$valSigla]['pg']."', ";
					}
				}
				if (!empty($aInfo[$valSigla]['ganaccion']))
				{
					if (strpos($aInfo[$valSigla]['ganaccion'], 'N/A') === false)
					{
						$campos .= " valGanAccion = '".$aInfo[$valSigla]['ganaccion']."', ";
					}
				}
				if (!empty($aInfo[$valSigla]['capitalizacion']))
				{
					if (strpos($aInfo[$valSigla]['capitalizacion'], 'N/A') === false)
					{
						$campos .= " valCapitalizacion = '".$aInfo[$valSigla]['capitalizacion']."', ";
					}
				}
				if (!empty($liquidez)) {
					$campos .=  " valLiquidez = '".$liquidez."', ";
				}
			}
			//
			$oMyDB->Command("UPDATE valores SET $campos valUpdated_at = NOW() WHERE valId = $id_valor;");
		}
	}
?>