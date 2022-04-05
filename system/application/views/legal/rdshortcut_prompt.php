<div id="cont" style="text-align:center;">
    Run Date<br />
    <div id="calcDate" class="scal tinyscal" style="margin:auto;"></div>
</div>

<script language="javascript" type="text/javascript">
numRuns = <?=$this->uri->segment(3)?>;

samplecal = new scal('calcDate', function(baseDate) {
	tb_remove();
	var contDiv = document.getElementById('currentRunDates');
	var contDivClone = contDiv.cloneNode(false);
	contDiv.parentNode.replaceChild(contDivClone, contDiv);
	
	document.getElementById('nextRunDate').value = 0;
	
	for(var i=0; i<numRuns; i++) {
		addRunDate(formatDate(baseDate));
		baseDate.setDate(baseDate.getDate() + 7);
	}
	addRunDate();
	saveRundates();
}, {closebutton:''});

</script>