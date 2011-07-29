<?php
	include_once '../cOmmOns/config.inc.php';
	require_once getLocal('USU').'class.usuarios.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';
/*-------------------------------------------------------------------*/
	function caracter_aleatorio() {
		mt_srand((double)microtime()*1000000);
		$valor_aleatorio = mt_rand(1,3);

		switch ($valor_aleatorio) {
		    case 1: $valor_aleatorio = mt_rand(97, 122); break;
		    case 2: $valor_aleatorio = mt_rand(48, 57); break;
		    case 3: $valor_aleatorio = mt_rand(65, 90);
		}
		return chr($valor_aleatorio);
	}
/*-------------------------------------------------------------------*/
	$stERROR = '';
	$stPERSONAL = '';
	$stFECHA = '01/01/1980';

	if (isset($_POST['btn_action'])){
		$stPERSONAL = $_POST['personal'];
		$stFECHA = $_POST['fecha'];

		if (strtoupper($_POST['verificar']) == strtoupper($_SESSION['captcha_texto_session'])){
			$oUsuario = new clsUsuarios();
			$oUsuario->clearErrores();

			$oUsuario->checkIdentidad($stPERSONAL,$stFECHA,getLocal('COMMONS'));
			if (!$oUsuario->hasErrores()){
				$oSmarty->assign ('stTITLE', 'Contraseña enviada');
				$oSmarty->assign ('stLINKS', array(array('desc'=>'Login','link'=>'index.php')));
				$oSmarty->assign ('stMESSAGE', 'La nueva contraseña fue enviada con éxito.');
				$oSmarty->display('information.tpl.html');

				unset($_SESSION['captcha_texto_session']); exit();
			} else {
				$stERROR = $oUsuario->getErrores();
			}
		} else {
			$stERROR = 'El código ingresado no es válido.';
		}
	}
/*--------------------------------------------------------------------------------------------------------------------*/
	$captcha_texto = "";
	for ($i = 1; $i <= 6; $i++) {
		$captcha_texto .= caracter_aleatorio();
	}
	$_SESSION["captcha_texto_session"] = $captcha_texto;
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stERROR', $stERROR);
	$oSmarty->assign('stEMAIL', $stPERSONAL);
	$oSmarty->assign('stFECHA', $stFECHA);
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stHORA_ACTUAL', date('H:i:s'));
	$oSmarty->assign('stEN_LINEA'   , !empty($_SESSION['en_linea']) ? TRUE : FALSE);
	$oSmarty->assign('stDIA_ACTUAL' , diaSemana(date('w')).', '.date('d').' de '.nombresMes(date('m')).' de '.date('Y'));
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->display('usu.recordar.tpl.html');
?>