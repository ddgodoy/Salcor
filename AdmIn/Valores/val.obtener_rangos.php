<?php
	require_once '../../cOmmOns/config.inc.php';

	$stRETURN   = 'bad';
	$stSIGLA    = !empty($_POST['sigla'])?$_POST['sigla']:'';
	$stRIESGO_B = !empty($_POST['b']) ? (float) $_POST['b'] : 0;
	$stRIESGO_MB= !empty($_POST['mb'])? (float) $_POST['mb']: 0;
	$stRIESGO_E = !empty($_POST['e']) ? (float) $_POST['e'] : 0;

	if ($stSIGLA != ''){
		if (@$handlePAGINA = fopen("http://ar.finance.yahoo.com/q?s=$stSIGLA&d=c&k=c4",'r')){
			$stTEXTO = '';
			while (!feof($handlePAGINA)){$stTEXTO .= fgets($handlePAGINA, 10240);}
			fclose($handlePAGINA);

			$cadenaINI = "&Uacute;ltima transacci&oacute;n";
			$cadenaFIN = "<\/td><\/tr><tr><td><small>";

			preg_match("/$cadenaINI(.*)$cadenaFIN/s", $stTEXTO, $xRESULT);

			if (!empty($xRESULT[1])){
				$fin = '</';
				$ini = 'a&ntilde;o';
				$haystack = $xRESULT[1];

				$inicioValor = strpos($haystack, $ini);
				if ($inicioValor !== false){
					$inicioValor += strlen($ini);

					$auxLimite = strpos($haystack, $fin, $inicioValor);
					$fixLimite = $auxLimite - $inicioValor;
					$resultado = strip_tags(substr($haystack, $inicioValor, $fixLimite));

					$xVar = explode(' - ',$resultado);
					if (count($xVar) > 0){
						$extremoInf = (float) str_replace(",", ".",$xVar[0]);
						$extremoSup = (float) str_replace(",", ".",$xVar[1]);
						$prom = ($extremoInf + $extremoSup) / 2;
						if ($prom > 0){
							$numB = $prom - ($prom * $stRIESGO_B / 100);
							$numMB= $prom - ($prom * $stRIESGO_MB/ 100);
							$numE = $prom - ($prom * $stRIESGO_E / 100);

							$stRETURN = $numB.'_'.$numMB.'_'.$numE;
	}}}}}}
	echo $stRETURN;
?>