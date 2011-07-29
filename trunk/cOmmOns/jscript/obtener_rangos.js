function obtenerRangos(){
	var sigla = document.getElementById('sigla');
	if (sigla.value == ''){
		alert('Debe ingresar una sigla.');
		sigla.focus();
	} else {
		document.getElementById('indicador_ajax_rangos').style.visibility = 'visible';

		var riesgo_tb = document.getElementById('riesgo_tb').innerHTML;
		var riesgo_tmb= document.getElementById('riesgo_tmb').innerHTML;
		var riesgo_te = document.getElementById('riesgo_te').innerHTML;

		var rutaAccionActual = document.getElementById('ruta_accion_rangos').value;
		var oBackAccionRango = {success:okAccionRango,failure:badAccionRango};

		postData = 'sigla='+sigla.value+'&b='+escape(riesgo_tb)+'&mb='+escape(riesgo_tmb)+'&e='+escape(riesgo_te);
		YAHOO.util.Connect.asyncRequest("POST", rutaAccionActual+"val.obtener_rangos.php", oBackAccionRango, postData);
	}
}
/*-------------------------------------------------------------*/
var badAccionRango = function(o){}
var okAccionRango = function(o){
	document.getElementById('indicador_ajax_rangos').style.visibility = 'hidden';

	var respuesta = o.responseText;
	if (respuesta != 'bad'){
		var aValores = respuesta.split('_');
		aValores[0] = aValores[0]!=''?parseFloat(aValores[0]):0;
		aValores[1] = aValores[1]!=''?parseFloat(aValores[1]):0;
		aValores[2] = aValores[2]!=''?parseFloat(aValores[2]):0;

		document.getElementById('rango_cb').value = aValores[0].toFixed(2);
		document.getElementById('rango_cmb').value= aValores[1].toFixed(2);
		document.getElementById('rango_ce').value = aValores[2].toFixed(2);
	}
}
/*-------------------------------------------------------------*/
function cambiarValoresTabla(valor){
	document.getElementById('indicador_ajax_rangos').style.visibility = 'visible';
	
	var rutaAccionActual = document.getElementById('ruta_accion_rangos').value;
	var oBackAccionPorcentajes = {success:okAccionPorcentajes,failure:badAccionPorcentajes};

	postData = 'tabla='+valor+'&usuario='+document.getElementById('hidden_usuario').value;
	YAHOO.util.Connect.asyncRequest("POST", rutaAccionActual+"val.obtener_porcentajes.php", oBackAccionPorcentajes, postData);
}
/*-------------------------------------------------------------*/
var badAccionPorcentajes = function(o){}
var okAccionPorcentajes = function(o){
	document.getElementById('indicador_ajax_rangos').style.visibility = 'hidden';

	var respuesta = o.responseText;
	if (respuesta != 'bad'){
		var aValores = respuesta.split('_');
		aValores[0] = aValores[0]!=''?parseFloat(aValores[0]):0;
		aValores[1] = aValores[1]!=''?parseFloat(aValores[1]):0;
		aValores[2] = aValores[2]!=''?parseFloat(aValores[2]):0;

		document.getElementById('riesgo_tb').innerHTML = aValores[0].toFixed(2);
		document.getElementById('riesgo_tmb').innerHTML= aValores[1].toFixed(2);
		document.getElementById('riesgo_te').innerHTML = aValores[2].toFixed(2);
		
		document.getElementById('rango_cb').value = 0;
		document.getElementById('rango_cmb').value= 0;
		document.getElementById('rango_ce').value = 0;
	}
}