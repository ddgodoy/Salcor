<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsValores{
		var $Id = 0;
		var $Sigla    = '';
		var $Nombre   = '';
		var $Especie  = 0;
		var $Sector   = 0;
		var $Moneda   = 0;
		var $Pais     = 0;
		var $Bolsa    = 0;
		var $Tabla    = 0;
		var $RangoCB  = 0;
		var $RangoCMB = 0;
		var $RangoCE  = 0;
		var $Volumen  = 0;
		var $Liquidez = 0;
		var $Variacion= '';
		var $Estado   = '';
		var $Usuario  = 0;
		var $Ultima   = 0;
		var $Comentario = '';
		var $Recomendado= '';
		var $Rendim     = '';
		var $DivAccion  = '';
		var $Capitalizacion = '';
		var $PG         = '';
		var $GanAccion  = '';
		var $Errores    = array();
		var $DB;

		function clsValores(){
			$this->clearValores();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearValores(){
			$this->Id = 0;
			$this->Sigla    = '';
			$this->Nombre   = '';
			$this->Especie  = 0;
			$this->Sector   = 0;
			$this->Moneda   = 0;
			$this->Pais     = 0;
			$this->Bolsa    = 0;
			$this->Tabla    = 0;
			$this->RangoCB  = 0;
			$this->RangoCMB = 0;
			$this->RangoCE  = 0;
			$this->Volumen  = 0;
			$this->Liquidez = 0;
			$this->Variacion= '';
			$this->Estado   = 0;
			$this->Usuario  = 0;
			$this->Ultima   = 0;
			$this->Comentario = '';
			$this->Recomendado= '';
			$this->Rendim     = '';
			$this->DivAccion  = '';
			$this->Capitalizacion = '';
			$this->PG         = '';
			$this->GanAccion  = '';
			$this->Errores    = array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar()
		{
			$auxSigla = strtoupper($this->Sigla);
			if ($this->DB->Query("SELECT valId FROM valores WHERE valSigla LIKE UPPER('$auxSigla') AND valUsuario = $this->Usuario;")){
				$this->Errores['Duplicado'] = "El valor ya se encuentra registrado.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO valores (valSigla, valNombre, valEspecie, valSector, valMoneda, valPais, ".
						 "valBolsa, valRangoCB, valRangoCMB, valRangoCE, valEstado, valUsuario, valTabla, valComentario, valRecomendado) ".
						 "VALUES ('$this->Sigla', '$this->Nombre', $this->Especie, $this->Sector, $this->Moneda, ".
						 "$this->Pais, $this->Bolsa, $this->RangoCB, $this->RangoCMB, $this->RangoCE, '$this->Estado', ".
						 "$this->Usuario, '$this->Tabla', '$this->Comentario', '$this->Recomendado');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert) {
				$this->Errores['Registrar'] = 'El valor no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId();
			/*-------------------------------------------------------------------------*/
			$_updated = $this->getUltimosDatosBySiglas(array($auxSigla), getLocal('FINANCE'));

			if (count($_updated) > 0) {
				$resultado = $this->getStringCamposForUpdate($_updated[$auxSigla], $this->Id, true);

				if ($resultado['campos'] != '') { $this->DB->Command("UPDATE valores SET ".$resultado['campos']." WHERE valId = $this->Id;"); }
			}
			/*-------------------------------------------------------------------------*/
			return TRUE;
		}
		function Modificar()
		{
			if (!$this->DB->Query("SELECT valId FROM valores WHERE valId = $this->Id;")) {
				$this->Errores['Modificar'] = "El valor no existe.";
			}
			$auxSigla = strtoupper($this->Sigla);

			if ($this->DB->Query("SELECT valId FROM valores WHERE valSigla LIKE UPPER('$auxSigla') AND valUsuario = $this->Usuario AND valId != $this->Id;")) {
				$this->Errores['Duplicado'] = "El valor ya se encuentra registrado.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE valores SET valSigla = '$this->Sigla', valNombre = '$this->Nombre', valEspecie = ".
											"$this->Especie, valSector = $this->Sector, valMoneda = $this->Moneda, valPais = $this->Pais, ".
											"valBolsa = $this->Bolsa, valRangoCB = $this->RangoCB, valRangoCMB = $this->RangoCMB, ".
											"valRangoCE = $this->RangoCE, valUsuario = $this->Usuario, valTabla = '$this->Tabla', ".
											"valEstado = '$this->Estado', valComentario = '$this->Comentario', valRecomendado = '$this->Recomendado' ".
											"WHERE valId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "El valor no puede ser modificado."; return FALSE;
			}
			/*-------------------------------------------------------------------------*/
			$_updated = $this->getUltimosDatosBySiglas(array($this->Sigla), getLocal('FINANCE'));

			if (count($_updated) > 0) {
				$resultado = $this->getStringCamposForUpdate($_updated[$this->Sigla], 0, true);

				if ($resultado['campos'] != '') { $this->DB->Command("UPDATE valores SET ".$resultado['campos']." WHERE valId = $this->Id;"); }
			}
			/*-------------------------------------------------------------------------*/
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el valor.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM valores WHERE valId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el valor.'; return FALSE;
			}
			$this->DB->Command("DELETE FROM compras WHERE cmpValor = $this->Id;");
			$this->DB->Command("DELETE FROM ventas WHERE venValor = $this->Id;");

			return TRUE;
		}
		function findId($valor){
			$this->clearValores();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM valores WHERE valId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'El valor no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['valId'];
			$this->Sigla   = $datos['valSigla'];
			$this->Nombre  = $datos['valNombre'];
			$this->Especie = $datos['valEspecie'];
			$this->Sector  = $datos['valSector'];
			$this->Moneda  = $datos['valMoneda'];
			$this->Pais    = $datos['valPais'];
			$this->Bolsa   = $datos['valBolsa'];
			$this->Tabla   = $datos['valTabla'];
			$this->RangoCB = $datos['valRangoCB'];
			$this->RangoCMB= $datos['valRangoCMB'];
			$this->RangoCE = $datos['valRangoCE'];
			$this->Volumen = $datos['valVolumen'];
			$this->Liquidez= $datos['valLiquidez'];
			$this->Estado  = $datos['valEstado'];
			$this->Usuario = $datos['valUsuario'];
			$this->Ultima  = $datos['valUltima'];
			$this->Comentario = $datos['valComentario'];
			$this->Recomendado = $datos['valRecomendado'];
			//
			$this->Rendim = $datos['valRendim'];
			$this->DivAccion = $datos['valDivAccion'];
			$this->Capitalizacion = $datos['valCapitalizacion'];
			$this->PG = $datos['valPG'];
			$this->GanAccion = $datos['valGanAccion'];
			//
			return TRUE;
		}
//----------------------------------------------------------
		function clearErrores(){$this->Errores = array();}
		function hasErrores(){if (empty($this->Errores)){return FALSE;} else {return TRUE;}}
		function getErrores(){
			$error = '';
			foreach($this->Errores as $descripcion){$error .= $descripcion.'<br>';}
			return $error;
		}
//---------------------------------------------------------
		function setSigla($valor){
			$this->Sigla = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Sigla'] = 'Complete la sigla.';
			}
		}
		function getSigla(){return $this->DB->forShow($this->Sigla);}
		function editSigla(){return $this->DB->forEdit($this->Sigla);}
//---------------------------------------------------------
		function setNombre($valor){$this->Nombre = $this->DB->forSave($valor);}
		function getNombre(){return $this->DB->forShow($this->Nombre);}
		function editNombre(){return $this->DB->forEdit($this->Nombre);}
//----------------------------------------------------------
		function setEspecie($valor){
			$this->Especie = $valor;
			if (empty($this->Especie)){
				$this->Errores['Especie'] = 'Seleccione una especie.';
			}
		}
		function getEspecie(){return $this->Especie;}

		function getEspecies(){
			$arValores = array();
			$rsValores = $this->DB->Query("SELECT * FROM especies ORDER BY espCodigo;");
			while ($rsValores && $fila = mysql_fetch_assoc($rsValores)){
				$arValores[$fila['espId']] = '['.$fila['espCodigo'].'] '.$this->DB->forShow($fila['espNombre']);
			}
			return empty($arValores)?array('-- sin datos --'):$arValores;
		}
//----------------------------------------------------------
		function setSector($valor){
			$this->Sector = $valor;
			if (empty($this->Sector)){
				$this->Errores['Sector'] = 'Seleccione un sector.';
			}
		}
		function getSector(){return $this->Sector;}
		
		function getSectores(){
			$arValores = array();
			$rsValores = $this->DB->Query("SELECT * FROM sectores ORDER BY secNombre;");
			while ($rsValores && $fila = mysql_fetch_assoc($rsValores)){
				$arValores[$fila['secId']] = $this->DB->forShow($fila['secNombre']);
			}
			return empty($arValores)?array('-- sin datos --'):$arValores;
		}
//----------------------------------------------------------
		function setMoneda($valor){
			$this->Moneda = $valor;
			if (empty($this->Moneda)){
				$this->Errores['Moneda'] = 'Seleccione una moneda.';
			}
		}
		function getMoneda(){return $this->Moneda;}
		
		function getMonedas(){
			$arValores = array();
			$rsValores = $this->DB->Query("SELECT * FROM monedas ORDER BY monCodigo DESC;");
			while ($rsValores && $fila = mysql_fetch_assoc($rsValores)){
				$arValores[$fila['monId']] = '['.$fila['monCodigo'].'] '.$this->DB->forShow($fila['monNombre']);
			}
			return empty($arValores)?array('-- sin datos --'):$arValores;
		}
//----------------------------------------------------------
		function setPais($valor){
			$this->Pais = $valor;
			if (empty($this->Pais)){
				$this->Errores['Pais'] = 'Seleccione un pa&iacute;s.';
			}
		}
		function getPais(){return $this->Pais;}
		
		function getPaises(){
			$arValores = array();
			$rsValores = $this->DB->Query("SELECT * FROM paises ORDER BY paiCodigo;");
			while ($rsValores && $fila = mysql_fetch_assoc($rsValores)){
				$arValores[$fila['paiId']] = '['.$fila['paiCodigo'].'] '.$this->DB->forShow($fila['paiNombre']);
			}
			return empty($arValores)?array('-- sin datos --'):$arValores;
		}
//----------------------------------------------------------
		function setBolsa($valor){
			$this->Bolsa = $valor;
			if (empty($this->Bolsa)){
				$this->Errores['Bolsa'] = 'Seleccione una bolsa.';
			}
		}
		function getBolsa(){return $this->Bolsa;}

		function getBolsas(){
			$arValores = array();
			$rsValores = $this->DB->Query("SELECT * FROM bolsas ORDER BY bolCodigo;");
			while ($rsValores && $fila = mysql_fetch_assoc($rsValores)){
				$arValores[$fila['bolId']] = '['.$fila['bolCodigo'].'] '.$this->DB->forShow($fila['bolNombre']);
			}
			return empty($arValores)?array('-- sin datos --'):$arValores;
		}
//----------------------------------------------------------
		function getPorcentajes($cual, $usuario){
			$campo1 = 'rie'.$cual.'_bueno';
			$campo2 = 'rie'.$cual.'_muybueno';
			$campo3 = 'rie'.$cual.'_excelente';

			$arValores = array('b'=>0,'mb'=>0,'e'=>0);
			$rsValores = $this->DB->Query("SELECT $campo1 as b, $campo2 as mb, $campo3 as e FROM riesgo WHERE rieUsuario = $usuario;");
			if ($rsValores){
				$dtValores = mysql_fetch_assoc($rsValores);
				$arValores['b'] = $dtValores['b'];
				$arValores['mb']= $dtValores['mb'];
				$arValores['e'] = $dtValores['e'];
			}
			return $arValores;
		}
//----------------------------------------------------------
		function setTabla($valor){$this->Tabla = $valor;}
		function getTabla(){return $this->Tabla;}
		function getTablas(){return array('C'=>'Conservadora','A'=>'Agresiva','P'=>'Personal');}
//----------------------------------------------------------
		function setRangoCB($valor){$this->RangoCB = (float) $valor;}
		function getRangoCB(){return $this->RangoCB;}
//----------------------------------------------------------
		function setRangoCMB($valor){$this->RangoCMB = (float) $valor;}
		function getRangoCMB(){return $this->RangoCMB;}
//----------------------------------------------------------
		function setRangoCE($valor){$this->RangoCE = (float) $valor;}
		function getRangoCE(){return $this->RangoCE;}
//----------------------------------------------------------
		function setVolumen($valor){$this->Volumen = (float) $valor;}
		function getVolumen(){return $this->Volumen;}
//----------------------------------------------------------
		function setLiquidez($valor){$this->Liquidez = (float) $valor;}
		function getLiquidez(){return $this->Liquidez;}
//----------------------------------------------------------
		function setVariacion($valor){$this->Variacion = $valor;}
		function getVariacion(){return $this->Variacion;}
//----------------------------------------------------------
		function setEstado($valor){$this->Estado = $valor;}
		function getEstado(){return $this->Estado;}
//----------------------------------------------------------
		function setRecomendado($valor){$this->Recomendado = $valor;}
		function getRecomendado(){return $this->Recomendado;}
//----------------------------------------------------------
		function setUsuario($valor){$this->Usuario = $valor;}
		function getUsuario(){return $this->Usuario;}
//----------------------------------------------------------
		function setUltima($valor){$this->Ultima = $valor;}
		function getUltima(){return $this->Ultima;}
//----------------------------------------------------------
		function setComentario($valor){$this->Comentario = $this->DB->forSave($valor);}
		function getComentario(){return $this->DB->forShow($this->Comentario);}
		function editComentario(){return $this->DB->forEdit($this->Comentario);}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
//----------------------------------------------------------
		function getUltimosDatosBySiglas($siglas, $ruta_finance)
		{
			$respuesta = array();

			require_once($ruta_finance.'YahooFinanceAPI.php');

			spl_autoload_register(array('YahooFinanceAPI', 'autoload'));

			$api = new YahooFinanceAPI();
			$api->addOption("symbol");
			$api->addOption("lastTrade");					 	 	 // ultima transaccion
			$api->addOption("lastTradeDate");					 // fecha ultima transaccion
			$api->addOption("averageDailyVolume"); 	 	 // volumen promedio
			$api->addOption("volume"); 					  	 	 // volumen
			$api->addOption("dividendYeild");			 	 	 // rendimiento
			$api->addOption("marketCapitalization"); 	 // capitalizacion
			$api->addOption("EPSEstimateCurrentYear"); // eps estimado
			$api->addOption("priceEarningsRatio"); 		 // p/g
			$api->addOption("dividendPerShare");  		 // dividendos por accion
			$api->addOption("name");	  					 	 	 // nombre empresa
			$api->addOption("previousClose");	  			 // cierre anterior
			$api->addOption("open");	  			         // precio apertura
			$api->addOption("change");	  			       // variacion
			$api->addOption("bidRealTime");	           // oferta
			$api->addOption("askRealTime");						 // precio de venta
			$api->addOption("daysRange");						   // rango del dia
			$api->addOption(YahooFinance_Options::FIFTY_TWO_WEEK_RANGE);  // 52 week range
			$api->addOption(YahooFinance_Options::ONE_YEAR_TARGET_PRICE); // objetivo est 1a

			foreach ($siglas as $sigla) {
				$api->addSymbol($sigla);
			}
			$result = $api->getQuotes();

			if ($result->isSuccess()) {
				$quotes = $result->data;

				foreach ($quotes as $quote) {
					$stock_value['simbolo']   = $quote->symbol;
					$stock_value['ultima']    = $quote->lastTrade;
					$stock_value['ult_fecha'] = $quote->lastTradeDate;
					$stock_value['volumen']   = $quote->volume;
					$stock_value['nombre']    = $quote->name;
					$stock_value['rendim']    = $quote->dividendYeild;
					$stock_value['ganaccion'] = $quote->EPSEstimateCurrentYear;
					$stock_value['pg']        = $quote->priceEarningsRatio;
					$stock_value['divaccion'] = $quote->dividendPerShare;
					$stock_value['variacion'] = $quote->get(YahooFinance_Options::FIFTY_TWO_WEEK_RANGE);
					$stock_value['obj_est1a'] = $quote->get(YahooFinance_Options::ONE_YEAR_TARGET_PRICE);
					$stock_value['cierre_ant']= $quote->previousClose;
					$stock_value['apertura']  = $quote->open;
					$stock_value['cambio']    = $quote->change;
					$stock_value['oferta']    = $quote->bidRealTime;
					$stock_value['rango_dia'] = $quote->daysRange;
					$stock_value['capitalizacion'] = $quote->marketCapitalization;
					$stock_value['precio_venta']   = $quote->askRealTime;
					$stock_value['vol_promedio']   = $quote->averageDailyVolume;

					$respuesta[$quote->symbol] = $stock_value;
				}
			}
			return $respuesta;
		}
//----------------------------------------------------------
		function getValorEnTabla($haystack, $ini, $fin, $fix_simbolo)
		{
			$resultado = 0;
			$inicioValor = strpos($haystack, $ini);

			if ($inicioValor !== false){
				$inicioValor += strlen($ini);

				$auxLimite = strpos($haystack, $fin, $inicioValor);
				$fixLimite = $auxLimite - $inicioValor;
				$resultado = strip_tags(substr($haystack, $inicioValor, $fixLimite));

				if ($fix_simbolo){
					$resultado = str_replace(".", "", $resultado);
					$resultado = str_replace(",", ".",$resultado);
			}}
			return (float) $resultado;
		}
//----------------------------------------------------------
		function getValorLiquidez($volumen) {
			$liquidez  = 0;
			$stDivisor = "SELECT divValor FROM divisores WHERE divMoneda = $this->Moneda AND divUsuario = $this->Usuario LIMIT 1;";
			$rsDivisor = $this->DB->Query($stDivisor);

			if ($rsDivisor) {
				$dtDivisor = mysql_fetch_assoc($rsDivisor);
				if ($dtDivisor['divValor'] > 0) {
					$liquidez = $volumen / $dtDivisor['divValor'];
			}}
			return $liquidez;
		}
//----------------------------------------------------------
		function getInfoCelda($haystack, $ini, $fin, $fix_simbolo=false){
			$resultado = '';
			$inicioValor = strpos($haystack, $ini);
			if ($inicioValor !== false){
				$inicioValor += strlen($ini);

				$auxLimite = strpos($haystack, $fin, $inicioValor);
				$fixLimite = $auxLimite - $inicioValor;
				$resultado = strip_tags(substr($haystack, $inicioValor, $fixLimite));
			}
			
		  if ($fix_simbolo){
        $resultado = str_replace(".", "", $resultado);
        $resultado = str_replace(",", ".",$resultado);
      }
			return $resultado;
		}
//----------------------------------------------------------
		function mayContinue($valor, $check_comilla = false)
		{
			if (!empty($valor)) {
				if (strpos($valor, 'N/A') === false) {
					if ($check_comilla) {
						if (strpos($valor, '"') === false) { return true; }
					} else {
						return true;
			}}}
			return false;
		}
//----------------------------------------------------------
		function getStringCamposForUpdate($info, $val_id = 0, $cut = false)
		{
			$lastTr = 0; $campos = ''; $getLiq = false;

			if ($this->mayContinue($info['nombre'])) {
				$campos .= " valNombre = '".$info['nombre']."', ";
			}
			if ($this->mayContinue($info['volumen'])) {
				$campos .= " valVolumen = '".$info['volumen']."', "; $getLiq = true;
			}
			if ($this->mayContinue($info['variacion'], true)) {
				$campos .= " valVariacion = '".$info['variacion']."', ";
			}
			if ($this->mayContinue($info['rendim'])) {
				$campos .= " valRendim = '".$info['rendim']."', ";
			}
			if ($this->mayContinue($info['divaccion'])) {
				$campos .= " valDivAccion = '".$info['divaccion']."', ";
			}
			if ($this->mayContinue($info['pg'])) {
				$campos .= " valPG = '".$info['pg']."', ";
			}
			if ($this->mayContinue($info['ganaccion'])) {
				$campos .= " valGanAccion = '".$info['ganaccion']."', ";
			}
			if ($this->mayContinue($info['capitalizacion'])) {
				$campos .= " valCapitalizacion = '".$info['capitalizacion']."', ";
			}
			if ($this->mayContinue($info['ultima'])) {
				$campos .= " valUltima = '".$info['ultima']."', "; $lastTr = (float) $info['ultima'];
			}
			$existe = !empty($lastTr) ? true : false;

			if ($getLiq) {
				if (!empty($val_id)) { $this->findId($val_id); }

				$liquidez = $this->getValorLiquidez($info['volumen']);

				if (!empty($liquidez)) { $campos .= " valLiquidez = '".$liquidez."', "; }
			}
			if ($cut) {
				$campos = substr($campos, 0, -2);
			}
			return array('campos'=>$campos, 'existe'=>$existe);
		}
//----------------------------------------------------------
	}
?>