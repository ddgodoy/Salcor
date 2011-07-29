<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsDivisores{
		var $Id = 0;
		var $Usuario = 0;
		var $Moneda  = 0;
		var $Valor   = 0;
		var $Errores = array();
		var $DB;

		function clsDivisores(){
			$this->clearDivisores();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearDivisores(){
			$this->Id = 0;
			$this->Usuario = 0;
			$this->Moneda  = 0;
			$this->Valor   = 0;
			$this->Errores = array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO divisores (divMoneda, divValor, divUsuario) VALUES ($this->Moneda, $this->Valor, $this->Usuario);";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'El divisor no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}
		function Modificar(){
			if (!$this->DB->Query("SELECT divId FROM divisores WHERE divId = $this->Id;")){
				$this->Errores['Modificar'] = "El divisor no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE divisores SET divMoneda = $this->Moneda, divValor = $this->Valor, ".
							"divUsuario = $this->Usuario WHERE divId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "El divisor no puede ser modificado."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el divisor.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM divisores WHERE divId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el divisor.'; return FALSE;
			}
			/*---------------------------------------------------------------*/
			/*---------------------------------------------------------------*/
			/*AGREGAR LOGICA PARA ELIMINAR TODAS LAS DEPENDENCIAS DEl DIVISOR*/
			/*---------------------------------------------------------------*/
			/*---------------------------------------------------------------*/
			return TRUE;
		}
		function findId($valor){
			$this->clearDivisores();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM divisores WHERE divId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'El divisor no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['divId'];
			$this->Usuario= $datos['divUsuario'];
			$this->Moneda = $datos['divMoneda'];
			$this->Valor  = $datos['divValor'];

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
		function setMoneda($valor){$this->Moneda = $valor;}
		function getMoneda(){return $this->Moneda;}
//----------------------------------------------------------
		function setUsuario($valor){$this->Usuario = $valor;}
		function getUsuario(){return $this->Usuario;}
//----------------------------------------------------------
		function setValor($valor){
			$this->Valor = (float) $valor;
			if (empty($this->Valor)){$this->Errores['Valor'] = 'Complete el valor.';}
		}
		function getValor(){return $this->Valor;}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
	}
?>