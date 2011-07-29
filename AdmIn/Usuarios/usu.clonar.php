<?php
	$u_usuario = $oUsuario->Id;
	$updFilter = !empty($updFilter)?$updFilter:'';
	$usuParaClon = $_POST['clone_user'];

	$rCartera = $oMyDB->Query("SELECT * FROM valores WHERE valUsuario = $usuParaClon $updFilter;");

	while ($rCartera && $dCartera = mysql_fetch_assoc($rCartera)){
		$u_sigla     = $dCartera['valSigla'];
		$u_nombre    = $dCartera['valNombre'];
		$u_especie   = $dCartera['valEspecie'];
		$u_sector    = $dCartera['valSector'];
		$u_moneda    = $dCartera['valMoneda'];
		$u_pais      = $dCartera['valPais'];
		$u_bolsa     = $dCartera['valBolsa'];
		$u_tabla     = $dCartera['valTabla'];
		$u_rangocb   = $dCartera['valRangoCB'];
		$u_rangocmb  = $dCartera['valRangoCMB'];
		$u_rangoce   = $dCartera['valRangoCE'];
		$u_volumen   = $dCartera['valVolumen'];
		$u_liquidez  = $dCartera['valLiquidez'];
		$u_variacion = $dCartera['valVariacion'];
		$u_estado    = $dCartera['valEstado'];
		$u_ultima    = $dCartera['valUltima'];
		$u_comentario= $dCartera['valComentario'];

		@$oMyDB->Command("INSERT INTO valores (valSigla, valNombre, valEspecie, valSector, valMoneda, valPais, valBolsa, ".
										 "valTabla, valRangoCB, valRangoCMB, valRangoCE, valVolumen, valLiquidez, valVariacion, valEstado, ".
										 "valUsuario, valUltima, valComentario) VALUES ('$u_sigla', '$u_nombre', $u_especie, $u_sector, ".
										 "$u_moneda, $u_pais, $u_bolsa, '$u_tabla', $u_rangocb, $u_rangocmb, $u_rangoce, $u_volumen, ".
										 "$u_liquidez, '$u_variacion', '$u_estado', $u_usuario, $u_ultima, '$u_comentario');");
	}
?>