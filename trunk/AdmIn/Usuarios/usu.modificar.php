<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';
	require_once getLocal('USU').'class.usuarios.inc.php';

	$oMyDB = new clsMyDB();
	
	$stID = 0;
	$stNOMBRE  = '';
	$stAPELLIDO= '';
	$stPERSONAL= '';
	$stLABORAL = '';
	$stCELULAR = '';
	$stFECNAC  = '';
	$stENABLED = '';
	$stALERTA  = '';
	$newpassword = '';
	$confirmacion= '';
	$stERROR = '';	

	if (isset($_POST['btn_accion'])){
		$stID = $_POST['id'];
		$stNOMBRE  = $_POST['nombre'];
		$stAPELLIDO= $_POST['apellido'];
		$stPERSONAL= $_POST['personal'];
		$stLABORAL = $_POST['laboral'];
		$stCELULAR = $_POST['celular'];
		$stFECNAC  = $_POST['fecnac'];
		$stENABLED = isset($_POST['enabled'])?'S':'N';
		$stALERTA  = isset($_POST['alerta']) ?'S':'N';
		$newpassword = !empty($_POST['newpassword'])?$_POST['newpassword']:'';
		$confirmacion = !empty($_POST['confirma'])?$_POST['confirma']:'';

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();

		if ($oUsuario->findId($stID)){
			$oUsuario->setNombre  ($stNOMBRE);
			$oUsuario->setApellido($stAPELLIDO);
			$oUsuario->setPersonal($stPERSONAL);
			$oUsuario->setLaboral ($stLABORAL);
			$oUsuario->setCelular ($stCELULAR);
			$oUsuario->setFecnac  ($stFECNAC);
			$oUsuario->setEnabled ($stENABLED);
			$oUsuario->setAlerta  ($stALERTA);

			if ($newpassword!='' && $confirmacion!=''){
				$oUsuario->changePasswordDirect($newpassword, $confirmacion);
			}
			if (!$oUsuario->hasErrores() && $oUsuario->Modificar()){
				//
				if (!empty($_POST['clone_user'])){
					$rSuyos = $oMyDB->Query("SELECT valId FROM valores WHERE valUsuario = $oUsuario->Id;");
					if ($rSuyos){
						$sSuyos = '';
						while ($dSuyos = mysql_fetch_assoc($rSuyos)){
							$sSuyos .= $dSuyos['valId'].',';
						}
						$sSuyos = substr($sSuyos, 0, -1);
						$updFilter = "AND valId NOT IN ($sSuyos)";
					}
					require_once getLocal('USU').'usu.clonar.php';
				}
				//
				redireccionar(getWeb('USU').'usu.listar.php');
			}
		}
		$stENABLED = isset($_POST['enabled'])?'CHECKED':'';
		$stERROR = $oUsuario->getErrores();
	}
	elseif (isset($_GET['Id'])){
		$stID = $_GET['Id'];

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();
		
		if (!$oUsuario->findId($stID)){
			$oSmarty->assign ('stTITLE'  , 'Modificar usuario');
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
		$stENABLED = ($oUsuario->Enabled=='S')?'CHECKED':'';
		$stALERTA  = ($oUsuario->Alerta=='S') ?'CHECKED':'';
	} else {
		$oSmarty->assign ('stTITLE'  , 'Modificar usuario');
		$oSmarty->assign ('stMESSAGE', 'No puede entrar a esta página directamente');
		$oSmarty->display('information.tpl.html');
		exit();
	}
	$oSmarty->assign('stID', $stID);
	$oSmarty->assign('stERROR'   , $stERROR);
	$oSmarty->assign('stNOMBRE'  , $stNOMBRE);
	$oSmarty->assign('stAPELLIDO', $stAPELLIDO);
	$oSmarty->assign('stPERSONAL', $stPERSONAL);
	$oSmarty->assign('stLABORAL' , $stLABORAL);
	$oSmarty->assign('stCELULAR' , $stCELULAR);
	$oSmarty->assign('stFECNAC'  , $stFECNAC);
	$oSmarty->assign('stENABLED' , $stENABLED);
	$oSmarty->assign('stALERTA'  , $stALERTA);

	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stTITLE' , 'Modificar usuario');
	$oSmarty->assign('stBTN_ACTION', 'Modificar');
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