<?php
	require_once 'cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('COMMONS').'func.mail.php';
/*------------------------------------------------------------------------------*/
	function getCalculoRango($tabla, $variacion, $usuario, $db){
		$aRes = array('CE'=>0);

		if (!empty($variacion)){
			$stQry = "SELECT rie$tabla"."_excelente as e FROM riesgo WHERE rieUsuario = $usuario;";
			$rsQry = $db->Query($stQry);
			if ($rsQry){
				$dtQry = mysql_fetch_assoc($rsQry);
				$xVar = explode(' - ',$variacion);
				if (count($xVar) > 0){
					$extremoInf = (float) str_replace(",", ".",$xVar[0]);
					$extremoSup = (float) str_replace(",", ".",$xVar[1]);
					$prom = ($extremoInf + $extremoSup) / 2;
					if ($prom > 0){
						$aRes['CE'] = $prom - ($prom * $dtQry['e'] / 100);
		}}}}
		return $aRes;
	}
/*------------------------------------------------------------------------------*/
	$oMyDB = new clsMyDB();
	$fecActual = date('Y-m-d');
	$rUsuarios = $oMyDB->Query("SELECT usuId, usuPersonal FROM usuarios WHERE usuEnabled = 'S' AND usuAlerta = 'S';");

	while ($rUsuarios && $fila = mysql_fetch_assoc($rUsuarios)){
		$id_usuario = $fila['usuId'];
		$email_usuario = !empty($fila['usuPersonal'])?$oMyDB->forShow($fila['usuPersonal']):!empty($fila['usuLaboral'])?$oMyDB->forShow($fila['usuPersonal']):'';

		if (!empty($email_usuario)){
			$arEnviar = array();
			$rValores = $oMyDB->Query("SELECT valId, valSigla, valTabla, valVariacion, valUltima FROM valores WHERE valUsuario = $id_usuario ORDER BY valSigla;");
			while ($rValores && $f2 = mysql_fetch_assoc($rValores)){
				$aAuxRango = getCalculoRango($f2['valTabla'], $f2['valVariacion'], $id_usuario, $oMyDB);

				if ($aAuxRango['CE'] > 0 && $f2['valUltima'] <= $aAuxRango['CE']){
					$arEnviar[$f2['valId']]['sigla'] = $f2['valSigla'];
					$arEnviar[$f2['valId']]['valor'] = $aAuxRango['CE'];
				}
			}
			/*------------------------------------------------------------------------------------------*/
			$xEnviar = array();
			foreach ($arEnviar as $key => $env){
				$rVer = $oMyDB->Query("SELECT valor, fecha FROM mails_compra WHERE usuario = $id_usuario AND accion = $key;");
				if ($rVer){
					$dVer = mysql_fetch_assoc($rVer);
					$auxiVal_a = (float) $dVer['valor'];
					$auxiVal_b = (float) $env['valor'];

					if ($auxiVal_a != $auxiVal_b && $fecActual != $dVer['fecha']){
						$xEnviar[] = $env['sigla'];
						$oMyDB->Command("UPDATE mails_compra SET valor = $auxiVal_b, fecha = '$fecActual' WHERE usuario = $id_usuario AND accion = $key;");
					}
				} else {
					$xEnviar[] = $env['sigla'];
					$oMyDB->Command("INSERT INTO mails_compra (usuario, accion, valor, fecha) VALUES ($id_usuario, $key, $auxiVal_b, '$fecActual');");
				}
			}
			/*------------------------------------------------------------------------------------------*/
			if (count($xEnviar) > 0){
				$aux_texto = '';
				$aux_html = '';

				foreach ($xEnviar as $x_env){
					$aux_texto .= $x_env."\n";
					$aux_html .= '<tr><td>'.$x_env.'</td></tr>';
				}
				$texto = 'SALCOR Stock Manager - Analisis de Compra'."\n"."\n".
						 'Estas acciones se encuentran con rango de compra excelente:'."\n".
						 $aux_texto;
				$html = "<html>
						<head>
						<title>SALCOR Stock Manager - Analisis de Compra</title>
						<style type='text/css'>
							body{background-color: #E8ECF0;}
							td {font-family: arial;font-size: 11px;}
						</style>
						</head>
						<body>
							<table>
								<tr><td><h1>SALCOR Stock Manager - Analisis de Compra</h1></td></tr>
								<tr><td>Estas acciones se encuentran con rango de compra excelente&nbsp;</td></tr>
								$aux_html
							</table>
						</body>
						</html>";
				@send_mail($email_usuario, "info@salcor.com.ar", 'SALCOR Stock Manager - Analisis de Compra', $html, $texto);
			}
		}
	}
?>