var destDropdown = null;

addOnLoad(function() {
	var drop = $('maintSelector');

	if(drop != null) {
		drop.onchange = function() {
			loadRecord(this.value)
		};
		drop.onkeyup = function() {
			loadRecord(this.value)
		};

		if(window.location.hash != "")
			loadRecord(window.location.hash.substr(1));
	}
});

function loadRecord(recId) {
	if(recId == 0) {
		$('id').value = 0;
		$$('.inputField').each(function(el) {
			if(el.type != "hidden") {
				el.value = "";
			}
		});
	} else {
		new Ajax.Request("/maintenance/get/"+controller+"/"+page+"/"+recId, {
			onSuccess: function(trans) {
				if(trans.responseJSON.error == "") {
					for(field in trans.responseJSON) {
						var fld = $(field);
						if(fld != null) {
							fld.value = trans.responseJSON[field];
						}
					}
				} else {
					alert(trans.responseJSON.error);
				}
			}
		});
	}
	if(typeof dataLoadCallback != "undefined")
		dataLoadCallback();
}

function addQuickEntry(elId, cont, pge) {
	var newEntry = new Element('option', {'value':'0'}).update('&lt;New&gt;');
	var el = $(elId);

	for(var i=0; i<el.childNodes.length; i++) {
		if(typeof el.childNodes[i].tagName != "undefined" && el.childNodes[i].tagName.toLowerCase() == "option") {
			el.insertBefore(newEntry, el.childNodes[i+1]);
			break;
		}
	}

	el.observe('change', function() {
		if(this.value == 0) {
			destDropdown = this;

			tb_show("Quick Add", "http://tbj-adpublisher.com/maintenance/prompt/" + cont + "/" + pge + "?height=750&width=1000", false);
		}
	});
}

function deleteRecord() {
	if($F('id') == 0 || $F('id') == "") {
		alert("No record selected.");
	} else if(confirm("Are you sure you wish to delete this customer?")) {
		new Ajax.Request("/maintenance/delete/"+controller+"/"+page+"/"+$F('id'), {
			onSuccess: function(trans) {
				if(trans.responseText != "") {
					alert("An error occurred while deleting this record:\n\n" + trans.responseText);
				} else {
					window.location.href = window.location.protocol + '//' + window.location.host + window.location.pathname;
				}
			}
		});
	}
}

function save(isPopup) {
	if(typeof isPopup == "undefined")
		isPopup = false;

	var flds = $$('.inputField');
	var params = {};
	if(!isPopup)
		params.id = $F('id');

	for(var i=0; i<flds.length; i++) {
		params[flds[i].getAttribute('name')] = flds[i].value
	}

	if(typeof onBeforeSave != "undefined")
		onBeforeSave(params);

	new Ajax.Request("/maintenance/save/"+controller+"/"+page, {
		method: 'post',
		parameters: params,
		onSuccess: function(trans) {

			if(trans.responseJSON.error == "") {
				var newid = trans.responseJSON.id;
				var newval = trans.responseJSON.fldName;

				if(isPopup) {
					var opt = new Element('option', {'value':newid, 'selected':'selected'}).update(newval);

					destDropdown.appendChild(opt);
					destDropdown.onchange();
					destDropdown = null;

					tb_remove();
				} else {
					$('id').value = newid;
				}
				if(!isPopup)
					alert("Saving Complete");
			} else {
				if(isPopup)
					tb_remove();
				alert(trans.responseJSON.error);
			}
		}
	});
}

function savePopup() {
	save(true);
}
