<h3>Please enter search criteria</h3>
<form method="post" action="/legal/searchprocess">
<div id="cont">
	<table>
    	<tr>
        	<td>File Number</td>
        	<td><input type="text" size="24" name="filenumber" id="filenumber" class="inputField1" /></td>
        </tr>
    	<tr>
        	<td>Control Number</td>
        	<td><input type="text" size="24" name="controlnumber" id="controlnumber" class="inputField1" /></td>
        </tr>
    	<tr>
        	<td>Name</td>
        	<td><input type="text" size="24" name="name" id="name" class="inputField1" /></td>
        </tr>
    	<tr>
        	<td>Job Number</td>
        	<td><input type="text" size="24" name="jobnumber" id="jobnumber" class="inputField1" /></td>
        </tr>
    	<tr>
        	<td>Run Date</td>
        	<td>
            	<div id="calDate" class="scal tinyscal" style="margin:auto;"></div>
                <input type="hidden" name="date" id="date" value="" />
			</td>
        </tr>
    </table>

    <div style="clear:both; float:left">
	    <input type="submit" value="Search" name="searchSubmit" class="button" />
    </div>
    <div style="height: 60px;"></div>
</div>

</form>

<script language="javascript" type="text/javascript">
samplecal = new scal('calDate', function(d) {$('date').value = d.format('YYYY-MM-DD')}, {closebutton:''});

</script>