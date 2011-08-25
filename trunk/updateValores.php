<?php
	/* http://finance.yahoo.com/q/bc?s=ACIN.BA&t=&c= */

	require_once 'cOmmOns/config.inc.php';
	require_once getLocal('VAL').'class.valores.inc.php';

	$oMyDB  = new clsMyDB();
	$oValor = new clsValores();
	$limite = 100;

	$valores_IN = '';
	$rValores = $oMyDB->Query("SELECT DISTINCT valSigla FROM valores WHERE valEstado = 'A' AND valIsValid = 1 ORDER BY valUpdated_at ASC LIMIT 200;");

	while ($rValores && $fila = mysql_fetch_assoc($rValores)) {
		$aSiglas[] = $fila['valSigla'];
		$valores_IN .= "'".$fila['valSigla']."',";
	}
	$valores_IN = substr($valores_IN, 0, -1);
	
	if (!empty($valores_IN)) {
		$aInfo = $oValor->getUltimosDatosBySiglas($aSiglas, getLocal('FINANCE'));

		$r = $oMyDB->Query("SELECT valId, valSigla, valCheckNotValid FROM valores WHERE valSigla IN ($valores_IN);");
		
		while ($r && $fila = mysql_fetch_assoc($r))
		{
			$campos   = '';
			$id_valor = $fila['valId'];
			$valSigla = $fila['valSigla'];
			$valValid = $fila['valCheckNotValid'];

			if (isset($aInfo[$valSigla]))
			{
				$resultado = $oValor->getStringCamposForUpdate($aInfo[$valSigla], $id_valor);
				$campos = $resultado['campos'];

				$_counter = 0;
				$_isvalid = 1;

				if (!$resultado['existe']) {
					$_counter = $valValid + 1; if ($_counter >= $limite) { $_isvalid = 0; }
				}
				$campos .= " valCheckNotValid = $_counter, valIsValid = $_isvalid, ";
			}
			$oMyDB->Command("UPDATE valores SET $campos valUpdated_at = NOW() WHERE valId = $id_valor;");
		}
	}
?>