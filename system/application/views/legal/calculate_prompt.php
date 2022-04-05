<h3>Calculate Total Ad Size</h3>
<div id="cont" style="text-align:center;">
    Run Date<br />
    <div id="calcDate" class="scal tinyscal" style="margin:auto;"></div>

    <div style="clear:both; float:right">
    	<input type="hidden" name="date" id="date" value="" />
	    <input type="button" name="calcSpace" id="calcSpace" value="Calculate" class="button" onclick="getCalc();" />
    </div>
</div>

<script language="javascript" type="text/javascript">

samplecal = new scal('calcDate', function(d) {$('date').value = d.format('YYYY-MM-DD')}, {closebutton:''});

function getCalc() {
	var date = $F('date');
	
	removeChildNodes(document.getElementById('cont'));

	new Ajax.Request("/legal/calculateshow", {
		method: "post",
		parameters: {"date":date},
		onSuccess: function(trans) {
			var cont = document.getElementById('cont')
			cont.style.marginTop = "75px";
			
			var t = trans.responseText.split(/\|/);
			var numAds = t[0];
			var adLength = t[1];
			
			cont.appendChild(document.createTextNode("Number of Ads: " + numAds));
			cont.appendChild(document.createElement('br'));
			cont.appendChild(document.createTextNode("Total Length of Ads: " + adLength + " Pages"));
		}
	});
}
</script>