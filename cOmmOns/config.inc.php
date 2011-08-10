<?php
	session_start();

	if (strtolower($_SERVER['SERVER_NAME']) == 'localhost') {
		$DEBUG = TRUE;
		$SMARTY_DEBUG = FALSE;
		define('SMARTY_DIR', $_SERVER['DOCUMENT_ROOT'].'/_Smarty/libs/');
	
		$_PATH['ROOT'] = $_SERVER['DOCUMENT_ROOT'].'/salcor2/';
		$_PATH['WEB'] = 'http://localhost/salcor2/';
	} else {
		$DEBUG = FALSE;
		$SMARTY_DEBUG = FALSE;
		define('SMARTY_DIR', '/var/www/vhosts/salcor.com.ar/httpdocs/_Smarty/libs/');
	
		$_PATH['ROOT'] = '/var/www/vhosts/salcor.com.ar/httpdocs/';
		$_PATH['WEB'] = 'http://www.salcor.com.ar/';
	}
  define('SUPER_ADMIN', 'admin');

	if ($DEBUG){error_reporting(E_ALL);} else {error_reporting(0);}
/*----------------------------------------------------------------------------------------------------------*/
	$_PATH['COMMONS'] = 'cOmmOns/';
	$_PATH['IMAGES']  = $_PATH['COMMONS'].'images/';
	$_PATH['JSCRIPT'] = $_PATH['COMMONS'].'jscript/';
	$_PATH['CAPTCHA'] = $_PATH['COMMONS'].'captcha/';
	$_PATH['ESTILO']  = $_PATH['COMMONS'].'estilo/';
	$_PATH['CALENDR'] = $_PATH['COMMONS'].'calendar/';
	$_PATH['FINANCE'] = $_PATH['COMMONS'].'yahoo_finance/';

	$_PATH['SMARTY'] = '_Smarty/libs/';
	$_PATH['TEMPLATES'] = 'templates/';
	$_PATH['TEMPLATES_C'] = 'templates_c/';

	$_PATH['ADMIN'] = 'AdmIn/';
	$_PATH['BOL'] = $_PATH['ADMIN'].'Bolsas/';
	$_PATH['CMP'] = $_PATH['ADMIN'].'Compras/';
	$_PATH['DIV'] = $_PATH['ADMIN'].'Divisores/';
	$_PATH['ESP'] = $_PATH['ADMIN'].'Especies/';
	$_PATH['MON'] = $_PATH['ADMIN'].'Monedas/';
	$_PATH['OPE'] = $_PATH['ADMIN'].'Operaciones/';
	$_PATH['PAI'] = $_PATH['ADMIN'].'Paises/';
	$_PATH['PRI'] = $_PATH['ADMIN'].'Principios/';
	$_PATH['REN'] = $_PATH['ADMIN'].'Rentabilidad/';
	$_PATH['RIE'] = $_PATH['ADMIN'].'Riesgo/';
	$_PATH['SEC'] = $_PATH['ADMIN'].'Sectores/';
	$_PATH['TAS'] = $_PATH['ADMIN'].'Tasas/';
	$_PATH['USU'] = $_PATH['ADMIN'].'Usuarios/';
	$_PATH['VAL'] = $_PATH['ADMIN'].'Valores/';
	$_PATH['REP'] = $_PATH['ADMIN'].'Reportes/';
	$_PATH['SUG'] = $_PATH['ADMIN'].'Sugeridos/';
/*----------------------------------------------------------------------------------------------------------*/
	require_once(SMARTY_DIR."Smarty.class.php");

	$oSmarty = new Smarty();
	$oSmarty->debugging    = $SMARTY_DEBUG;
	$oSmarty->compile_check= !$SMARTY_DEBUG;
	$oSmarty->template_dir = getLocal('TEMPLATES');
	$oSmarty->compile_dir  = getLocal('TEMPLATES_C');

	$oSmarty->assign('stWEBHOME', $_PATH['WEB']);
	$oSmarty->assign('stRUTAS', getAllWeb());
/*----------------------------------------------------------------------------------------------------------*/
	function getAllWeb(){
		global $_PATH;
		foreach($_PATH as $key => $value){
			$newKey = strtolower($key);
			if ($key=='root'||$key=='web'){$RUTAS[$newKey] = $value;} else {$RUTAS[$newKey] = $_PATH['WEB'].$value;}
		}
		return $RUTAS;
	}
	function getAllLocal(){
		global $_PATH;
		foreach($_PATH as $key => $value){
			$newKey = strtolower($key);
			if ($key=='root'||$key=='web'){$RUTAS[$newKey] = $value;} else {$RUTAS[$newKey] = $_PATH['ROOT'].$value;}
		}
		return $RUTAS;
	}
	function getWeb($que){
		global $_PATH;
		$que = strtoupper($que);
		if ($que=='ROOT'||$que=='WEB'){return $_PATH[$que];}
		if (isset($_PATH[$que])){return $_PATH['WEB'].$_PATH[$que];} else {return "";}
	}
	function getLocal($que){
		global $_PATH;
		$que = strtoupper($que);
		if ($que=='ROOT'||$que=='WEB'){return $_PATH[$que];}
		if (isset($_PATH[$que])){return $_PATH['ROOT'].$_PATH[$que];} else {return "";}
	}
/*----------------------------------------------------------------------------------------------------------*/
	if (!isset($_SESSION['en_linea'])){
		if (@fopen('http://ar.finance.yahoo.com/','r')){
			$_SESSION['en_linea'] = 'ok';
		}
	}
?>