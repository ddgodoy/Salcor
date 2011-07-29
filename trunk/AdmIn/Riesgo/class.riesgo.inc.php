<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsRiesgo{
		var $Id = 0;
		var $Usuario    = 0;
		var $C_bueno    = 0;
		var $C_muybueno = 0;
		var $C_excelente= 0;
		var $A_bueno    = 0;
		var $A_muybueno = 0;
		var $A_excelente= 0;
		var $P_bueno    = 0;
		var $P_muybueno = 0;
		var $P_excelente= 0;
		var $Errores = array();
		var $DB;

		function clsRiesgo(){
			$this->clearRiesgo();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearRiesgo(){
			$this->Id = 0;
			$this->Usuario    = 0;
			$this->C_bueno    = 0;
			$this->C_muybueno = 0;
			$this->C_excelente= 0;
			$this->A_bueno    = 0;
			$this->A_muybueno = 0;
			$this->A_excelente= 0;
			$this->P_bueno    = 0;
			$this->P_muybueno = 0;
			$this->P_excelente= 0;
			$this->Errores = array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if ($this->DB->Query("SELECT rieId FROM riesgo WHERE rieUsuario = $this->Usuario;")){
				$this->Errores['Duplicado'] = "El factor de riesgo ya existe para este usuario.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO riesgo (rieUsuario, rieC_bueno, rieC_muybueno, rieC_excelente, ".
						 "rieA_bueno, rieA_muybueno, rieA_excelente, rieP_bueno, rieP_muybueno, rieP_excelente) ".
						 "VALUES ($this->Usuario, $this->C_bueno, $this->C_muybueno, $this->C_excelente, ".
						 "$this->A_bueno, $this->A_muybueno, $this->A_excelente, $this->P_bueno, ".
						 "$this->P_muybueno, $this->P_excelente);";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'El factor de riesgo no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}
		function Modificar(){
			if (!$this->DB->Query("SELECT rieId FROM riesgo WHERE rieId = $this->Id;")){
				$this->Errores['Modificar'] = "El factor de riesgo no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE riesgo SET rieC_bueno = $this->C_bueno, rieC_muybueno = $this->C_muybueno, ".
							"rieC_excelente = $this->C_excelente, rieA_bueno = $this->A_bueno, rieA_muybueno = ".
							"$this->A_muybueno, rieA_excelente = $this->A_excelente, rieP_bueno = $this->P_bueno, ".
							"rieP_muybueno = $this->P_muybueno, rieP_excelente = $this->P_excelente, rieUsuario = ".
							"$this->Usuario WHERE rieId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "El factor de riesgo no puede ser modificado."; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$result = $this->DB->Query("SELECT * FROM riesgo WHERE rieUsuario = $valor;");
			if (!$result){
				$this->Errores['Usuario'] = 'El factor de riesgo no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['rieId'];
			$this->Usuario    = $datos['rieUsuario'];
			$this->C_bueno    = $datos['rieC_bueno'];
			$this->C_muybueno = $datos['rieC_muybueno'];
			$this->C_excelente= $datos['rieC_excelente'];
			$this->A_bueno    = $datos['rieA_bueno'];
			$this->A_muybueno = $datos['rieA_muybueno'];
			$this->A_excelente= $datos['rieA_excelente'];
			$this->P_bueno    = $datos['rieP_bueno'];
			$this->P_muybueno = $datos['rieP_muybueno'];
			$this->P_excelente= $datos['rieP_excelente'];

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
		function setC_bueno($valor){
			$this->C_bueno = (float) $valor;
			if (empty($this->C_bueno)){
				$this->Errores['C_bueno'] = 'Complete el valor - Conservadora [bueno].';
			}
			if ($this->C_bueno < 0 || $this->C_bueno > 100){
				$this->Errores['C_bueno_porc'] = 'El valor debe estar entre 1 y 100 - Conservadora [bueno].';
			}
		}
		function getC_bueno(){return $this->C_bueno;}
//----------------------------------------------------------
		function setC_muybueno($valor){
			$this->C_muybueno = (float) $valor;
			if (empty($this->C_muybueno)){
				$this->Errores['C_muybueno'] = 'Complete el valor - Conservadora [muy bueno].';
			}
			if ($this->C_muybueno < 0 || $this->C_muybueno > 100){
				$this->Errores['C_muybueno_porc'] = 'El valor debe estar entre 1 y 100 - Conservadora [muy bueno].';
			}
		}
		function getC_muybueno(){return $this->C_muybueno;}
//----------------------------------------------------------
		function setC_excelente($valor){
			$this->C_excelente = (float) $valor;
			if (empty($this->C_excelente)){
				$this->Errores['C_excelente'] = 'Complete el valor - Conservadora [excelente].';
			}
			if ($this->C_excelente < 0 || $this->C_excelente > 100){
				$this->Errores['C_excelente_porc'] = 'El valor debe estar entre 1 y 100 - Conservadora [excelente].';
			}
		}
		function getC_excelente(){return $this->C_excelente;}
//----------------------------------------------------------
		function setA_bueno($valor){
			$this->A_bueno = (float) $valor;
			if (empty($this->A_bueno)){
				$this->Errores['A_bueno'] = 'Complete el valor - Agresiva [bueno].';
			}
			if ($this->A_bueno < 0 || $this->A_bueno > 100){
				$this->Errores['A_bueno_porc'] = 'El valor debe estar entre 1 y 100 - Agresiva [bueno].';
			}
		}
		function getA_bueno(){return $this->A_bueno;}
//----------------------------------------------------------
		function setA_muybueno($valor){
			$this->A_muybueno = (float) $valor;
			if (empty($this->A_muybueno)){
				$this->Errores['A_muybueno'] = 'Complete el valor - Agresiva [muy bueno].';
			}
			if ($this->A_muybueno < 0 || $this->A_muybueno > 100){
				$this->Errores['A_muybueno_porc'] = 'El valor debe estar entre 1 y 100 - Agresiva [muy bueno].';
			}
		}
		function getA_muybueno(){return $this->A_muybueno;}
//----------------------------------------------------------
		function setA_excelente($valor){
			$this->A_excelente = (float) $valor;
			if (empty($this->A_excelente)){
				$this->Errores['A_excelente'] = 'Complete el valor - Agresiva [excelente].';
			}
			if ($this->A_excelente < 0 || $this->A_excelente > 100){
				$this->Errores['A_excelente_porc'] = 'El valor debe estar entre 1 y 100 - Agresiva [excelente].';
			}
		}
		function getA_excelente(){return $this->A_excelente;}
//----------------------------------------------------------
		function setP_bueno($valor){
			$this->P_bueno = (float) $valor;
			if (empty($this->P_bueno)){
				$this->Errores['P_bueno'] = 'Complete el valor - Personal [bueno].';
			}
			if ($this->P_bueno < 0 || $this->P_bueno > 100){
				$this->Errores['P_bueno_porc'] = 'El valor debe estar entre 1 y 100 - Personal [bueno].';
			}
		}
		function getP_bueno(){return $this->P_bueno;}
//----------------------------------------------------------
		function setP_muybueno($valor){
			$this->P_muybueno = (float) $valor;
			if (empty($this->P_muybueno)){
				$this->Errores['P_muybueno'] = 'Complete el valor - Personal [muy bueno].';
			}
			if ($this->P_muybueno < 0 || $this->P_muybueno > 100){
				$this->Errores['P_muybueno_porc'] = 'El valor debe estar entre 1 y 100 - Personal [muy bueno].';
			}
		}
		function getP_muybueno(){return $this->P_muybueno;}
//----------------------------------------------------------
		function setP_excelente($valor){
			$this->P_excelente = (float) $valor;
			if (empty($this->P_excelente)){
				$this->Errores['P_excelente'] = 'Complete el valor - Personal [excelente].';
			}
			if ($this->P_excelente < 0 || $this->P_excelente > 100){
				$this->Errores['P_excelente_porc'] = 'El valor debe estar entre 1 y 100 - Personal [excelente].';
			}
		}
		function getP_excelente(){return $this->P_excelente;}
//----------------------------------------------------------
		function setUsuario($valor){$this->Usuario = $valor;}
		function getUsuario(){return $this->Usuario;}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
	}
?>