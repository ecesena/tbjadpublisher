function addOnLoad(newFunc) {
	if (typeof window.onload != 'function') {
		window.onload = newFunc;
	} else {
		var oldOnLoad = window.onload;

		window.onload = function() {
			if (oldOnLoad) {
				oldOnLoad();
			}
			newFunc();
		}
	}
}

function formatDate(dte) {
	return (dte.getMonth()+1) + "-" + (dte.getDate()) + "-" + (dte.getFullYear());
}

function removeChildNodes(el) {
	while(el.childNodes[0]) {
		el.removeChild(el.childNodes[0]);
	}
}

function isNumeric(sText) {
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;

	for (i = 0; i < sText.length && IsNumber == true; i++)  { 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) {
			IsNumber = false;
		}
	}

	return IsNumber;
}

var samplecal;
var samplecal1;
var samplecal2;
function showCalendars(destId, calDiv) {
	if(typeof calDiv == "undefined")
		calDiv = "samplecal";
	
	if(samplecal)
		samplecal.destroy();
	if(samplecal1)
		samplecal1.destroy();
	if(samplecal2)
		samplecal2.destroy();
	
	$('samplecal').addClassName('scal tinyscal');
	$('samplecal').show();
	var options = {
		titleformat:'mmmm yyyy',
		closebutton:'X',
		nextbutton:'',
		prevbutton:'',
		dayheadlength:2,
		weekdaystart:0
		};
	
	calUpdate = function(d) { updateCalendarEl(d, destId) };
	samplecal = new scal('samplecal', function() {}, options);
	samplecal.setCurrentDate('monthdown');

	options.closebutton = '';
	samplecal1 = new scal('samplecal', function() {}, options);
	
	samplecal2 = new scal('samplecal', function() {}, options);
	samplecal2.setCurrentDate('monthup');

	$$('.dayselected').invoke('removeClassName', 'dayselected');

	samplecal.updateelement = calUpdate;
	samplecal1.updateelement = calUpdate;
	samplecal2.updateelement = calUpdate;
}

function updateCalendarEl(d, el) {
	$(el).value = d.format('mm-dd-yyyy');
	$(el).onchange();
}

function reduceHTML(srcEl) {
	if(srcEl.nodeType == 3) { //Text Node, check it out
		var retStr = srcEl.nodeValue;
//		alert(srcEl.parentNode.parentNode.outerHTML);
		
		var curEl = srcEl.parentNode;
		var isBold = false;
		var isItalic = false;
		var isUnderlined = false;

		while(curEl != null) {
			if(curEl.getStyle) {
//				alert(curEl.getStyle('font-weight'));
				if(curEl.getStyle('font-style') == 'italic')
					isItalic = true;
				
				if(curEl.getStyle('text-decoration') == 'underline')
					isUnderlined = true;
				
				if(curEl.getStyle('font-weight') == 'bold' || curEl.getStyle('font-weight') == 700 || curEl.getStyle('font-weight') == 401)
					isBold = true;
			}
			curEl = curEl.parentNode;
		}
		
		if(isItalic) {
			retStr = "<EM>" + retStr + "</EM>";
			//alert(retStr + " is italic");
		}
		
		if(isUnderlined) {
			retStr = "<U>" + retStr + "</U>";
			//alert(retStr + " is underlined");
		}
		
		if(isBold) {
			retStr = "<STRONG>" + retStr + "</STRONG>";
//			alert(retStr + "\r\n '" + srcEl.parentNode.getStyle('text-decoration') + "'");
		}
		
		return retStr;
		
	} else { //Other node type, recurse over children
		if(srcEl.childNodes) {
			var retStr = "";
			for(var i=0; i<srcEl.childNodes.length; i++) {
				retStr += reduceHTML(srcEl.childNodes[i]);
			}
		}
		
		if(srcEl.getStyle && srcEl.getStyle('display') == "block") {
			if(srcEl.innerText != "" && retStr.substr(0, 3) != "<p>")
				retStr = "<p>" + retStr + "</p>";
		}
		
		if(srcEl.tagName == "BR")
			retStr += "<br>";
		
		return retStr;
	}
}
