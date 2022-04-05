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
	<li><a href="/user/logout/">Log Out</a></li>
</ul>
<div style="height: 25px;"></div>
<div id="calendar">

<h1><?=($this->uri->segment(3)=="firstrun"?"First Run Calendar":"Daily Calendar")?></h1>
<input type="button" name="prevDate" value="&lt;" onclick="prevDate();" />
<?=$friendly_date?>

<input type="button" name="nextDate" value="&gt;" onclick="nextDate();" />

<div id="calDate" class="scal tinyscal" style="margin:auto;"></div>

<script language="javascript" type="text/javascript">

var dtStr = '<?=$mysql_date?>';
var dateSplit = dtStr.split(/-/g);
var origDate = new Date(dateSplit[0], dateSplit[1]*1-1, dateSplit[2]*1);

samplecal = new scal('calDate', function(newDate) {
	if(newDate.format('YYYY-MM-DD') != origDate.format('YYYY-MM-DD')) {
		window.location.href = '/legal/calendarview/<?=$this->uri->segment(3)?>/' + newDate.format('YYYY-MM-DD');
	}
}, {closebutton:''});
samplecal.setCurrentDate(origDate);

</script>

<div style="float:left; margin-right:30px;">
<div style="margin-bottom:20px;">
    <div style="border: solid 1px #000000; padding: 10px;">Total Ads</div>
    <div style="border: solid 1px #000000; padding: 10px;"><?=$results->num_rows();?></div>
</div>

<? if($num_first_runs != -1): ?>
<div>
    <div style="border: solid 1px #000000">Total First Runs</div>
    <div style="border: solid 1px #000000"><?=$num_first_runs?></div>
</div>
<? endif; ?>
</div>
<div style="height: 30px;"></div>

<div style="float:left; border: solid 1px #000000; padding:3px;">
	<table id="srchResultsTbl" cellpadding="0" cellspacing="0">
    	<tr>
        	<? foreach($tbl as $title=>$fld): ?>
            	<th><a href="#" onclick="sortRecords('<?=$fld?>');"><?=$title?></a></th>
            <? endforeach; ?>
        </tr>
        
        <? foreach($results->result() as $row): ?>
        <tr class="tblData">
        	<? foreach($tbl as $title=>$fld):
				if($fld == "id"):
			?>
            <td><a href="/legal/entry/<?=$row->id?>"><?=$row->id?></a></td>
            <?  else: ?>
            <td><?=$row->$fld?></td>
            <?  endif;
			endforeach; ?>
        </tr>
        <? endforeach; ?>
    </table>
</div>

<? if($this->session->userdata('previous_jobnumber') != 0): ?>
<div class="entryButton wide" style="float:right; clear:both;"><a href="/legal/entry/<?=$this->session->userdata('previous_jobnumber')?>">Return to Previous Ad</a></div>
<? endif; ?>


<script language="javascript" type="text/javascript">

function nextDate() {
	var dtStr = '<?=$mysql_date?>';
	var dateSplit = dtStr.split(/-/g);
	var origDate = new Date(dateSplit[0], dateSplit[1]*1-1, dateSplit[2]*1+1);

	window.location.href = '/legal/calendarview/<?=$this->uri->segment(3)?>/' + origDate.format('YYYY-MM-DD');
}

function prevDate(dtStr) {
	var dtStr = '<?=$mysql_date?>';
	var dateSplit = dtStr.split(/-/g);
	var origDate = new Date(dateSplit[0], dateSplit[1]*1-1, dateSplit[2]*1-1);

	window.location.href = '/legal/calendarview/<?=$this->uri->segment(3)?>/' + origDate.format('YYYY-MM-DD');
}

var currentSort = 'doctype';
var isAsc = false;

function sortRecords(by) {
	if(currentSort == by)
		isAsc = !isAsc;
	else
		isAsc = true;

	records.sort(function(a, b) {
		var x = a[by].toLowerCase();
		var y = b[by].toLowerCase();
		
		if(isAsc)
			return ((x < y) ? -1 : ((x > y) ? 1 : -1));
		else
			return ((x < y) ? 1 : ((x > y) ? -1 : -1));
	});
	currentSort = by;
	
	$$('.tblData').each(function(row) {
		row.parentNode.removeChild(row);
	});
	var destTbl = $('srchResultsTbl');
	
	for(var i=0; i<records.length; i++) {
		var recRow = new Element('tr').addClassName('tblData');

		for(prop in records[i]) {
			var recTd = new Element('td');

			if(prop == 'id') {
				var cellTxt = new Element('a', {'href':'/legal/entry/'+records[i][prop]}).update(records[i][prop]);
				recTd.appendChild(cellTxt);
			} else {
				recTd.update(records[i][prop]);
			}
			
			recRow.appendChild(recTd);
		}
		
		destTbl.appendChild(recRow);
	}
}

var records = new Array();
<?
foreach($results->result() as $row) {
	$rec = "records.push({";
	$first = true;;
	foreach($tbl as $title=>$fld) {
		if($first) {
			$first=false;
			$rec .= $fld . ":'" . $row->$fld . "'";
		} else {
			$rec .= "," . $fld . ":'" . $row->$fld . "'";
		}
	}
	$rec .= "});\r\n";
	echo $rec;
}
?>

function print_r(theObj){
  if(theObj.constructor == Array ||
	 theObj.constructor == Object){
	document.write("<ul>");
	for(var p in theObj){
	  if(theObj[p].constructor == Array||
		 theObj[p].constructor == Object){
document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
		document.write("<ul>");
		print_r(theObj[p]);
		document.write("</ul>");
	  } else {
document.write("<li>["+p+"] => "+theObj[p]+"</li>");
	  }
	}
	document.write("</ul>");
  }
}
</script>