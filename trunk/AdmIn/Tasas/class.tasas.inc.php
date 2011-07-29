<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsTasas{
		var $Id = 0;
		var $Usuario  = 0;
		var $Buena    = 0;
		var $MBuena   = 0;
		var $Excelente= 0;
		var $Entrada  = 0;
		var $Salida   = 0;
		var $TasaBuena = array();
		var $TasaMBuena = array();
		var $TasaExcelente = array();
		var $Errores = array();
		var $DB;

		function clsTasas(){
			$this->clearTasas();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearTasas(){
			$this->Id = 0;
			$this->Usuario  = 0;
			$this->Buena    = 0;
			$this->MBuena   = 0;
			$this->Excelente= 0;
			$this->Entrada  = 0;
			$this->Salida   = 0;
			$this->TasaBuena = array();
			$this->TasaMBuena = array();
			$this->TasaExcelente = array();
			$this->Errores  = array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if ($this->DB->Query("SELECT tasId FROM tasas WHERE tasUsuario = $this->Usuario;")){
				$this->Errores['Duplicado'] = "La tasa ya existe para este usuario.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO tasas (tasUsuario, tasBuena, tasMBuena, tasExcelente, tasEntrada, tasSalida) ".
						 			 "VALUES ($this->Usuario, $this->Buena, $this->MBuena, $this->Excelente, $this->Entrada, $this->Salida);";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'La tasa no puede ser registrada.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}
		function Modificar(){
			if (!$this->DB->Query("SELECT tasId FROM tasas WHERE tasId = $this->Id;")){
				$this->Errores['Modificar'] = "La tasa no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE tasas SET tasBuena = $this->Buena, tasMBuena = $this->MBuena, tasExcelente = $this->Excelente, ".
											"tasEntrada = $this->Entrada, tasSalida = $this->Salida, tasUsuario = $this->Usuario WHERE tasId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "La tasa no puede ser modificada."; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$result = $this->DB->Query("SELECT * FROM tasas WHERE tasUsuario = $valor;");
			if (!$result){
				$this->Errores['Usuario'] = 'La tasa no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['tasId'];
			$this->Usuario  = $datos['tasUsuario'];
			$this->Buena    = $datos['tasBuena'];
			$this->MBuena   = $datos['tasMBuena'];
			$this->Excelente= $datos['tasExcelente'];
			$this->Entrada  = $datos['tasEntrada'];
			$this->Salida   = $datos['tasSalida'];

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
//----------------------------------------------------------
		function setBuena($valor){
			$this->Buena = (integer) $valor;
			if (empty($this->Buena)){
				$this->Errores['Buena'] = 'Complete el valor - rentabilidad buena.';
			}
			if ($this->Buena < 0 || $this->Buena > 100){
				$this->Errores['Buena_porc'] = 'El valor debe estar entre 1 y 100 - rentabilidad buena.';
			}
		}
		function getBuena(){return $this->Buena;}
//----------------------------------------------------------
		function setMBuena($valor){
			$this->MBuena = (integer) $valor;
			if (empty($this->MBuena)){
				$this->Errores['MBuena'] = 'Complete el valor - rentabilidad muy buena.';
			}
			if ($this->MBuena < 0 || $this->MBuena > 100){
				$this->Errores['MBuena_porc'] = 'El valor debe estar entre 1 y 100 - rentabilidad muy buena.';
			}
		}
		function getMBuena(){return $this->MBuena;}
//----------------------------------------------------------
		function setExcelente($valor){
			$this->Excelente = (integer) $valor;
			if (empty($this->Excelente)){
				$this->Errores['Excelente'] = 'Complete el valor - rentabilidad excelente.';
			}
			if ($this->Excelente < 0 || $this->Excelente > 100){
				$this->Errores['Excelente_porc'] = 'El valor debe estar entre 1 y 100 - rentabilidad excelente.';
			}
		}
		function getExcelente(){return $this->Excelente;}
//----------------------------------------------------------
		function setEntrada($valor){
			$this->Entrada = (float) $valor;
			if (empty($this->Entrada)){
				$this->Errores['Entrada'] = 'Complete el valor de entrada.';
			}
		}
		function getEntrada(){return $this->Entrada;}
//----------------------------------------------------------
		function setSalida($valor){
			$this->Salida = (float) $valor;
			if (empty($this->Salida)){
				$this->Errores['Salida'] = 'Complete el valor de salida.';
			}
		}
		function getSalida(){return $this->Salida;}
//----------------------------------------------------------
		function setUsuario($valor){$this->Usuario = $valor;}
		function getUsuario(){return $this->Usuario;}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
//----------------------------------------------------------
		function setArrayTasas(){
			$tAnualB = $this->getTasaBrutaAnual($this->Buena);
			$tAnualMB= $this->getTasaBrutaAnual($this->MBuena);
			$tAnualE = $this->getTasaBrutaAnual($this->Excelente);

			$tDiariaB = $tAnualB / 365;
			$tDiariaMB= $tAnualMB/ 365;
			$tDiariaE = $tAnualE / 365;

			$tMensualB = $tDiariaB * 30;
			$tMensualMB= $tDiariaMB* 30;
			$tMensualE = $tDiariaE * 30;

			$this->TasaBuena = array('A'=>$tAnualB,'M'=>$tMensualB,'D'=>$tDiariaB);
			$this->TasaMBuena = array('A'=>$tAnualMB,'M'=>$tMensualMB,'D'=>$tDiariaMB);
			$this->TasaExcelente = array('A'=>$tAnualE,'M'=>$tMensualE,'D'=>$tDiariaE);
		}
		function getTasaBrutaAnual($rentabilidad){
			$tAnual = 0;
			$xAnual = $this->Entrada + ($this->Entrada * $rentabilidad / 100);
			$tAnual = (($xAnual / $this->Salida) - 1) * 100;

			return $tAnual;
		}
//----------------------------------------------------------
	}
?>