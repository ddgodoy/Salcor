<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('CAPTCHA').'class.captcha.inc.php';

	$oCaptcha = new clsCaptcha();
	$oCaptcha->setDirectorio(getLocal('CAPTCHA').'image_data');
	$oCaptcha->setFuente(getLocal('CAPTCHA').'elephant.ttf');
	$oCaptcha->show();
?>