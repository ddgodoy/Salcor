<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsPaises{
		var $Id = 0;
		var $Codigo = '';
		var $Nombre = '';
		var $Errores= array();
		var $DB;

		function clsPaises(){
			$this->clearPaises();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearPaises(){
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

			$qryInsert = "INSERT INTO paises (paiCodigo, paiNombre) VALUES ('$this->Codigo', '$this->Nombre');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'El pa&iacute;s no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}		
		function Modificar(){
			$result = $this->DB->Query("SELECT paiId FROM paises WHERE paiId = $this->Id;");
			if (!$result){
				$this->Errores['Modificar'] = "El pa&iacute;s no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE paises SET paiCodigo = '$this->Codigo', paiNombre = '$this->Nombre' WHERE paiId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "El pa&iacute;s no puede ser modificado."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el pa&iacute;s.'; return FALSE;
			}
			if ($this->DB->Query("SELECT valId FROM valores WHERE valPais = $this->Id;")){
				$this->Errores['Dependencias'] = 'Hay valores relaciones con este pais. Debe eliminarlos para continuar.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM paises WHERE paiId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el pa&iacute;s.'; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$this->clearPaises();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM paises WHERE paiId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'El pa&iacute;s no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['paiId'];
			$this->Codigo = $datos['paiCodigo'];
			$this->Nombre = $datos['paiNombre'];

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