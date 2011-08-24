<?php
	require_once '../../cOmmOns/config.inc.php';

	$stRETURN    = 'bad';
	$stSIGLA     = !empty($_POST['sigla']) ? $_POST['sigla'] : '';
	$stRIESGO_B  = !empty($_POST['b']) ? (float) $_POST['b'] : 0;
	$stRIESGO_MB = !empty($_POST['mb'])? (float) $_POST['mb']: 0;
	$stRIESGO_E  = !empty($_POST['e']) ? (float) $_POST['e'] : 0;

	if ($stSIGLA != '') {
		$aInfo = $oValor->getUltimosDatosBySiglas(array($stSIGLA), getLocal('FINANCE'));

		if (count($aInfo) > 0) {
			if (!empty($aInfo[$stSIGLA]['variacion'])) {
				if (strpos($aInfo[$stSIGLA]['variacion'], 'N/A') === false) {
					if (strpos($aInfo[$stSIGLA]['variacion'], '"') === false)
					{
						$xVar = explode(' - ', $aInfo[$stSIGLA]['variacion']);
						if (count($xVar) > 0)
						{
							$extremoInf = (float) str_replace(",", ".",$xVar[0]);
							$extremoSup = (float) str_replace(",", ".",$xVar[1]);
							$prom = ($extremoInf + $extremoSup) / 2;

							if ($prom > 0) {
								$numB  = $prom - ($prom * $stRIESGO_B / 100);
								$numMB = $prom - ($prom * $stRIESGO_MB/ 100);
								$numE  = $prom - ($prom * $stRIESGO_E / 100);

								$stRETURN = $numB.'_'.$numMB.'_'.$numE;
							}
						}
					}
				}
			}
		}
	}
	echo $stRETURN;
?>