<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsEspecies{
		var $Id = 0;
		var $Codigo = '';
		var $Nombre = '';
		var $Errores= array();
		var $DB;

		function clsEspecies(){
			$this->clearEspecies();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearEspecies(){
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

			$qryInsert = "INSERT INTO especies (espCodigo, espNombre) VALUES ('$this->Codigo', '$this->Nombre');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'La especie no puede ser registrada.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}		
		function Modificar(){
			$result = $this->DB->Query("SELECT espId FROM especies WHERE espId = $this->Id;");
			if (!$result){
				$this->Errores['Modificar'] = "La especie no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE especies SET espCodigo = '$this->Codigo', espNombre = '$this->Nombre' WHERE espId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "La especie no puede ser modificada."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la especie.'; return FALSE;
			}
			if ($this->DB->Query("SELECT valId FROM valores WHERE valEspecie = $this->Id;")){
				$this->Errores['Dependencias'] = 'Hay valores relaciones con esta especie. Debe eliminarlos para continuar.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM especies WHERE espId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la especie.'; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$this->clearEspecies();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM especies WHERE espId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'La especie no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['espId'];
			$this->Codigo = $datos['espCodigo'];
			$this->Nombre = $datos['espNombre'];

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