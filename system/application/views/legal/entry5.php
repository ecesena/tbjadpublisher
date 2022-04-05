<script language="javascript" type="text/javascript">
var entry_id = <?=$ad_info->id?>;

addOnLoad(function() {
	if(window.location.hash != "" && isNumeric(window.location.hash.substr(1))) {
		window.location = window.location.href.replace(/#/, "/");
	}

	<?
	if($rundates != NULL) {
		for($i=0; $i<count($rundates); $i++) {
			echo "addRunDate('".mysql_to_human($rundates[$i]->date)."');\n";
		}
	}
	?>
	addRunDate();

	<? if(can_access("maintenance")): ?>
	addQuickEntry('legal_customer_id', 'legal', 'customer');
	<? endif; ?>


<? if($ad_info->isready == 1): ?>
	setAdLocked(false);
<? endif;?>
});

function FCKeditor_OnComplete(editorInstance) {
    editorInstance.Events.AttachEvent( 'OnBlur', function(e) { edOnBlur(); });
    editorInstance.Events.AttachEvent( 'OnPaste', function(e) {
		setTimeout("edOnBlur()", 100);
		return true;
		edOnBlur();
	}
	);
	cleanup(false);

	if(adIsLocked) {
		setTimeout("FCKeditorAPI.GetInstance('adTextCntrl').EditorDocument.designMode = 'off'", 100);
	}
}


function edOnBlur() {
	cleanup();
}

function cleanup(saveIt) {
	//alert('cleaning');
	if(typeof saveIt == "undefined")
		saveIt = true;

	oEditor = FCKeditorAPI.GetInstance('adTextCntrl');

	var srcEl = $('hiddenContentEditor');
	srcEl.innerHTML = oEditor.GetHTML();

//	alert(ed.getContent());
	/*
	var temp = ed.getContent({format: 'raw'});
	var temp2 = "";
	for(var i=0; i<temp.length; i++) {
		temp2 += temp.substr(i, 1) + " - " + temp.charCodeAt(i) + "\n";

		if(i % 10 == 0) {
			alert(temp2);
			temp2 = "";
		}
	}
	alert(temp2);
	*/

	var cleanHTML = reduceHTML(srcEl); //reduceHTML(srcEl);
	cleanHTML = cleanHTML.replace(/<br><\/p>/g, "<br>&nbsp;</p>");

	//Load text into preview area then clean again. Fixes linespace issue?
	srcEl.innerHTML = cleanHTML;
	cleanHTML = reduceHTML(srcEl);
	cleanHTML = cleanHTML.replace(/<br><\/p>/g, "<br>&nbsp;</p>");

//	cleanHTML = cleanHTML.replace(/  /g, "&nbsp;&nbsp;");

	var replacements = {
		"\u2018": "'",
		"\u2019": "'",
		"\u201c": '"',
		"\u201d": '"',
		"\u2011": '-',
		"\u2013": '-',
		"\u2014": '--',
		"\u00A7": '\xA7'
	}

	for(regexp in replacements) {
		var reg = new RegExp(regexp, 'g');
		cleanHTML = cleanHTML.replace(reg, replacements[regexp] );
	}

	//cleanHTML = cleanHTML.replace(/(\s*<p>(\s|&nbsp;)*<\/p>\s*)+\s*$/, "");

	if(cleanHTML == "")
		cleanHTML = "<p></p>";
	oEditor.SetHTML(cleanHTML);

	var rds = $$('.runDate input:not([value~="Enter Date Here"])').pluck('value').join(', ').replace(/-/g, '/');

	srcEl.innerHTML = cleanHTML + "<div>&nbsp;&nbsp;" + rds + "</div>";

	if(saveIt)
		saveFld(cleanHTML, 'adtext');
}

function setEditorText(txt) {
/*	var txtCntrl = document.getElementById('adTextCntrl');
	removeChildNodes(txtCntrl);
	txtCntrl.appendChild(document.createTextNode(txt));*/
	var oEditor = FCKeditorAPI.GetInstance('adTextCntrl');
	oEditor.SetHTML(txt);

	cleanup(false);
}


var weekDayArray = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat", ""];
function addRunDate(dateVal) {
	if(typeof dateVal == "undefined" || dateVal == "") {
		dateVal = "Enter Date Here";
		var fontCol = "#999999";
		var weekDay = 7;
	} else {
		var fontCol = "#000000";

		var dateValSplit = dateVal.split(/-/g);
		var dateObj = new Date(dateValSplit[2], dateValSplit[0]*1-1, dateValSplit[1]);
		var weekDay = dateObj.getDay();
	}

	var contDiv = document.getElementById('currentRunDates');

	var nextRunDateEl = document.getElementById('nextRunDate');
	var nextRunDate = nextRunDateEl.value*1;
	nextRunDateEl.value = nextRunDate + 1;

	var rdDiv = document.createElement('div');
	rdDiv.className = 'runDate';

	var rdInp = document.createElement('input');
	rdInp.setAttribute('type', 'text');
	rdInp.setAttribute('name', 'runDate'+nextRunDate);
	rdInp.setAttribute('id', 'runDate'+nextRunDate);
	rdInp.setAttribute('value', dateVal);
	rdInp.style.color = fontCol;
	rdInp.onfocus = rdFocus;
	rdInp.onblur = function() { el = this; setTimeout("rdBlur()", 100); };
	rdInp.onkeyup = function() {
		var rd = $$('.runDate input').last();
		if(rd.value != "" && rd.value != "Enter Date Here" && !adIsLocked) {
			addRunDate();
		}
	}
	rdInp.onchange = function() {
		var dateValSplit = this.value.split(/-/g);
		var dateObj = new Date(dateValSplit[2], dateValSplit[0]*1-1, dateValSplit[1]);
		var weekDay = dateObj.getDay();

		this.parentNode.childNodes[0].innerText = weekDayArray[weekDay];

		rdInp.onblur();
		rdInp.onkeyup();
	}
	rdWeekdaySpan = document.createElement('span');
	rdWeekdaySpan.appendChild(document.createTextNode(weekDayArray[weekDay]));
	rdWeekdaySpan.className = 'weekdayLabel';

	rdDiv.appendChild(rdWeekdaySpan);

	rdDiv.appendChild(rdInp);
	contDiv.appendChild(rdDiv);
}

function rdShortcut(numRuns) {
	if(adIsLocked) {
		alert("Ad is set to run. You cannot change the run dates unless you click Modify Ad or Cancel Run.");
	} else {
		var fRunDate = $$('.runDate input').first();
		if(fRunDate.value == "Enter Date Here" || (fRunDate.value != "Enter Date Here" && confirm("Run Dates have already been entered for this Ad. Continue?"))) {
			tb_show("Select start date", "/legal/rdshortcutprompt/"+numRuns+"?width=300&height=200");
		}
	}
}

function rdFocus() {
	this.style.color = "#000000";
	if(this.value == "Enter Date Here") {
		this.value = "";
	}
	showCalendars(this);
}

function rdBlur() {
	if(el.value == "") {
		el.style.color = "#999999";
		el.value = "Enter Date Here";
	}
	saveRundates();
}

function saveFld(newVal, fieldName) {
	if(!adIsLocked || fieldName == "isready") {
		new Ajax.Request("/legal/save_entry/"+entry_id, {
			parameters: {'fld':fieldName, 'val':newVal},
			onSuccess: function(trans) {
				$('adlength').update(trans.responseText.split(/~/)[1]);

				if(entry_id == 0) {
					var newentryid = trans.responseText.split(/~/)[0];

					window.location.hash = "#"+newentryid;

					entry_id = newentryid*1;
					document.getElementById('entryid').value = entry_id;
					document.getElementById('scrollEntryId').update(entry_id);
				}
			}
		});
	}
}

function saveRundates() {
	rdates = "";

	var lastRunDate = document.getElementById('nextRunDate').value;
	for(var i=0; i<lastRunDate; i++) {
		rd = $('runDate'+i);

		if(rd != null) {
			rdate = rd.value
			if(rdate != "" && rdate != "Enter Date Here") {
				if(rdates == "")
					rdates = rdate;
				else
					rdates += "," + rdate;
			}
		}
	}

	saveFld(rdates, "runDates");
}

function delEntry() {
	if(entry_id != 0) {
		if(confirm("By clicking Ok you understand that this notice will be deleted and will not appear in any further output files.")) {
			window.location = "/legal/delete_entry/"+entry_id;
		}
	}
}

function generateProof() {
		window.open("/report/generateRTF/"+entry_id);
}

var adIsLocked;
function setAdLocked(updateDb) {
	if(updateDb && ($F('legal_customer_id') == "" || $F('legal_doctype_id') == "")) {
		alert('The customer and the document type must be entered before the ad can be set to run.');
		return;
	}
	adIsLocked = true;
	if(updateDb) { //if true run button was clicked.
		saveFld("1", "isready");
	}

	var lockList = new Array('legal_customer_id', 'legal_doctype_id', 'filenumber', 'controlnumber', 'name', 'estimatedcost');

	for(var i=0; i<lockList.length; i++) {
		var el = $(lockList[i]);

		if(el.tagName == "input" || el.tagName == "textarea") {
			el.setAttribute('readOnly', 'readOnly');
		} else {
			el.setAttribute('disabled', 'disabled');
		}
	}

	if(typeof FCKeditorAPI != 'undefined') {
		var oEditor = FCKeditorAPI.GetInstance('adTextCntrl');
		if(typeof oEditor != "undefined" && typeof oEditor.EditorDocument != "undefined") {
			FCKeditorAPI.GetInstance('adTextCntrl').EditorDocument.designMode = 'off';
		}
	}

	var lastRunDate = $F('nextRunDate');
	for(var i=0; i<lastRunDate; i++) {
		var rd = $('runDate'+i);

		if(rd != null) {
			if(rd.value == "Enter Date Here" || rd.value == "") {
				rd.parentNode.parentNode.removeChild(rd.parentNode);
			} else {
				rd.style.backgroundColor = "#D0F0C0";
				rd.style.borderColor = "#D0F0C0";
				rd.parentNode.style.backgroundColor = "#D0F0C0";
				rd.setAttribute('readOnly', 'readOnly');
				rd.onfocus = function() {};
			}
		}
	}
}

function setAdUnlocked(deleteRundates) {
	adIsLocked = false;
	if(deleteRundates && confirm("Are you sure you would like to cancel the run and delete the run dates?")) {
		saveFld("0", "isready");
		saveFld("", "runDates");

		var lastRunDate = $F('nextRunDate');
		for(var i=0; i<lastRunDate; i++) {
			var rd = $('runDate'+i);
			if(rd != null) {
				rd.parentNode.parentNode.removeChild(rd.parentNode);
			}
		}
		$('nextRunDate').value = 0;
	} else if(!deleteRundates) {
		saveFld("0", "isready");
	} else {
		return;
	}

	var unlockList = new Array('legal_customer_id', 'legal_doctype_id', 'filenumber', 'controlnumber', 'name', 'estimatedcost');

	for(var i=0; i<unlockList.length; i++) {
		var el = $(unlockList[i]);

		if(el.tagName == "input" || el.tagName == "textarea") {
			el.removeAttribute('readOnly');
		} else {
			el.removeAttribute('disabled');
		}
	}

	var oEditor = FCKeditorAPI.GetInstance('adTextCntrl');
	oEditor.EditorDocument.designMode = 'on';

	var lastRunDate = $F('nextRunDate');
	for(var i=0; i<lastRunDate; i++) {
		var rd = $('runDate'+i);
		if(rd != null) {
			rd.style.backgroundColor = "#FFFFFF";
			rd.style.borderColor = "#FFFFFF";
			rd.parentNode.style.backgroundColor = "#FFFFFF";
			rd.removeAttribute('readOnly');
			rd.onfocus = rdFocus;
		}
	}

	var rd = $$('.runDate input').last();
	if(typeof rd == "undefined" || rd.value != "" && rd.value != "Enter Date Here") {
		addRunDate();
	}

}

function newAd() {
	var seg3 = "<?=$this->uri->segment(3);?>";
	if(seg3 == "") {
		window.location.hash = "";
		window.location.reload(true);
	} else {
		window.location.href = "/legal/entry";
	}
}

function copyAd() {
	window.location.href = "/legal/copyad/" + entry_id;
}

/*window.onkeydown = function() {
	if(event.keyCode==8 || event.keyCode==13) {
		if(event.target.tagName != "INPUT" && event.target.tagName != "TEXTAREA")
			return false;
	}
}*/

function prevAd() {
	window.location.href = '/legal/previousentry/' + entry_id;
}

function nextAd() {
	window.location.href = '/legal/nextentry/' + entry_id;
}

</script>



<style>
	ul.main-menu{

    width: 100%;
    padding: 0;
    margin: 0;
    list-style-type: none;
}

ul.main-menu li a {

    width: 6em;
    text-decoration: none;
    color: #000;
    font-size: 14px;
    background-color: #fff;
    padding: 0.2em 0.6em;
    border-right: 1px solid white;
    padding: 7px;
    border-radius: 1px;
    border: solid #000 1px;
}

ul.main-menu li a:hover {

    width: 6em;
    text-decoration: none;
    color: #000;
    font-size: 14px;
    background-color: #FFFFCC;
    padding: 0.2em 0.6em;
    border-right: 1px solid white;
    padding: 7px;
    border-radius: 1px;
    border: solid #000 1px;
}

ul.main-menu li {
    display: inline;

}

</style>
<ul class="main-menu">
	<li><a href="/legal/">Main Menu</a></li>
	<li><a href="/legal/outputprompt?height=250&width=370" title="Output Date" class="thickbox">Output Date</a></li>
	<li><a href="/legal/calculateprompt?height=250&width=400" title="Calculate Space" class="thickbox">Calculate</a></li>
	<li><a href="/legal/calendarprompt/firstrun?height=230&width=400" class="thickbox">First Run</a></li>
	<li><a href="/legal/calendarprompt/day?height=230&width=400" class="thickbox">Daily</a></li>
	<li><a href="/user/logout/">Log Out</a></li>
	<li><a href="/legal/searchprompt?height=365&width=410" title="Record Search" class="thickbox">Search Ads</a></li>
</ul>

<div style="height: 45px;"></div>

<div id="adTopBar">

<table style="font-size:medium;">
	<tr>
    	<td style="padding-left:2px;">Customer</td>
    	<td style="padding-left:2px;">Job Number</td>
    	<td style="padding-left:2px;">DocType</td>
    	<td style="padding-left:2px;">File Number</td>
    	<td style="padding-left:2px;">Control Number</td>
    	<td style="padding-left:2px;">Name</td>
    </tr>
    <tr>
        <td><?=$customer->generateDropdown("legal_customer_id", "onchange='saveFld(this.value, \"legal_customer_id\");'")?></td>
    	<td><input type="text" name="entryid" id="entryid" value="<?=$ad_info->id?>" readonly="readonly" size="8" style="text-align:center" /></td>
        <td><?=$doctype->generateDropdown("legal_doctype_id", "onchange='saveFld(this.value, \"legal_doctype_id\");'")?></td>
		<td><input type="text" name="filenumber" id="filenumber" value="<?=$ad_info->filenumber?>" size="20" onchange="saveFld(this.value, 'filenumber');" style="font-size:.75em;" /></td>
		<td><input type="text" name="controlnumber" id="controlnumber" value="<?=$ad_info->controlnumber?>" size="18" onchange="saveFld(this.value, 'controlnumber');" style="font-size:.75em;" /></td>
		<td><input type="text" name="name" id="name" value="<?=$ad_info->name?>" size="31" onchange="saveFld(this.value, 'name');" style="font-size:.75em;" /></td>
    </tr>
</table>
</div>

<?php $main_info = $ad_info->adtext; ?>
<?php $main_info = preg_replace('/<p>\p{Z}*<\/p>/u', '', $main_info); ?>

<div id="adText"><div id="adTextToolbar"></div>
<script language="javascript" type="text/javascript">
var oFCKeditor = new FCKeditor('adTextCntrl');
oFCKeditor.BasePath = '/javascript/fckeditor/';
oFCKeditor.Height = 600;
oFCKeditor.Width = 380;
oFCKeditor.ToolbarSet = 'TBJ';
oFCKeditor.Value = "<?=preg_replace('#\r?\n#', '\\r\\n',(addslashes($main_info)))?>";
oFCKeditor.Config['EditorAreaStyles'] = 'p {font-size:10;}';
oFCKeditor.Create();
</script></div>

<div id="hiddenContentEditor" style="margin-bottom: 30px; margin-left: 20px; padding-right:.1in; float:left; height:650px; width:2in; overflow-x:hidden; overflow-y: scroll; text-align:justify; font-size:8px; font-family:'Times New Roman', Times, serif;"></div>

<div id="sideBar">


<?php if($this->session->userdata('resultspage') != ''): ?>
	<div class="entryButton wide"><a href="/legal/returnToResults/">Return to Results</a></div>
<?php endif; ?><br />

<div id="adScrolling">
    <input type="button" name="prevAd" value="&lt;" onclick="prevAd();" />
    <span id="scrollEntryId"><?=$ad_info->id?></span>
    <input type="button" name="nextAd" value="&gt;" onclick="nextAd();" />
</div>

<div id="runDateShortcut">
<input type="button" value="1x Run" onclick="rdShortcut(1)" /><input type="button" value="2x Run" onclick="rdShortcut(2)" /><br />
<input type="button" value="3x Run" onclick="rdShortcut(3)" /><input type="button" value="4x Run" onclick="rdShortcut(4)" /><br />
<? /*
BJ issues run on

*/ ?>
</div>

<input type="hidden" id="nextRunDate" name="nextRunDate" value="0" />
<div id="currentRunDates">

</div>
<!--<div class="entryButton wide"><a href="javascript:;" onclick="saveRundates();">Save Run Dates</a></div>-->

<div id="topButtons">
    <div class="entryButton left" style=""><a href="#run" onclick="setAdLocked(true);">Run</a></div>
    <div class="entryButton right" style=""><a href="#proof" onclick="generateProof();">Proof</a></div>
    <div class="entryButton left" style=""><a href="#cancelrun" onclick="setAdUnlocked(true);">Cancel Run</a></div>
    <div class="entryButton right" style=""><a href="#modify" onclick="setAdUnlocked(false);">Modify Ad</a></div>

    <div style="margin-top: 10px;">
    <div class="entryButton center" style""><a href="#new" onclick="newAd();">New Entry</a></div>
    <div class="entryButton center" style""><a href="#delete" onclick="delEntry();">Delete Entry</a></div>
    <div class="entryButton center" style=""><a href="#copy" onclick="copyAd();">Copy Ad</a></div>

    <div style="text-align:left; margin:15px 0; font-size:.9em">
    	Ad Size: <span style="color: red;" id="adlength"><?=round($ad_info->adlength, 2)?></span><br />
    	Estimated Cost: <input type="text" name="estimatedcost" id="estimatedcost" value="<?=$ad_info->estimatedcost?>" size="7" onchange="saveFld(this.value, 'estimatedcost');" /><br />

    	Proof Notes: <br />
		<textarea onchange="saveFld(this.value, 'proofnotes');" style="width: 197px; height:150px;"><?=$ad_info->proofnotes?></textarea>
    </div>



    </div>
</div>



<div id="bottomButtons"></div>



<div id="samplecal"></div>
