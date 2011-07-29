<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('USU').'class.usuarios.inc.php';

	$oMyDB = new clsMyDB();
	
	$stLOGIN   = '';
	$stNOMBRE  = '';
	$stAPELLIDO= '';
	$stPERSONAL= '';
	$stLABORAL = '';
	$stCELULAR = '';
	$stFECNAC  = '01/01/1980';
	$stENABLED = 'CHECKED';
	$stALERTA  = 'CHECKED';
	$stERROR   = '';

	if (isset($_POST['btn_accion'])){
		$stLOGIN   = $_POST['login'];
		$stNOMBRE  = $_POST['nombre'];
		$stAPELLIDO= $_POST['apellido'];
		$stPERSONAL= $_POST['personal'];
		$stLABORAL = $_POST['laboral'];
		$stCELULAR = $_POST['celular'];
		$stFECNAC  = $_POST['fecnac'];
		$stENABLED = isset($_POST['enabled'])?'S':'N';
		$stALERTA  = isset($_POST['alerta']) ?'S':'N';

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
		$oUsuario->setAlerta  ($stALERTA);

		$oUsuario->setPassword($password);
		$oUsuario->setConfirma($confirma);
		
		if (!$oUsuario->hasErrores() && $oUsuario->Registrar()){
			//
			if (!empty($_POST['clone_user'])){
				require_once getLocal('USU').'usu.clonar.php';
			}
			//
			redireccionar(getWeb('USU').'usu.listar.php');
		}
		$stERROR = $oUsuario->getErrores();
		$stENABLED = isset($_POST['enabled'])?'CHECKED':'';
	}
	$oSmarty->assign('stERROR'   , $stERROR);
	$oSmarty->assign('stLOGIN'   , $stLOGIN);
	$oSmarty->assign('stNOMBRE'  , $stNOMBRE);
	$oSmarty->assign('stAPELLIDO', $stAPELLIDO);
	$oSmarty->assign('stPERSONAL', $stPERSONAL);
	$oSmarty->assign('stLABORAL' , $stLABORAL);
	$oSmarty->assign('stCELULAR' , $stCELULAR);
	$oSmarty->assign('stFECNAC'  , $stFECNAC);
	$oSmarty->assign('stENABLED' , $stENABLED);
	$oSmarty->assign('stALERTA'  , $stALERTA);

	$oSmarty->assign('stBTN_ACTION', 'Nuevo');
	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Nuevo usuario');
	//
	$aUsers = array();
	$rUsers = $oMyDB->Query("SELECT usuId, usuNombre, usuApellido FROM usuarios ORDER BY usuApellido;");
	while ($rUsers && $dUsers = mysql_fetch_assoc($rUsers)){
		$aUsers[$dUsers['usuId']] = $oMyDB->forShow($dUsers['usuApellido'].', '.$dUsers['usuNombre']);
	}
	$oSmarty->assign('stAUSERS', $aUsers);
	//
	$oSmarty->display('usu.registrar.tpl.html');
?>