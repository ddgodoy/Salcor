<?php
	include_once '../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';
	
	$stNOMBRE = '';
	$stEMAIL  = '';
	$stASUNTO = '';
	$stCOMENTARIO = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stNOMBRE = $_POST['nombre'];
		$stEMAIL  = $_POST['email'];
		$stASUNTO = $_POST['asunto'];
		$stCOMENTARIO = $_POST['comentario'];
		
		if (empty($stNOMBRE)){$stERROR .= 'Debe ingresar el nombre.<br />';}
		if (empty($stEMAIL)) {$stERROR .= 'Debe ingresar el email.<br />';}
		if (empty($stASUNTO)){$stERROR .= 'Debe ingresar el asunto.<br />';}
		if (empty($stCOMENTARIO)){$stERROR .= 'Debe ingresar el comentario.';}
		
		if (empty($stERROR)){
			require_once getLocal('COMMONS').'func.mail.php';

			$ok = true;
			if (!has_no_newlines($stNOMBRE)){$ok = false;}
			if (!has_no_newlines($stEMAIL)) {$ok = false;}
			if (!has_no_newlines($stASUNTO)){$ok = false;}
			if (!has_no_emailheaders($stCOMENTARIO)){$ok = false;}
			
			if ($ok){
				$texto = 'SALCOR Stock Manager - Contacto'."\n"."\n".
						 'Nombre: '.$stNOMBRE."\n".
						 'Email: '.$stEMAIL."\n".
						 'Asunto: '.$stASUNTO."\n".
						 'Comentario: '.$stCOMENTARIO;

				$stCOMENTARIO_HTML = nl2br($stCOMENTARIO);
				$html = "<html>
						<head>
						<title>SALCOR Stock Manager - Contacto</title>
						<style type='text/css'>
							body{background-color: #E8ECF0;}
							td {font-family: arial;font-size: 11px;}
						</style>
						</head>
						<body>
							<table>
								<tr><td colspan='2'><h1>SALCOR Stock Manager - Contacto</h1></td></tr>
								<tr><td><strong>Nombre:</strong>&nbsp;</td><td>$stNOMBRE</td></tr>
								<tr><td><strong>Email:</strong>&nbsp;</td><td>$stEMAIL</td></tr>
								<tr><td><strong>Asunto:</strong>&nbsp;</td><td>$stASUNTO</td></tr>
								<tr><td colspan='2'><strong>Comentario:</strong>&nbsp;</td></tr>
								<tr><td colspan='2'>$stCOMENTARIO_HTML</td></tr>
							</table>
						</body>
						</html>";
				if (send_mail("info@salcor.com.ar", $stEMAIL, 'SALCOR Stock Manager - Contacto', $html, $texto)){
					$oSmarty->assign ('stTITLE', 'Formulario de Contacto');
					$oSmarty->assign ('stMESSAGE', 'Su datos de contacto fueron enviados correctamente.');
					$oSmarty->display('information.tpl.html');
					exit();
				} else {
					$stERROR .= 'Los datos no fueron enviados. Por favor, intente mas tarde.';
				}
			} else {
				$stERROR .= 'Los datos ingresados contienen caracteres no válidos.';
			}
		}
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);
	$oSmarty->assign('stEMAIL' , $stEMAIL);
	$oSmarty->assign('stASUNTO', $stASUNTO);
	$oSmarty->assign('stCOMENTARIO', $stCOMENTARIO);
	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stHORA_ACTUAL', date('H:i:s'));
	$oSmarty->assign('stEN_LINEA'   , !empty($_SESSION['en_linea']) ? TRUE : FALSE);
	$oSmarty->assign('stDIA_ACTUAL' , diaSemana(date('w')).', '.date('d').' de '.nombresMes(date('m')).' de '.date('Y'));
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->display('usu.contacto.tpl.html');
?>