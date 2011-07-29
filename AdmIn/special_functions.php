<?php
	function getCalculoRangos($tabla, $variacion, $riesgo, $deft='--')
	{
		$aRes = array('CB' =>$deft,
								  'CMB'=>$deft,
								  'CE' =>$deft,
								  'PROM'=>$deft);
		if (!empty($variacion)){
			if (!empty($riesgo)){
				$xVar = explode(' - ', $variacion);

				if (isset($xVar[0]) && isset($xVar[1])){
					$extremoInf = (float) str_replace(",", ".", $xVar[0]);
					$extremoSup = (float) str_replace(",", ".", $xVar[1]);
					$prom = ($extremoInf + $extremoSup) / 2;

					if ($prom > 0){
						$aRes['CB']  = $prom - ($prom * $riesgo[$tabla][0] / 100);
						$aRes['CMB'] = $prom - ($prom * $riesgo[$tabla][1] / 100);
						$aRes['CE']  = $prom - ($prom * $riesgo[$tabla][2] / 100);
						$aRes['PROM']= $prom;
		}}}}
		return $aRes;
	}
//---------------------------------------------------------------------
	function getEdadAccion($fecha_compra, $venta=''){
		$fecha_venta = $venta=='' ? date('Y-m-d') : $venta;

		$dateF = strtotime($fecha_compra, 0);
		$dateT = strtotime($fecha_venta , 0);
		$dateD = $dateT - $dateF;

		return floor($dateD / 86400);
	}
//---------------------------------------------------------------------
	function getDatosTemp($ultima, $compra, $tasas, $edad){
		$result = array('Utilidad'=>0,'Zeta'=>0,'Rangos'=>array('B'=>0,'MB'=>0,'E'=>0));

		if (!empty($tasas)){
			$vTasas = ($tasas['entrada'] - $tasas['salida']) * 100;
			/**/
			$result['Utilidad'] = (($ultima - $compra) * 100) / $compra;
			$result['Zeta'] = $vTasas>0 ? $result['Utilidad']-$vTasas : $result['Utilidad'];
			/**/
			$result['Rangos']['B'] = $tasas['buena'];
			$result['Rangos']['MB']= $tasas['muybuena'];
			$result['Rangos']['E'] = $tasas['excelente'];
		}
		return $result;
	}
?>