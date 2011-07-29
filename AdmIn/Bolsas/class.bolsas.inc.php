<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsBolsas{
		var $Id = 0;
		var $Codigo = '';
		var $Nombre = '';
		var $DesdeH = '';
		var $DesdeM = '';
		var $HastaH = '';
		var $HastaM = '';
		var $Lapso  = '';
		var $Errores= array();
		var $DB;

		function clsBolsas(){
			$this->clearBolsas();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearBolsas(){
			$this->Id = 0;
			$this->Codigo = '';
			$this->Nombre = '';
			$this->DesdeH = '';
			$this->DesdeM = '';
			$this->HastaH = '';
			$this->HastaM = '';
			$this->Lapso  = '';
			$this->Errores= array();
		}
		function Validar(){
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if (!$this->Validar()){return FALSE;}

			$qryInsert = "INSERT INTO bolsas (bolCodigo, bolNombre, bolDesdeH, bolDesdeM, bolHastaH, bolHastaM, bolLapso) ".
						 "VALUES ('$this->Codigo', '$this->Nombre', '$this->DesdeH', '$this->DesdeM', '$this->HastaH', '$this->HastaM', '$this->Lapso');";
			$resInsert = $this->DB->Command($qryInsert);

			if (!$resInsert){
				$this->Errores['Registrar'] = 'La bolsa no puede ser registrada.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId(); return TRUE;
		}		
		function Modificar(){
			$result = $this->DB->Query("SELECT bolId FROM bolsas WHERE bolId = $this->Id;");
			if (!$result){
				$this->Errores['Modificar'] = "La bolsa no existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qryModificar = "UPDATE bolsas SET bolCodigo = '$this->Codigo', bolNombre = '$this->Nombre', ".
							"bolDesdeH = '$this->DesdeH', bolDesdeM = '$this->DesdeM', bolHastaH = '".
							"$this->HastaH', bolHastaM = '$this->HastaM', bolLapso = '$this->Lapso' WHERE bolId = $this->Id;";
			$resModificar = $this->DB->Command($qryModificar);

			if (!$resModificar){
				$this->Errores['Modificar'] = "La bolsa no puede ser modificada."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la bolsa.'; return FALSE;
			}
			if ($this->DB->Query("SELECT valId FROM valores WHERE valBolsa = $this->Id;")){
				$this->Errores['Dependencias'] = 'Hay valores relaciones con esta bolsa. Debe eliminarlos para continuar.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM bolsas WHERE bolId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar la bolsa.'; return FALSE;
			}
			return TRUE;
		}
		function findId($valor){
			$this->clearBolsas();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM bolsas WHERE bolId = $this->Id;");
			if (!$result){
				$this->Errores['Id'] = 'La bolsa no existe.'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['bolId'];
			$this->Codigo = $datos['bolCodigo'];
			$this->Nombre = $datos['bolNombre'];
			$this->DesdeH = $datos['bolDesdeH'];
			$this->DesdeM = $datos['bolDesdeM'];
			$this->HastaH = $datos['bolHastaH'];
			$this->HastaM = $datos['bolHastaM'];
			$this->Lapso  = $datos['bolLapso'];

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
		function setDesdeH($valor){$this->DesdeH = $valor;}
		function getDesdeH(){return $this->DesdeH;}

		function setDesdeM($valor){$this->DesdeM = $valor;}
		function getDesdeM(){return $this->DesdeM;}
//----------------------------------------------------------
		function setHastaH($valor){$this->HastaH = $valor;}
		function getHastaH(){return $this->HastaH;}
		
		function setHastaM($valor){$this->HastaM = $valor;}
		function getHastaM(){return $this->HastaM;}
//----------------------------------------------------------
		function setLapso($valor){$this->Lapso = $valor;}
		function getLapso(){return $this->Lapso;}
//----------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
	}
?>