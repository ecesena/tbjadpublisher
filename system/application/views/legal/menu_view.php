<div id="mainMenu">
<h1>Main Menu</h1>

<div class="menuButton full"><a href="/legal/entry">Create New Ad</a></div>
<br />
<hr />
<br />

<div class="menuButton left"><a href="/maintenance/view/legal/customer">Customers</a></div>
<div class="menuButton right"><a href="/maintenance/view/system/user">Users</a></div>

<br />
<br />

<div class="menuButton full"><a href="/user/logout/">Log Out</a></div>

<br />
<hr />
<br />

<div class="menuButton left"><a href="/legal/outputprompt?height=250&width=370" title="Output Date" class="thickbox">Output Date</a></div>
<div class="menuButton right"><a href="/legal/calculateprompt?height=250&width=400" title="Calculate Space" class="thickbox">Calculate Space</a></div>
<div class="menuButton center"><a href="/legal/searchprompt?height=365&width=410" title="Record Search" class="thickbox">Search</a></div>

<br />

<h2>Calendars</h2>
<div class="menuButton full"><a href="/legal/calendarprompt/firstrun?height=230&width=400" class="thickbox">First Run Calendar</a></div>
<div class="menuButton full"><a href="/legal/calendarprompt/day?height=230&width=400" class="thickbox">Daily Calendar</a></div>


</div>

<script language="javascript" type="text/javascript">

function clearAllInputs() {
	var inps = document.getElementsByTagName('input');
	
	for(var i=0; i<inps.length; i++) {
		if(inps[i].getAttribute('type') == "text") {
			inps[i].setAttribute('value', '');
		}
	}
}
</script>