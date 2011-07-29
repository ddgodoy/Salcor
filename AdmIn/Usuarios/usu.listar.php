<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';
	require_once getLocal('COMMONS').'class.paginado.inc.php';

	$oMyDB  = new clsMyDB();
	$aOrder = array('login'=>'usuLogin', 'apellido'=>'usuApellido', 'personal'=>'usuPersonal', 'habilitado'=>'usuEnabled');
	$stORD  = 'login';
	$stSNT  = 'ASC';
	$stTEXT = '';
	$stFILT = '';
//ordenamiento
	if (!empty($_REQUEST['orden'])){$stORD = $_REQUEST['orden']; $stSNT = $_REQUEST['sentido']=='ASC'?'DESC':'ASC';}
//busqueda
	if(!empty($_REQUEST['texto'])){
		$stTEXT = $_REQUEST['texto'];
		$stFILT.= " AND (usuNombre LIKE '$stTEXT%' || usuApellido LIKE '$stTEXT%') ";
	}
	$QUERY = "SELECT * FROM usuarios WHERE usuLogin != 'admin' $stFILT ORDER BY $aOrder[$stORD] $stSNT";
	$PAGE  = (isset($_GET['page']))?(integer)$_GET['page']:1;
// paginado
	$oPaginado = new clsPaginadoMySQL($QUERY, $oMyDB, $PAGE, 20);
	$oPaginado->doPaginar();

	if ($oPaginado->getCantidadRegistros() == 0){
		$oSmarty->assign ('stPREFIJO_ACCION', 'usu');
		$oSmarty->assign ('stTITLE', 'Listado de usuarios');
		$oSmarty->assign ('stLINKS', array(array('desc'=>'<b>Nuevo usuario</b>', 'link'=>'usu.registrar.php')));
		$oSmarty->assign ('stMESSAGE', 'No hay usuarios registrados.');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$result = &$oPaginado->registros;
// resultados
	while ($result && $fila = mysql_fetch_assoc($result)){
		$Id = $fila['usuId'];
		$aUsuarios[$Id]['Login'] = $oMyDB->forShow($fila['usuLogin']);
		$aUsuarios[$Id]['Nombre'] = $oMyDB->forShow($fila['usuApellido'].', '.$fila['usuNombre']);
		$aUsuarios[$Id]['Enabled'] = ($fila['usuEnabled']=='S')?'Habilitado':'Inhabilitado';
		$aUsuarios[$Id]['Personal'] = $oMyDB->forShow($fila['usuPersonal']);
	}
	$oPaginado->removeParametro('orden');
	$oPaginado->removeParametro('sentido');

	$oSmarty->assign_by_ref('stUSUARIOS', $aUsuarios);
	$oSmarty->assign('stTEXT', $stTEXT);
	$oSmarty->assign('stSNT' , $stSNT);

	$oSmarty->assign('stREG_TOTAL' , $oPaginado->getCantidadRegistros());
	$oSmarty->assign('stPARAMETROS', $oPaginado->parametros);
	$oSmarty->assign('stLINKS', $oPaginado->links);
	$oSmarty->assign('stPAGES', $oPaginado->linksPaginas);
	$oSmarty->assign('stPAGE' , $oPaginado->getPaginaActual());
	$oSmarty->assign('stTITLE', 'Listado de usuarios');
	
	$oSmarty->display('usu.listar.tpl.html');
?>