<?php
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	class clsUsuarios{
		var $Id = 0;
		var $Login   = '';
		var $Password= '';
		var $Confirma= '';
		var $Nombre  = '';
		var $Apellido= '';
		var $Personal= '';
		var $Laboral = '';
		var $Celular = '';
		var $Fecnac  = '';
		var $Enabled = '';
		var $Alerta  = '';
		var $Errores = array();
		var $DB;

		function clsUsuarios(){
			$this->clearUsuarios();
			$this->DB = new clsMyDB();

			if ($this->DB->hasErrores()){$this->Errores = $this->DB->Errores;}
		}
		function clearUsuarios(){
			$this->Id = 0;
			$this->Login   = '';
			$this->Password= '';
			$this->Confirma= '';
			$this->Nombre  = '';
			$this->Apellido= '';
			$this->Personal= '';
			$this->Laboral = '';
			$this->Celular = '';
			$this->Fecnac  = '';
			$this->Enabled = '';
			$this->Alerta  = '';
			$this->Errores = array();
		}
		function Validar(){
			if ($this->Id == 0){
				if ($this->Confirma != $this->Password){$this->Errores['Confirmacion'] = 'La contraseña no coincide con su confirmación.';}
			}
			if (empty($this->Errores)){return TRUE;} else {return FALSE;}
		}
		function Registrar(){
			if ($this->DB->Query("SELECT usuId FROM usuarios WHERE usuLogin = '$this->Login';")){
				$this->Errores['Login'] = 'El usuario ya existe.';
			}
			if (!$this->Validar()){return FALSE;}

			$qInsert = "INSERT INTO usuarios (usuLogin, usuPassword, usuNombre, usuApellido, usuPersonal, usuLaboral, ".
					   "usuCelular, usuFecnac, usuEnabled, usuAlerta) VALUES ('$this->Login', password('$this->Password'), ".
					   "'$this->Nombre', '$this->Apellido', '$this->Personal', '$this->Laboral', '$this->Celular', '".
					   "$this->Fecnac', '$this->Enabled', '$this->Alerta');";
			$rInsert = $this->DB->Command($qInsert);

			if (!$rInsert){
				$this->Errores['Registrar'] = 'El usuario no puede ser registrado.'; return FALSE;
			}
			$this->Id = $this->DB->InsertId();
			return TRUE;
		}
		function Modificar(){
			if (!$this->DB->Query("SELECT usuId FROM usuarios WHERE usuId = $this->Id;")){
				$this->Errores['Modificar'] = "El usuario que quiere modificar no existe!";
			}
			if ($this->DB->Query("SELECT usuId FROM usuarios WHERE usuId <> $this->Id AND usuLogin = '$this->Login';")){
				$this->Errores['Login'] = "El usuario ya existe.";
			}
			if (!$this->Validar()){return FALSE;}

			$qModificar = "UPDATE usuarios SET usuLogin = '$this->Login', usuNombre = '$this->Nombre', usuApellido = '".
						  "$this->Apellido', usuPersonal = '$this->Personal', usuLaboral = '$this->Laboral', usuCelular ".
						  "= '$this->Celular', usuFecnac = '$this->Fecnac', usuEnabled = '$this->Enabled', usuAlerta = '".
						  "$this->Alerta' WHERE usuId = $this->Id;";
			$rModificar = $this->DB->Command($qModificar);
			
			if (!$rModificar){
				$this->Errores['Modificar'] = "Los datos del usuario no se modificaron."; return FALSE;
			}
			return TRUE;
		}
		function Borrar(){
			if ($this->Id <= 0){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el usuario.'; return FALSE;
			}
			if (!$this->DB->Command("DELETE FROM usuarios WHERE usuId = $this->Id;")){
				$this->Errores['Borrar'] = 'Error al tratar de eliminar el usuario.'; return FALSE;
			}
			$this->DB->Command("DELETE FROM divisores WHERE divUsuario = $this->Id;");
			$this->DB->Command("DELETE FROM riesgo WHERE rieUsuario = $this->Id;");
			$this->DB->Command("DELETE FROM tasas WHERE tasUsuario = $this->Id;");
			$this->DB->Command("DELETE FROM usuarios_tmp WHERE tmpUsuario = $this->Id;");

			$res1 = $this->DB->Query("SELECT valId FROM valores WHERE valUsuario = $this->Id;");
			while ($res1 && $f1 = mysql_fetch_assoc($res1)){
				$id_valor = $f1['valId'];

				$this->DB->Command("DELETE FROM compras WHERE cmpValor = $id_valor;");
				$this->DB->Command("DELETE FROM ventas WHERE venValor = $id_valor;");
			}
			$this->DB->Command("DELETE FROM valores WHERE valUsuario = $this->Id;");

			return TRUE;
		}
		function changePassword($old, $new, $confirm){
			if (empty($new) || empty($confirm) || $new != $confirm){
				$this->Errores['Password'] = 'La nueva contraseña no coincide con su confirmación.'; return FALSE;
			}
			$qLogin = "SELECT usuId FROM usuarios WHERE usuLogin = '$this->Login' AND usuPassword = password('$old');";
			if (!$this->DB->Query($qLogin)){
				$this->Errores['Login'] = 'La contraseña ingresada es incorrecta.'; return FALSE;
			}
			$result = $this->DB->Command("UPDATE usuarios SET usuPassword = password('$new') WHERE usuLogin = '$this->Login'");
			if (!$result){
				$this->Errores['Password'] = 'La contraseña no puede cambiarse.'; return FALSE;
			}
			return TRUE;
		}
		function changePasswordDirect($new, $confirm){
			if (empty($new) || empty($confirm) || $new != $confirm){
				$this->Errores['Password'] = 'La nueva contraseña no coincide con su confirmación.'; return FALSE;
			}
			$this->setPassword($new);
			if (empty($this->Errores)){
				$result = $this->DB->Command("UPDATE usuarios SET usuPassword = password('$this->Password') WHERE usuLogin = '$this->Login'");
				if (!$result){
					$this->Errores['Password'] = 'La contraseña no puede cambiarse.'; return FALSE;
				}
				return TRUE;
			} else {return FALSE;}
		}
		function findId($valor){
			$this->clearUsuarios();
			$this->Id = (integer) $valor;

			if ($this->Id <= 0){
				$this->Errores['Id'] = 'Error interno.'; return FALSE;
			}
			$result = $this->DB->Query("SELECT * FROM usuarios WHERE usuId = $this->Id;");			
			if (!$result){
				$this->Errores['Id'] = 'El usuario no existe!'; return FALSE;
			}
			$datos = mysql_fetch_assoc($result);

			$this->Id = $datos['usuId'];
			$this->Login   = $datos['usuLogin'];
			$this->Password= $datos['usuPassword'];
			$this->Confirma= $datos['usuPassword'];
			$this->Nombre  = $datos['usuNombre'];
			$this->Apellido= $datos['usuApellido'];
			$this->Personal= $datos['usuPersonal'];
			$this->Laboral = $datos['usuLaboral'];
			$this->Celular = $datos['usuCelular'];
			$this->Fecnac  = $datos['usuFecnac'];
			$this->Enabled = $datos['usuEnabled'];
			$this->Alerta  = $datos['usuAlerta'];

			return TRUE;
		}
//---------------------------------------------------------
		function checkIdentidad($email, $fecha, $ruta){
			$fecha = $this->DB->verifyDate($fecha);
			$s_adm = "SELECT usuId as cod FROM usuarios WHERE usuPersonal = '$email' AND usuFecnac = '$fecha';";
			$r_adm = $this->DB->Query($s_adm);

			if ($r_adm){
				$a_persona = mysql_fetch_assoc($r_adm);
			} else {
				$this->Errores['Nueva_Contrasenia'] = 'Los datos ingresados no son correctos.';
			}
			if (!isset($this->Errores['Nueva_Contrasenia'])){
				$nueva_password = time();
				$id_persona = $a_persona['cod'];

				if ($this->DB->Command("UPDATE usuarios SET usuPassword = password('$nueva_password') WHERE usuId = $id_persona;")){
					require_once $ruta.'func.mail.php';
					/*version html----------------------------------------*/
					$html = "<html>
							<head>
							<title>SALCOR - Nueva Password</title>
							<style type='text/css'>body{background-color: #E8ECF0;}td {font-family: arial;font-size: 11px;}</style>
							</head>
							<body>
								<table>
									<tr><td colspan='2'><h1>SALCOR - Nueva Password</h1></td></tr>
									<tr><td><strong>Su nueva password:</strong>&nbsp;</td><td>$nueva_password</td></tr>
								</table>
							</body>
							</html>";
					/*version texto plano---------------------------------*/
					$texto = 'SALCOR - Nueva Password'."\n"."\n".
							 'Su nueva password:'.$nueva_password;
					/*----------------------------------------------------*/
					if (send_mail($email, "info@salcor.com.ar", "SALCOR - Solicitud nueva password", $html, $texto)){
						$this->Errores['Envio'] = 'No fue posible realizar el envío. Por favor, intente más tarde.';
					}
				} else {$this->Errores['Nueva'] = 'Error durante el proceso.';}
			}
		}
//---------------------------------------------------------
		function clearErrores(){$this->Errores = array();}
		function hasErrores(){if (empty($this->Errores)){return FALSE;} else {return TRUE;}}
		function getErrores(){
			$error = '';
			foreach($this->Errores as $descripcion){$error .= $descripcion.'<br>';}
			return $error;
		}
//---------------------------------------------------------
		function setLogin($valor){
			if (trim($valor) != $valor){
				$this->Errores['Login'] = 'El Login no debe contener espacios al principio o al final.';
			} elseif (count(explode(" ", $valor))>1){
				$this->Errores['Login'] = 'El Login no debe contener espacios.';
			}
			$this->Login = strtolower($valor);
			if (empty($this->Login)){$this->Errores['Login'] = 'Ingrese su nombre de usuario.';}
		}
//---------------------------------------------------------
		function setPassword($valor){
			if (trim($valor) != $valor){
				$this->Errores['Password'] = 'La Contraseña no debe contener espacios al principio o al final.';
			} elseif (count(explode(" ", $valor))>1){
				$this->Errores['Password'] = 'La Contraseña no debe contener espacios.';
			} elseif (strlen($valor) < 5){
				$this->Errores['Password'] = 'La Contraseña debe tener al menos 5 caracteres.';
			}
			$this->Password = $valor;
			if (empty($this->Password)){$this->Errores['Password'] = 'Ingrese su Contraseña.';}
		}
//---------------------------------------------------------
		function setConfirma($valor){
			$this->Confirma = $valor;
			if (empty($this->Confirma)){$this->Errores['Confirmacion'] = 'Complete la confirmación.';}
		}
//---------------------------------------------------------
		function setNombre($valor){
			$this->Nombre = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Nombre'] = 'Complete el nombre.';
			}
		}
		function getNombre(){return $this->DB->forShow($this->Nombre);}
		function editNombre(){return $this->DB->forEdit($this->Nombre);}
//---------------------------------------------------------
		function setApellido($valor){
			$this->Apellido = $this->DB->forSave($valor);
			if (empty($valor)){
				$this->Errores['Apellido'] = 'Complete el apellido.';
			}
		}
		function getApellido(){return $this->DB->forShow($this->Apellido);}
		function editApellido(){return $this->DB->forEdit($this->Apellido);}
//----------------------------------------------------------
		function setPersonal($valor){
			$this->Personal = $this->DB->forSave($valor);
			$aValidacion = $this->DB->ValidarMail($valor);

			if ($aValidacion[0] == false){$this->Errores['Personal'] = $aValidacion[1];}
		}
		function getPersonal() {return $this->DB->forShow($this->Personal);}
		function editPersonal(){return $this->DB->forEdit($this->Personal);}
//----------------------------------------------------------
		function setLaboral($valor){
			$this->Laboral = $this->DB->forSave($valor);
			if (!empty($valor)){
				$aValidacion = $this->DB->ValidarMail($valor);
				if ($aValidacion[0] == false){$this->Errores['Laboral'] = $aValidacion[1];}
			}
		}
		function getLaboral() {return $this->DB->forShow($this->Laboral);}
		function editLaboral(){return $this->DB->forEdit($this->Laboral);}
//---------------------------------------------------------
		function setCelular($valor){$this->Celular = $this->DB->forSave($valor);}
		function getCelular() {return $this->DB->forShow($this->Celular);}
		function editCelular(){return $this->DB->forEdit($this->Celular);}
//----------------------------------------------------------
		function setFecnac($valor){$this->Fecnac = $this->DB->verifyDate($valor);}
		function getFecnac(){return $this->DB->forShow($this->Fecnac);}
		function editFecnac(){return $this->DB->convertDate($this->Fecnac);}
//---------------------------------------------------------
		function setEnabled($valor){
			$this->Enabled = $valor; if ($this->Enabled != 'S'){$this->Enabled = 'N';}
		}
//---------------------------------------------------------
		function setAlerta($valor){
			$this->Alerta = $valor; if ($this->Alerta != 'S'){$this->Alerta = 'N';}
		}
//---------------------------------------------------------
		function setId($valor){
			$this->Id = (integer) $valor;
			if (empty($this->Id)){$this->Errores['Id'] = 'Error interno.';}
		}
		function getId(){return $this->Id;}
//---------------------------------------------------------
		function doLogin($login, $password){
			$this->clearUsuarios();

			$this->setLogin($login);
			$this->setPassword($password);
			
			if ($this->hasErrores()){return FALSE;}

			$qLogin = "SELECT usuId, usuEnabled FROM usuarios WHERE usuLogin = '$this->Login' AND usuPassword = password('$this->Password');";			
			$rLogin = $this->DB->Query($qLogin);

			if (!$rLogin){
				$this->Errores['Login'] = 'Por favor, ingrese correctamente sus datos.'.mysql_error(); return FALSE;
			}
			$datos = mysql_fetch_assoc($rLogin);
			if ($datos['usuEnabled'] != 'S'){
				$this->Errores['Login'] = 'El usuario no está habilitado.'; return FALSE;
			}
			if (!$this->findId($datos['usuId'])){return FALSE;} return TRUE;
		}
	}
?>