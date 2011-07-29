<?php
	include_once '../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	if (isset($_SESSION['_usuId'])){redireccionar(getWeb('ADMIN').'contenido.php');}
	
	$oMyDB = new clsMyDB();
	$stERROR = '';
	$stLOGIN = '';

	if (isset($_POST['btnLogin'])){
		$stLOGIN = $_POST['login'];
		$stPASSW = $_POST['password'];

		require_once getLocal('USU').'class.usuarios.inc.php';

		$oUsuario = new clsUsuarios();
		$oUsuario->clearErrores();

		$oUsuario->doLogin($stLOGIN, $stPASSW);
		if (!$oUsuario->hasErrores()){
			$_SESSION['_usuId'] = $oUsuario->getId();
			$_SESSION['_usuLogin'] = $oUsuario->Login;
			$_SESSION['_usuNombre'] = $oUsuario->getApellido().', '.$oUsuario->getNombre();
			$_SESSION['_usuPersonal'] = $oUsuario->getPersonal();
			/*---------------------------------------------------------------------------*/
			if ($_SESSION['_usuLogin'] == SUPER_ADMIN){
				$rsUsr = $oMyDB->Query("SELECT usuId FROM usuarios WHERE usuLogin != 'admin' ORDER BY usuApellido LIMIT 1;");
				if ($rsUsr){
					$dUsr = mysql_fetch_assoc($rsUsr);
					$_SESSION['_usuActivo'] = $dUsr['usuId'];
				} else {
					$_SESSION['_usuActivo'] = 0;
				}
			} else {$_SESSION['_usuActivo'] = $_SESSION['_usuId'];}
			/*---------------------------------------------------------------------------*/
			/*
			$rValores = $oMyDB->Query("SELECT valId FROM valores WHERE valEstado = 'A' AND valUsuario = ".$_SESSION['_usuActivo'].";");
			if ($rValores){
				require_once getLocal('VAL').'class.valores.inc.php';
				$oValor = new clsValores();

				while ($fila = mysql_fetch_assoc($rValores)){
					$id_valor = $fila['valId'];
	
					$arrDatosActualizados = $oValor->getUltimosDatos($id_valor);
					if (count($arrDatosActualizados) > 0){
						$campos = !empty($arrDatosActualizados['ultima'])   ? 'valUltima = '.$arrDatosActualizados['ultima'].','        : '';
						$campos.= !empty($arrDatosActualizados['volumen'])  ? 'valVolumen = '.$arrDatosActualizados['volumen'].','      : '';
						$campos.= !empty($arrDatosActualizados['liquidez']) ? 'valLiquidez = '.$arrDatosActualizados['liquidez'].','    : '';
						$campos.= !empty($arrDatosActualizados['nombre'])   ? "valNombre = '".$arrDatosActualizados['nombre']."',"      : '';
						$campos.= !empty($arrDatosActualizados['variacion'])? "valVariacion = '".$arrDatosActualizados['variacion']."',": '';
						$campos = substr($campos, 0, -1);
				
						if ($campos!=''){$oMyDB->Command("UPDATE valores SET $campos WHERE valId = $id_valor;");}
					}
				}
			}
			*/
			/*---------------------------------------------------------------------------*/
			redireccionar(getWeb('ADMIN').'contenido.php');
		}
		$stERROR = $oUsuario->getErrores();
	}
	$oSmarty->assign('stACTION', $_SERVER['PHP_SELF']);
	$oSmarty->assign('stLOGIN' , $stLOGIN);
	$oSmarty->assign('stERROR' , $stERROR);
/*--------------------------------------------------------------------------------------------------------------------*/
	$arPrincipios = array();
	$rsPrincipios = $oMyDB->Query("SELECT * FROM principios;");
	while ($rsPrincipios && $priFila = mysql_fetch_assoc($rsPrincipios)){
		$arPrincipios[] = array($priFila['priDicho'],$priFila['priExplicacion']);
	}
	$ctPrincipios = count($arPrincipios);
	if ($ctPrincipios > 0){
		srand((double)microtime()*1000000);
		$axPrincipios = $arPrincipios[rand(0,$ctPrincipios-1)];

		$oSmarty->assign('stPRINCIPIO_DICHO', $oMyDB->forShow($axPrincipios[0]));
		$oSmarty->assign('stPRINCIPIO_EXPLICACION', $oMyDB->forShow($axPrincipios[1]));
	}
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->assign('stHORA_ACTUAL', date('H:i:s'));
	$oSmarty->assign('stEN_LINEA'   , !empty($_SESSION['en_linea']) ? TRUE : FALSE);
	$oSmarty->assign('stDIA_ACTUAL' , diaSemana(date('w')).', '.date('d').' de '.nombresMes(date('m')).' de '.date('Y'));
/*--------------------------------------------------------------------------------------------------------------------*/
	$oSmarty->display('usu.login.tpl.html');
?>