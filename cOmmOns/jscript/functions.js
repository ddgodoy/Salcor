var clockID = 0;
function StartClock(){clockID = setTimeout("UpdateClock();", 500);}
function UpdateClock(){
   if(clockID) {clearTimeout(clockID); clockID = 0;}
   var tDate= new Date();
   var hor  = tDate.getHours();
   var seg  = tDate.getSeconds();
   var min  = tDate.getMinutes();

   seg = seg.toString(); min = min.toString();

   if (seg.length == 1){seg = '0' + seg;}
   if (min.length == 1){min = '0' + min;}

  	document.getElementById('hora_actual').innerHTML = hor + ":" + min + ":" + seg;

	clockID = setTimeout("UpdateClock();", 1000);
}
function KillClock(){if(clockID) {clearTimeout(clockID); clockID = 0;}}
//------------------------------------------------------------------------
function confirmLink(theLink, theText){
    if (theText == '' || typeof(window.opera) != 'undefined') {return true;}
    var is_confirmed = confirm(theText);
    if (is_confirmed) {
        theLink.href += '&confirmado=1';
    }
    return is_confirmed;
}
//------------------------------------------------------------------------
function doPreview(wich, where){
	var objFile = document.getElementById(wich); //imagen
	var objImg = document.getElementById(where); //preview

	objImg.src = objFile.value;
}
//------------------------------------------------------------------------
function openWin(ventana,popW,popH,nombre_ventana){
	if (nombre_ventana == undefined){var nom = 'Window';} else {var nom = nombre_ventana;}
	var w = 0, h = 0;

   	w = screen.width;
   	h = screen.height;

	var leftPos = (w-popW)/2, topPos = (h-popH)/2;

    popupWindow=open(''+ventana+'',nom,'resizable=no,scrollbars=yes,width='+popW+',height='+popH+',top='+topPos+',left='+leftPos);
    if (popupWindow.opener == null){popupWindow.opener = self;}
}
//------------------------------------------------------------------------
function highlight_div(checkbox_node){
    label_node = checkbox_node.parentNode;

    if (checkbox_node.checked){
		label_node.style.backgroundColor='#5B646C';
		label_node.style.color='#ffffff';
	} else {
		label_node.style.backgroundColor='#ffffff';
		label_node.style.color='#000000';
	}
}
//------------------------------------------------------------------------
function checkAll(caja){
	var qEstado = false;
	var cantidad= caja.length;
	if (document.getElementById('check_todos').checked){
		qEstado = true;
	}
	for (i=0; i<cantidad; i++){caja[i].checked = qEstado;}
}
//------------------------------------------------------------------------
function opacity(id, opacStart, opacEnd, millisec){
	var timer = 0;
    var speed = Math.round(millisec / 100);
    for(i = opacStart; i <= opacEnd; i++){
        setTimeout("changeOpac(" + i + ",'" + id + "')", timer * speed); timer++;
    }
}
function changeOpac(opacity, id){
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
}
//------------------------------------------------------------------------
function getPageSize(){	
	var xScroll, yScroll;

	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else {
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	var windowWidth, windowHeight;
	if (self.innerHeight) {
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}
	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
}
//------------------------------------------------------------------------
function activarOverlay(){
	var aPantalla = getPageSize();
	var oDiv = document.getElementById('id_div_overlay');

	oDiv.style.width = aPantalla[2];
	oDiv.style.height = aPantalla[3];
	oDiv.style.visibility = 'visible';

	changeOpac(0,'id_div_overlay');
	opacity('id_div_overlay',0,93,300);
}
function desactivarOverlay(){
	var oDiv = document.getElementById('id_div_overlay');
	oDiv.style.visibility = 'hidden';
	oDiv.style.width = 0;
	oDiv.style.height = 0;
}