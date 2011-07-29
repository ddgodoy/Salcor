/**/
Array.prototype.borrame = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};
/**/
var estadoMarcarAll = false;
var aIdsCambioTabla = new Array();
//-----------------------------------------------------------------
function addCambiarTabla(valor){
	var cantV = aIdsCambioTabla.length;
	var celda = document.getElementById('td_cambio_tabla_'+valor);

	if (celda.style.backgroundColor == ''){
		celda.style.backgroundColor = '#FFF0B7'
		aIdsCambioTabla[cantV] = valor;
	} else {
		celda.style.backgroundColor = '';

		var xValor = -1;
		for(var i=0; i<cantV; i++){
			if (aIdsCambioTabla[i] == valor){xValor = i; break;}
		}
		if (xValor != -1){aIdsCambioTabla.borrame(xValor);}
	}
}
//-----------------------------------------------------------------
function addCambiarTablaTodos(){
	var celda = '';
	var idcel = new Array();
	var tabla = document.getElementById('tb_cambio_tabla');

	aIdsCambioTabla.length = 0;

	for (var i=0; i < tabla.rows.length; i++){
		celda = tabla.rows[i].cells[9];
		idcel = celda.id.split('_');

		if (estadoMarcarAll == false){
			celda.style.backgroundColor = '#FFF0B7'
			aIdsCambioTabla[i] = idcel[3];
		} else {
			celda.style.backgroundColor = ''
		}
	}
	estadoMarcarAll = !estadoMarcarAll;
}
//-----------------------------------------------------------------
function runCambiarTabla(){
	if (aIdsCambioTabla.length == 0){
		alert('No hay valores marcados para cambiar');
	} else {
		if (confirm('Confirma este cambio?')){
			document.getElementById('ajax_animation').style.visibility = 'visible';

			var tabla_Cambio = document.getElementById('letra_tb_cambio');
			var strParse_Ids = YAHOO.lang.JSON.stringify(aIdsCambioTabla);
			var oHdlChgTabla = {success:okHdlChgTabla, failure: badHdlChgTabla};

			YAHOO.util.Connect.asyncRequest("POST", 'val.cambiar_tabla.php', oHdlChgTabla, 'tabla='+tabla_Cambio.value+'&ids='+strParse_Ids);
		}
	}
	return false;
}
var okHdlChgTabla = function(o){
	document.getElementById('ajax_animation').style.visibility = 'hidden';
	document.location = 'val.listar.php';
}
var badHdlChgTabla = function(o){}
//-----------------------------------------------------------------
function setTextLetra(letra){
	document.getElementById('stext').value = letra;
	document.getElementById('sselect').selectedIndex = 0;
	document.getElementById('frm_val_listar').submit();
}