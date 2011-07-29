function getDatosPanel(id, accion){
	var sigla = document.getElementById('sigla_'+id).innerHTML;
	var precio= document.getElementById('precio_'+id).innerHTML;
	var fecha = document.getElementById('fechacorta_hoy').value;

	document.getElementById('sigla_panel').innerHTML = sigla;
	document.getElementById('fecha_panel').value = fecha;
	document.getElementById('precio_panel').value= precio;
	document.getElementById('boton_panel').value = accion;
	document.getElementById('id_valor_panel').value = id;
	document.getElementById('leyenda_panel').innerHTML = 'DATOS DE COMPRA';

	activarPanel();
}
function getDatosPanelSug(id, accion, id_sug){
	var sigla = document.getElementById('sigla_'+id+'_'+id_sug).innerHTML;
	var precio= document.getElementById('precio_'+id+'_'+id_sug).innerHTML;
	var fecha = document.getElementById('fechacorta_hoy').value;

	document.getElementById('sigla_panel').innerHTML = sigla;
	document.getElementById('fecha_panel').value = fecha;
	document.getElementById('precio_panel').value= precio;
	document.getElementById('boton_panel').value = accion;
	document.getElementById('id_valor_panel').value = id;
	document.getElementById('id_valor_sug').value = id_sug;
	document.getElementById('leyenda_panel').innerHTML = 'DATOS DE COMPRA';

	activarPanel();
}
/**/
function getDatosPanelVenta(id, accion, id_compra){
	var sigla  = document.getElementById('sigla_'+id).innerHTML;
	var precio = document.getElementById('precio_'+id).innerHTML;
	var fcompra= document.getElementById('fcompra_'+id).innerHTML;
	var pcompra= document.getElementById('pcompra_'+id).innerHTML;
	var fecha  = document.getElementById('fechacorta_hoy').value;
  var nombre_bolsa = document.getElementById('nombre_bolsa_'+id).value;
	var id_bolsa = document.getElementById('id_bolsa_'+id).value;

	document.getElementById('sigla_panel').innerHTML= sigla;
	document.getElementById('fecha_panel').value    = fecha;
	document.getElementById('precio_panel').value   = precio;
	document.getElementById('boton_panel').value    = accion;
	document.getElementById('fcompra_panel').value  = fcompra;
	document.getElementById('pcompra_panel').value  = pcompra;
	
  xDatos = id.split('_');
	
	document.getElementById('id_valor_panel').value = xDatos[0];
	document.getElementById('compra_valor_id_panel').value = id_compra;
	document.getElementById('leyenda_panel').innerHTML = 'DATOS DE VENTA';
	
	/*instancio un objeto y de esta manera puedo crear option al vuelo*/
	var oMiSelect = document.getElementById('opcion_bolsa');
	oMiSelect.options[0] = new Option(nombre_bolsa, id_bolsa);
	//oMiSelect.disabled = true;
	/**/
	activarPanel();
}
/*-------------------------------------------------------------*/
function activarPanel(){
	var aPantalla = getPageSize();
	var oDiv = document.getElementById('id_div_panel');

	oDiv.style.left = (aPantalla[2] / 2) - 100;
	oDiv.style.top = (aPantalla[3] / 2) - 100;
	oDiv.style.display = 'block';

	changeOpac(0,'id_div_panel');
	opacity('id_div_panel',0,100,300);
}
function desactivarPanel(){
	var oDiv = document.getElementById('id_div_panel');
	oDiv.style.display = 'none';
	oDiv.style.top = 0;
	oDiv.style.left = 0;
}
/*-------------------------------------------------------------*/
function checkAccionPanelSug(){
	if (!confirm('Confirma la compra?')){
		return false;
	}
	document.getElementById('indicador_ajax_panel').style.visibility = 'visible';

	var rutaAccionActual = document.getElementById('ruta_accion_actual').value;
	var oBackAccionPanel = {success:okAccionPanel,failure:badAccionPanel};
	
	postData = 'id=' + document.getElementById('id_valor_panel').value +
			   '&id_sug=' + escape(document.getElementById('id_valor_sug').value) + 
			   '&fecha=' + escape(document.getElementById('fecha_panel').value) + 
			   '&precio=' + document.getElementById('precio_panel').value +
			   '&fcompra=' + document.getElementById('fcompra_panel').value +
			   '&pcompra=' + document.getElementById('pcompra_panel').value + 
			   '&id_compra=' + document.getElementById('compra_valor_id_panel').value + 
		   '&id_bolsa=' + document.getElementById('opcion_bolsa').value ;

	YAHOO.util.Connect.asyncRequest("POST", rutaAccionActual+"cmp.accion.php", oBackAccionPanel, postData);

	return true;
}
/*-------------------------------------------------------------*/
function checkAccionPanel(){
	var prefijo = 'cmp';
	var mensaje = 'compra';

	if (document.getElementById('boton_panel').value == 'Vender'){
		prefijo = 'ren';
		mensaje = 'venta';
	}
	if (!confirm('Confirma la '+mensaje+'?')){
		return false;
	}
	document.getElementById('indicador_ajax_panel').style.visibility = 'visible';

	var rutaAccionActual = document.getElementById('ruta_accion_actual').value;
	var oBackAccionPanel = {success:okAccionPanel,failure:badAccionPanel};
	
	postData = 'id=' + document.getElementById('id_valor_panel').value +
			   '&fecha=' + escape(document.getElementById('fecha_panel').value) + 
			   '&precio=' + document.getElementById('precio_panel').value +
			   '&fcompra=' + document.getElementById('fcompra_panel').value +
			   '&pcompra=' + document.getElementById('pcompra_panel').value + 
			   '&id_compra=' + document.getElementById('compra_valor_id_panel').value + 
		   '&id_bolsa=' + document.getElementById('opcion_bolsa').value ;

	YAHOO.util.Connect.asyncRequest("POST", rutaAccionActual+prefijo+".accion.php", oBackAccionPanel, postData);

	return true;
}
/*-------------------------------------------------------------*/
var okAccionPanel = function(o){
	document.getElementById('indicador_ajax_panel').style.visibility = 'hidden';

	if (o.responseText == 'id'){
		alert('No es posible encontrar el valor');
	} else if (o.responseText == 'precio'){
		alert('Debe ingresar el precio');
	} else if (o.responseText == 'fecha'){
		alert('La fecha no es correcta');
	} else if (o.responseText == 'bad'){
		alert('No es posible completar el registro');
	} else {
		var sRespuesta = o.responseText;
		var aRespuesta = new Array();
		aRespuesta = sRespuesta.split(',');

		alert('La '+aRespuesta[0]+' fue registrada exitosamente');
		document.location = aRespuesta[1];
	}
}
/*-------------------------------------------------------------*/
var badAccionPanel = function(o){alert('Error interno.');}