<?php
	require_once getLocal('ADMIN').'sesion_admin.inc.php';

	$contenido[] = array('Desc'=>'Cartera','Link'=>'');
	$contenido[] = array('Desc'=>'Listar valores' ,'Link'=>getWeb('VAL').'val.listar.php');
	$contenido[] = array('Desc'=>'Cargar un valor','Link'=>getWeb('VAL').'val.registrar.php');
	$contenido[] = array('Desc'=>'Rango de Compra','Link'=>getWeb('RIE').'rie.listar.php');
	$contenido[] = array('Desc'=>'Rent. Esperada' ,'Link'=>getWeb('TAS').'tas.listar.php');
	$contenido[] = array('Desc'=>'Divisores'      ,'Link'=>getWeb('DIV').'div.listar.php');
	$contenido[] = array('Desc'=>'An&aacute;lisis','Link'=>'');
	$contenido[] = array('Desc'=>'De Compra'      ,'Link'=>getWeb('CMP').'cmp.listar.php');
	$contenido[] = array('Desc'=>'De Rentabilidad','Link'=>getWeb('REN').'ren.listar.php');
	$contenido[] = array('Desc'=>'De Operaciones' ,'Link'=>getWeb('OPE').'ope.listar.php');
	$contenido[] = array('Desc'=>'Reportes'       ,'Link'=>getWeb('REP').'rep.inicio.php');
	$contenido[] = array('Desc'=>'Sugeridos'      ,'Link'=>getWeb('SUG').'sug.listar.php');

	if ($_SESSION['_usuLogin']==SUPER_ADMIN){
		$contenido[] = array('Desc'=>'Tablas','Link'=>'');
		$contenido[] = array('Desc'=>'Bolsas'    ,'Link'=>getWeb('BOL').'bol.listar.php');
		$contenido[] = array('Desc'=>'Especies'  ,'Link'=>getWeb('ESP').'esp.listar.php');
		$contenido[] = array('Desc'=>'Monedas'   ,'Link'=>getWeb('MON').'mon.listar.php');
		$contenido[] = array('Desc'=>'Pa&iacute;ses','Link'=>getWeb('PAI').'pai.listar.php');
		$contenido[] = array('Desc'=>'Sectores'  ,'Link'=>getWeb('SEC').'sec.listar.php');
		$contenido[] = array('Desc'=>'Control'   ,'Link'=>'');
		$contenido[] = array('Desc'=>'Usuarios'  ,'Link'=>getWeb('USU').'usu.listar.php');
		$contenido[] = array('Desc'=>'Web Mail'  ,'Link'=>'http://www.salcor.com.ar/webmail');
		$contenido[] = array('Desc'=>'Admin. Salcor'   ,'Link'=>'http://www.salcor.com.ar/hcm');
		
	}
	$oSmarty->assign('stCONTENIDO', $contenido);
	$oSmarty->assign('stUSUARIO_ACTIVO', $_SESSION['_usuNombre']);
	$oSmarty->assign('stUSUARIO_LINK_DATOS', getWeb('ADMIN').'misdatos.php?Id='.$_SESSION['_usuId']);
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stHORA_ACTUAL', date('H:i:s'));
	$oSmarty->assign('stEN_LINEA'   , !empty($_SESSION['en_linea']) ? TRUE : FALSE);
	$oSmarty->assign('stDIA_ACTUAL' , diaSemana(date('w')).', '.date('d').' de '.nombresMes(date('m')).' de '.date('Y'));
/*--------------------------------------------------------------------------------------------------------------------*/
?>