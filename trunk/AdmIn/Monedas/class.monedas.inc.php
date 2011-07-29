<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsMonedas{
		var $Id = 0;
		var $Codigo = '';
		var $Nombre = '';
		var $Errores= array();
		var $DB;

		function clsMonedas(){
			$this->clearMonedas();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearMonedas(){
			$this->Id = 0;
			$this->Codigo = '';
			$this->Nombre = '';
			$this->Errores= array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO monedas (monCodigo, monNombre) VALUES ('$this->Codigo', '$this->Nombre');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'La moneda no puede ser registrada.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}		
		function Modificar(){
			$result = $this->DB->Query("SELECT monId FROM monedas WHERE monId = $this->Id;");
			if (!$result){
				$this->Errores['Modificar'] = "La moneda no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE monedas SET monCodigo = '$this->Codigo', monNombre = '$this->Nombre' WHERE monId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "La moneda no puede ser modificada."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la moneda.'; return FALSE;
			}
			if ($this->DB->Query("SELECT valId FROM valores WHERE valMoneda = $this->Id;")){
				$this->Errores['Dependencias'] = 'Hay valores relaciones con esta moneda. Debe eliminarlos para continuar.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM monedas WHERE monId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la moneda.'; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$this->clearMonedas();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM monedas WHERE monId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'La moneda no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['monId'];
			$this->Codigo = $datos['monCodigo'];
			$this->Nombre = $datos['monNombre'];

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
		function setCodigo($valor){
			$this->Codigo = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Codigo'] = 'Complete el c&oacute;digo.';
			}
		}
		function getCodigo(){return $this->DB->forShow($this->Codigo);}
		function editCodigo(){return $this->DB->forEdit($this->Codigo);}
//----------------------------------------------------------
		function setNombre($valor){
			$this->Nombre = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Nombre'] = 'Complete el nombre.';
			}
		}
		function getNombre(){return $this->DB->forShow($this->Nombre);}
		function editNombre(){return $this->DB->forEdit($this->Nombre);}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
	}
?>