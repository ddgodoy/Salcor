<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('COMMONS').'func.html.inc.php';
	require_once getLocal('COMMONS').'class.mysql.inc.php';

	$stRETURN  = '';
	$stIDVALOR = !empty($_POST['id']) ? $_POST['id'] : 0;
	$stFECHA   = !empty($_POST['fecha'])  ? $_POST['fecha']  :'';
	$stPRECIO  = !empty($_POST['precio']) ? $_POST['precio'] : 0;
	$stFCOMPRA = !empty($_POST['fcompra'])? $_POST['fcompra']:'';
	$stPCOMPRA = !empty($_POST['pcompra'])? $_POST['pcompra']: 0;
	$stIDCOMPRA= !empty($_POST['id_compra'])? $_POST['id_compra']: 0;
	$stBOLSA  = !empty($_POST['id_bolsa'])?$_POST['id_bolsa']: 0;

	if ($stIDVALOR > 0){
		if ($stPRECIO > 0){
			if (ValidaFecha($stFECHA)){
				$oMyDB = new clsMyDB();
				$sHORA = date('H:i:s');
				$sDATE = $oMyDB->verifyDate($stFECHA);
				$cDATE = $oMyDB->verifyDate($stFCOMPRA);

				$insert = "INSERT INTO ventas (venValor, venFecha, venHora, venPrecio, venFCompra, venPCompra , venBolsa) ".
						   "VALUES ($stIDVALOR, '$sDATE', '$sHORA', $stPRECIO, '$cDATE', $stPCOMPRA, $stBOLSA);";
				if ($oMyDB->Command($insert)){
					$oMyDB->Command("UPDATE compras SET cmpEstado = 'I' WHERE cmpId = $stIDCOMPRA;");
					$stRETURN = 'venta,'.getWeb('OPE').'ope.listar.php';
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