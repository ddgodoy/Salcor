<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsPrincipios{
		var $Id = 0;
		var $Dicho = '';
		var $Explicacion = '';
		var $Errores = array();
		var $DB;

		function clsPrincipios(){
			$this->clearPrincipios();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearPrincipios(){
			$this->Id = 0;
			$this->Dicho = '';
			$this->Explicacion = '';
			$this->Errores = array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO principios (priDicho, priExplicacion) VALUES ('$this->Dicho', '$this->Explicacion');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'El principio no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId();
			return TRUE;
		}		
		function Modificar(){
			$result = $this->DB->Query("SELECT priId FROM principios WHERE priId = $this->Id;");
			if (!$result){
				$this->Errores['Modificar'] = "El principio no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE principios SET priDicho = '$this->Dicho', priExplicacion = '$this->Explicacion' WHERE priId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "El principio no puede ser modificado."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el principio.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM principios WHERE priId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el principio.'; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$this->clearPrincipios();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM principios WHERE priId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'El principio no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['priId'];
			$this->Dicho = $datos['priDicho'];
			$this->Explicacion = $datos['priExplicacion'];
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
		function setDicho($valor){
			$this->Dicho = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Dicho'] = 'Complete el dicho.';
			}
		}
		function getDicho(){return $this->DB->forShow($this->Dicho);}
		function editDicho(){return $this->DB->forEdit($this->Dicho);}
//----------------------------------------------------------
		function setExplicacion($valor){
			$this->Explicacion = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Explicacion'] = 'Complete la explicación.';
			}
		}
		function getExplicacion(){return $this->DB->forShow($this->Explicacion);}
		function editExplicacion(){return $this->DB->forEdit($this->Explicacion);}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
	}
?>