<h3>Please enter Output Date</h3>
<div id="cont" style="text-align:center;">
    Run Date<br />
    <div id="calcDate" class="scal tinyscal" style="margin:auto;"></div>

    <div style="clear:both; float:right">
    	<input type="hidden" name="date" id="date" value="" />
	    <input type="button" name="generateRTF" id="generateRTF" value="Generate RTF" onclick="window.location.href = '/legal/outputfile/' + $F('date'); tb_remove();" class="button" />
    </div>
</div>


<script language="javascript" type="text/javascript">

samplecal = new scal('calcDate', function(d) {$('date').value = d.format('YYYY-MM-DD')}, {closebutton:''});
$('date').value = new Date().format('YYYY-MM-DD');
</script>