var is_gecko = /gecko/i.test(navigator.userAgent);
var is_ie = /MSIE/.test(navigator.userAgent);

function setSelectionRange(input, start, end) {
	if (is_gecko) {
		input.setSelectionRange(start, end);
	} else {
		var range = input.createTextRange();
		range.collapse(true);
		range.moveStart("character", start);
		range.moveEnd("character", end - start);
		range.select();
	}
};
function getSelectionStart(input) {
	if (is_gecko)
		return input.selectionStart;
	var range = document.selection.createRange();
	var isCollapsed = range.compareEndPoints("StartToEnd", range) == 0;
	if (!isCollapsed)
		range.collapse(true);
	var b = range.getBookmark();
	return b.charCodeAt(2) - 2;
};
function getSelectionEnd(input) {
	if (is_gecko)
		return input.selectionEnd;
	var range = document.selection.createRange();
	var isCollapsed = range.compareEndPoints("StartToEnd", range) == 0;
	if (!isCollapsed)
		range.collapse(false);
	var b = range.getBookmark();
	return b.charCodeAt(2) - 2;
};
//------------------------------------------------------------------------
function onlyInteger(InputText, evt){
	var strValue = '';
	var charCode = (evt.which) ? evt.which : evt.keyCode;

  	var selectionStart = getSelectionStart(InputText);
  	var selectionEnd = getSelectionEnd(InputText);
  	strValue = InputText.value.substring(0, selectionStart) + String.fromCharCode(charCode) + InputText.value.substring(selectionEnd);

	if ((!isInteger(strValue) && charCode > 40) || charCode == 45 || charCode == 32){return false;}
	return true;
}
//------------------------------------------------------------------------
function onlyDecimal(InputText, evt) {
	var strValue = '';
	var charCode = evt.which ? evt.which : evt.keyCode;

  	var selectionStart = getSelectionStart(InputText);
  	var selectionEnd = getSelectionEnd(InputText);
  	strValue = InputText.value.substring(0, selectionStart) + String.fromCharCode(charCode) + InputText.value.substring(selectionEnd);

  	if ((!isDecimal(strValue) && charCode > 40) || charCode == 32) {return false;}
	return true;
}
//------------------------------------------------------------------------
function isDecimal(inputVal) {
	oneDecimal = false;
	inputStr = inputVal.toString();
	for (var i = 0; i < inputStr.length; i++) {
		var oneChar = inputStr.charAt(i);
		if (oneChar == "-") {return false;}
		if (oneChar == "." && !oneDecimal) {oneDecimal = true; continue;}
		if (oneChar < "0" || oneChar > "9") {return false;}
	}
	return true;
}
//------------------------------------------------------------------------
function isInteger(inputVal) {
	inputStr = inputVal.toString();
	for (var i = 0; i < inputStr.length; i++) {
		var oneChar = inputStr.charAt(i);

		if (i == 0 && oneChar == "-") {continue;}
		if (oneChar < "0" || oneChar > "9"){return false;}
	}
	return true;
}