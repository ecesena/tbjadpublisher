<h3>Please enter the date you wish to view</h3>

<div id="cont" style="text-align:center;">
    <div id="calDate" class="scal tinyscal" style="margin:auto;"></div>


    <div style="clear:both; float:right">
    	<input type="hidden" name="date" id="date" value="" />
	    <input type="button" name="calendarSubmit" id="calendarSubmit" value="View" onclick="window.location.href = '/legal/calendarview/<?=$this->uri->segment(3)?>/' + $F('date'); tb_remove();" class="button" />
    </div>
</div>

<script language="javascript" type="text/javascript">
samplecal = new scal('calDate', function(d) {$('date').value = d.format('YYYY-MM-DD')}, {closebutton:''});

</script>