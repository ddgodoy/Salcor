<?php
	require_once 'cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('COMMONS').'func.mail.php';
/*------------------------------------------------------------------------------*/
	function getDatosTemp($ultima, $compra, $fecha, $user, $db){
		$result = array('Utilidad'=>0,'Zeta'=>0,'Rangos'=>array('E'=>0));
		/*----------------------------------------------------*/
		$dateF = strtotime($fecha, 0);
		$dateT = strtotime(date('Y-m-d'), 0);
		$dateD = $dateT - $dateF;
		$fecha = floor($dateD / 86400);
		$xEdad = $fecha > 0 ? $fecha : 1;
		/*----------------------------------------------------*/
		$rTasas = $db->Query("SELECT * FROM tasas WHERE tasUsuario = $user;");
		if ($rTasas){
			$dTasas = mysql_fetch_assoc($rTasas);
			$vTasas = ($dTasas['tasEntrada'] - $dTasas['tasSalida']) * 100;

			$result['Utilidad'] = (($ultima - $compra) * 100) / $compra;
			$result['Zeta'] = $vTasas>0 ? $result['Utilidad']-$vTasas : $result['Utilidad'];

			$result['Rangos']['E'] = $dTasas['tasTBDExcelente'] * $xEdad;
		}
		return $result;
	}
/*------------------------------------------------------------------------------*/
	$oMyDB = new clsMyDB();
	$rUsuarios = $oMyDB->Query("SELECT usuId, usuPersonal FROM usuarios WHERE usuEnabled = 'S' AND usuAlerta = 'S';");

	while ($rUsuarios && $fila = mysql_fetch_assoc($rUsuarios)){
		$id_usuario = $fila['usuId'];
		$email_usuario = !empty($fila['usuPersonal'])?$oMyDB->forShow($fila['usuPersonal']):!empty($fila['usuLaboral'])?$oMyDB->forShow($fila['usuPersonal']):'';

		if (!empty($email_usuario)){
			$arEnviar = array();
			$sValores = "SELECT valId, valSigla, valUltima, cmpPrecio, cmpFecha ".
									"FROM valores, compras WHERE valId = cmpValor AND cmpEstado = 'A' ".
									"AND valUsuario = $id_usuario;";
			$rValores = $oMyDB->Query($sValores);

			while ($rValores && $f2 = mysql_fetch_assoc($rValores)){
				$aAux = getDatosTemp($f2['valUltima'], $f2['cmpPrecio'], $f2['cmpFecha'], $id_usuario, $oMyDB);

				if ($aAux['Rangos']['E'] != 0 && $aAux['Zeta'] >= $aAux['Rangos']['E']){
					$arEnviar[$f2['valId']]['sigla'] = $f2['valSigla'];
					$arEnviar[$f2['valId']]['valor'] = $aAux['Rangos']['E'];
				}
			}
			/*------------------------------------------------------------------------------------------*/
			$xEnviar = array();
			foreach ($arEnviar as $key => $env){
				$rVer = $oMyDB->Query("SELECT valor, fecha FROM mails_venta WHERE usuario = $id_usuario AND accion = $key;");
				if ($rVer){
					$fecActual = date('Y-m-d');
					$dVer = mysql_fetch_assoc($rVer);

					if ($dVer['valor'] != $env['valor'] && $fecActual != $dVer['fecha']){
						$xEnviar[] = $env['sigla'];
						$oMyDB->Command("UPDATE mails_venta SET valor = ".$env['valor'].", fecha = '$fecActual' WHERE usuario = $id_usuario AND accion = $key;");
					}
				} else {
					$fecEnBase = date('Y-m-d');
					$xEnviar[] = $env['sigla'];
					$oMyDB->Command("INSERT INTO mails_venta (usuario, accion, valor, fecha) VALUES ($id_usuario, $key, ".$env['valor'].", '$fecActual');");
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
				$texto = 'SALCOR Stock Manager - Analisis de Rentabilidad'."\n"."\n".
						 'Estas acciones se encuentran con rango de venta excelente:'."\n".
						 $aux_texto;
				$html = "<html>
						<head>
						<title>SALCOR Stock Manager - Analisis de Rentabilidad</title>
						<style type='text/css'>
							body{background-color: #E8ECF0;}
							td {font-family: arial;font-size: 11px;}
						</style>
						</head>
						<body>
							<table>
								<tr><td><h1>SALCOR Stock Manager - Analisis de Rentabilidad</h1></td></tr>
								<tr><td>Estas acciones se encuentran con rango de venta excelente&nbsp;</td></tr>
								$aux_html
							</table>
						</body>
						</html>";
				@send_mail($email_usuario, "info@salcor.com.ar", 'SALCOR Stock Manager - Analisis de Rentabilidad', $html, $texto);
			}
		}
	}
?>