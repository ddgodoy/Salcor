<?php
	require_once '../../cOmmOns/config.inc.php';
	require_once getLocal('ADMIN').'menu.inc.php';	
	require_once getLocal('ADMIN').'check_usuario.php';

	$aSectores = array();
	$rSectores = $oMyDB->Query("SELECT * FROM sectores ORDER BY secCodigo;");

	while ($rSectores && $dSectores = mysql_fetch_assoc($rSectores)){
		$cod = "<div style='width:40px;float:left;overflow:hidden;line-height:20px;'>".$dSectores['secCodigo'].'</div>';
		$nom = "<div style='width:230px;float:left;overflow:hidden;line-height:20px;'>".$oMyDB->forShow($dSectores['secNombre']).'</div>';

		$aSectores[$dSectores['secId']] = $cod.$nom;
	}
	$oSmarty->assign('stSECTORES', llenarDivMultiple_Tab('sectores[]', $aSectores));
	$oSmarty->assign('stTITLE', 'Reportes');

	$oSmarty->display('rep.inicio.tpl.html');
?>