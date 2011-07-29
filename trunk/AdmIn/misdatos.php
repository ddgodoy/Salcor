<?php
	require_once '../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('USU').'class.usuarios.inc.php';

	$stID = 0;
	$stNOMBRE  = '';
	$stAPELLIDO= '';
	$stPERSONAL= '';
	$stLABORAL = '';
	$stCELULAR = '';
	$stFECNAC  = '';

	$newpassword = '';
	$confirmacion= '';
	$stERROR = '';	

	if (isset($_POST['btn_action'])){
		$stID = $_POST['id'];
		$stNOMBRE  = $_POST['nombre'];
		$stAPELLIDO= $_POST['apellido'];
		$stPERSONAL= $_POST['personal'];
		$stLABORAL = $_POST['laboral'];
		$stCELULAR = $_POST['celular'];
		$stFECNAC  = $_POST['fecnac'];

		$newpassword = !empty($_POST['newpassword'])?$_POST['newpassword']:'';
		$confirmacion= !empty($_POST['confirma'])?$_POST['confirma']:'';

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();

		if ($oUsuario->findId($stID)){
			$oUsuario->setNombre  ($stNOMBRE);
			$oUsuario->setApellido($stAPELLIDO);
			$oUsuario->setPersonal($stPERSONAL);
			$oUsuario->setLaboral ($stLABORAL);
			$oUsuario->setCelular ($stCELULAR);
			$oUsuario->setFecnac  ($stFECNAC);

			if ($newpassword!='' && $confirmacion!=''){
				$oUsuario->changePasswordDirect($newpassword, $confirmacion);
			}
			if (!$oUsuario->hasErrores() && $oUsuario->Modificar()){
				$oSmarty->assign ('stTITLE', 'Actualizar mis datos');
				$oSmarty->assign ('stMESSAGE', 'Sus datos se actualizaron correctamente.');
				$oSmarty->display('information.tpl.html');
				exit();
			}
		}
		$stERROR = $oUsuario->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();
		
		if (!$oUsuario->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Actualizar mis datos');
			$oSmarty->assign ('stMESSAGE', $oUsuario->getErrores());
			$oSmarty->display('information.tpl.html');
			exit();
		}
		$stNOMBRE  = $oUsuario->editNombre();
		$stAPELLIDO= $oUsuario->editApellido();
		$stPERSONAL= $oUsuario->editPersonal();
		$stLABORAL = $oUsuario->editLaboral();
		$stCELULAR = $oUsuario->editCelular();
		$stFECNAC  = $oUsuario->editFecnac();
	} else {
		$oSmarty->assign ('stTITLE'  , 'Actualizar mis datos');
		$oSmarty->assign ('stMESSAGE', 'No puede entrar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID' , $stID);
	$oSmarty->assign('stERROR'   , $stERROR);
	$oSmarty->assign('stNOMBRE'  , $stNOMBRE);
	$oSmarty->assign('stAPELLIDO', $stAPELLIDO);
	$oSmarty->assign('stPERSONAL', $stPERSONAL);
	$oSmarty->assign('stLABORAL' , $stLABORAL);
	$oSmarty->assign('stCELULAR' , $stCELULAR);
	$oSmarty->assign('stFECNAC'  , $stFECNAC);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Actualizar mis datos');

	$oSmarty->display('misdatos.tpl.html');
?>