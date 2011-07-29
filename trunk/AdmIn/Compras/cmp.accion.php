<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$stRETURN = '';
	$stIDVALOR= !empty($_POST['id']) ? $_POST['id'] : 0;
	$stIDSUG  = !empty($_POST['id_sug']) ? $_POST['id_sug'] : 0;
	$stFECHA  = !empty($_POST['fecha']) ? $_POST['fecha'] :'';
	$stPRECIO = !empty($_POST['precio'])? $_POST['precio']: 0;
	$stBOLSA  = !empty($_POST['id_bolsa'])?$_POST['id_bolsa']: 0;

	if ($stIDVALOR > 0){
		if ($stPRECIO > 0){
			if (ValidaFecha($stFECHA)){
				$oMyDB = new clsMyDB();
				$sHORA = date('H:i:s');
				$sDATE = $oMyDB->verifyDate($stFECHA);

				if ($oMyDB->Command("INSERT INTO compras (cmpValor, cmpFecha, cmpHora, cmpPrecio, cmpBolsa) VALUES ($stIDVALOR, '$sDATE', '$sHORA', $stPRECIO, $stBOLSA);")){
					@$oMyDB->Command("DELETE FROM sugeridos WHERE sugId = $stIDSUG;");

					$stRETURN = 'compra,'.getWeb('REN').'ren.listar.php';
				} else {
					$stRETURN = 'bad';
				}
			} else {
				$stRETURN = 'fecha';
			}
		} else {
		$stRETURN = 'precio';
		}
	} else {
		$stRETURN = 'id';
	}
	echo $stRETURN;
?>