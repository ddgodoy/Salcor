<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$stRETURN = 'bad';
	$stTABLA  = !empty($_POST['tabla'])  ? $_POST['tabla']:'';
	$stUSUARIO= !empty($_POST['usuario'])? $_POST['usuario'] : 0;

	if ($stTABLA != ''){
		$oMyDB = new clsMyDB();
		$camp1 = 'rie'.$stTABLA.'_bueno';
		$camp2 = 'rie'.$stTABLA.'_muybueno';
		$camp3 = 'rie'.$stTABLA.'_excelente';

		$rsRes = $oMyDB->Query("SELECT $camp1 as b, $camp2 as mb, $camp3 as e FROM riesgo WHERE rieUsuario = $stUSUARIO;");
		if ($rsRes){
			$dtRes = mysql_fetch_assoc($rsRes);

			$stRETURN = '';
			$stRETURN.= $dtRes['b'].'_'.$dtRes['mb'].'_'.$dtRes['e'];
		}
	}
	echo $stRETURN;
?>