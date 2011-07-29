<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('PAI').'class.paises.inc.php';

	$stCODIGO = '';
	$stNOMBRE = '';
	$stERROR  = '';

	if (isset($_POST['btn_accion'])){
		$stCODIGO = $_POST['codigo'];
		$stNOMBRE = $_POST['nombre'];

		$oPais = new clsPaises();
		$oPais->clearErrores();

		$oPais->setCodigo($stCODIGO);
		$oPais->setNombre($stNOMBRE);

		if (!$oPais->hasErrores() && $oPais->Registrar()){
			redireccionar(getWeb('PAI').'pai.listar.php');
		}
		$stERROR = $oPais->getErrores();
	}
	$oSmarty->assign('stERROR' , $stERROR);
	$oSmarty->assign('stCODIGO', $stCODIGO);
	$oSmarty->assign('stNOMBRE', $stNOMBRE);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo pa&iacute;s');
	$oSmarty->assign('stBTN_ACTION', 'Nuevo');

	$oSmarty->display('pai.registrar.tpl.html');
?>