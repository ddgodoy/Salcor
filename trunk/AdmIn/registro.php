<?php
	include_once '../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';

	$stLOGIN   = '';
	$stNOMBRE  = '';
	$stAPELLIDO= '';
	$stPERSONAL= '';
	$stLABORAL = '';
	$stCELULAR = '';
	$stFECNAC  = '01/01/1980';
	$stERROR   = '';

	if (isset($_POST['btn_accion'])){
		require_once getLocal('USU').'class.usuarios.inc.php';

		$stLOGIN   = $_POST['login'];
		$stNOMBRE  = $_POST['nombre'];
		$stAPELLIDO= $_POST['apellido'];
		$stPERSONAL= $_POST['personal'];
		$stLABORAL = $_POST['laboral'];
		$stCELULAR = $_POST['celular'];
		$stFECNAC  = $_POST['fecnac'];
		$stENABLED = 'N';

		$password  = $_POST['password'];
		$confirma  = $_POST['confirma'];

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();

		$oUsuario->setLogin   ($stLOGIN);
		$oUsuario->setNombre  ($stNOMBRE);
		$oUsuario->setApellido($stAPELLIDO);
		$oUsuario->setPersonal($stPERSONAL);
		$oUsuario->setLaboral ($stLABORAL);
		$oUsuario->setCelular ($stCELULAR);
		$oUsuario->setFecnac  ($stFECNAC);
		$oUsuario->setEnabled ($stENABLED);

		$oUsuario->setPassword($password);
		$oUsuario->setConfirma($confirma);
		
		if (!$oUsuario->hasErrores() && $oUsuario->Registrar()){
			require_once getLocal('COMMONS').'class.mysql.inc.php';
			require_once getLocal('COMMONS').'func.mail.php';

			$oMyDB = new clsMyDB();

			/*generacion de un codigo para validar la activacion-------------------*/
			$codigo_activacion = uniqid('');
			$oMyDB->Command("INSERT INTO usuarios_tmp (tmpUsuario, tmpCodigo) VALUES ($oUsuario->Id,'$codigo_activacion');");

			/*email al administrador del sitio-------------------------------------*/			
			$texto = 'SALCOR Stock Manager - Nuevo Usuario'."\n"."\n".
					 'Login: '.$stLOGIN."\n".
					 'Nombre: '.$stNOMBRE."\n".
					 'Apellido: '.$stAPELLIDO."\n".
					 'Email Personal: '.$stPERSONAL."\n".
					 'Email Laboral: '.$stLABORAL."\n".
					 'Telefonos: '.$stCELULAR;
			$html = "<html>
					<head>
					<title>SALCOR Stock Manager - Nuevo Usuario</title>
					<style type='text/css'>
						body{background-color: #E8ECF0;}
						td {font-family: arial;font-size: 11px;}
					</style>
					</head>
					<body>
						<table>
							<tr><td colspan='2'><h1>SALCOR Stock Manager -  Nuevo Usuario</h1></td></tr>
							<tr><td><strong>Login:</strong>&nbsp;</td><td>$stLOGIN</td></tr>
							<tr><td><strong>Nombre:</strong>&nbsp;</td><td>$stNOMBRE</td></tr>
							<tr><td><strong>Apellido:</strong>&nbsp;</td><td>$stAPELLIDO</td></tr>
							<tr><td><strong>Email Personal:</strong>&nbsp;</td><td>$stPERSONAL</td></tr>
							<tr><td><strong>Email Laboral:</strong>&nbsp;</td><td>$stLABORAL</td></tr>
							<tr><td><strong>Telefonos:</strong>&nbsp;</td><td>$stCELULAR</td></tr>
						</table>
					</body>
					</html>";

			$stEMAIL_ADMINISTRADOR = 'info@salcor.com.ar';
			$rsAdm = $oMyDB->Query("SELECT usuPersonal FROM usuarios WHERE usuLogin = 'admin';");
			if ($rsAdm){
				$dtAdm = mysql_fetch_assoc($rsAdm);
				$stEMAIL_ADMINISTRADOR = $oMyDB->forShow($dtAdm['usuPersonal']);
			}
			send_mail($stEMAIL_ADMINISTRADOR, 'info@salcor.com.ar', 'SALCOR Stock Manager - Nuevo Usuario', $html, $texto);

			/*email al usuario para completar la activacion------------------------*/
			$texto = 'SALCOR Stock Manager - Activación del Registro'."\n"."\n".
					 'Para completar la activación, copie el siguiente enlace'."\n".
					 'y péguelo en la barra de direcciones de su navegador:'."\n"."\n".
					 'http://www.salcor.com.ar/AdmIn/activar_usuario.php?codigo='.$codigo_activacion;
			$html = "<html>
					<head>
					<title>SALCOR Stock Manager - Activación del Registro</title>
					<style type='text/css'>
						body{background-color: #E8ECF0;}
						td {font-family: arial;font-size: 11px;}
					</style>
					</head>
					<body>
						<table>
						<tr><td colspan='2'><h1>SALCOR Stock Manager -  Activación del Registro</h1></td></tr>
						<tr><td>Use el siguiente enlace para activar el registro:</td></tr>
						<tr><td>
						<a href='http://www.salcor.com.ar/AdmIn/activar_usuario.php?codigo=".$codigo_activacion."' target='_blank'>
							Activar
						</a>
						</td></tr>
						<tr><td>También puede completar este paso copiando y pegando este link en la barra de direcciones de su navegador:</td></tr>
						<tr><td>http://www.salcor.com.ar/AdmIn/activar_usuario.php?codigo=".$codigo_activacion."</td></tr>
						</table>
					</body>
					</html>";
			send_mail($stPERSONAL, 'info@salcor.com.ar', 'SALCOR Stock Manager - Activar registro', $html, $texto);
			/*---------------------------------------------------------------------*/
			$stMENSAJE_REGISTRO = 'Sus datos se han registrado correctamente.<br />'.
								  'Un email ha sido enviado a la direcci&oacute;n proporcionada en este formulario.<br />'.
								  'Siga las indicaciones que ah&iacute; se detallan para completar la activaci&oacute;n.';
			$oSmarty->assign ('stTITLE', 'Registro de Usuarios');
			$oSmarty->assign ('stMESSAGE', $stMENSAJE_REGISTRO);
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stERROR = $oUsuario->getErrores();
	}
	$oSmarty->assign('stERROR'   , $stERROR);
	$oSmarty->assign('stLOGIN'   , $stLOGIN);
	$oSmarty->assign('stNOMBRE'  , $stNOMBRE);
	$oSmarty->assign('stAPELLIDO', $stAPELLIDO);
	$oSmarty->assign('stPERSONAL', $stPERSONAL);
	$oSmarty->assign('stLABORAL' , $stLABORAL);
	$oSmarty->assign('stCELULAR' , $stCELULAR);
	$oSmarty->assign('stFECNAC'  , $stFECNAC);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo usuario');
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stHORA_ACTUAL', date('H:i:s'));
	$oSmarty->assign('stEN_LINEA'   , !empty($_SESSION['en_linea']) ? TRUE : FALSE);
	$oSmarty->assign('stDIA_ACTUAL' , diaSemana(date('w')).', '.date('d').' de '.nombresMes(date('m')).' de '.date('Y'));
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->display('registro.tpl.html');
?>