<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('ESP').'class.especies.inc.php';

	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oEspecie = new clsEspecies();
		$oEspecie->clearErrores();

		$oEspecie->setCodigo($stCODIGO);
		$oEspecie->setNombre($stNOMBRE);

		if (!$oEspecie->hasErrores() && $oEspecie->Registrar()){
			redireccionar(getWeb('ESP').'esp.listar.php');
		}
		$stERROR = $oEspecie->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nueva especie');
	$oSmarty->assign('stBTN_ACTION', 'Nueva');

	$oSmarty->display('esp.registrar.tpl.html');
?>