
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
</ul>

<div style="height: 40px;"></div>

<h3>Enter the Customer Information below</h3>
<?
if(!$isPrompt) {
	echo $dropdownHTML;
}
?>
<style type="text/css">
#cont {
	text-align:left;
}
</style>
<div id="cont">
    <div style="clear:both; float:right">
	    <input type="button" value="Save  " onclick="<?=($isPrompt?"savePopup();":"save();")?>" class="button" /><? if(!$isPrompt): ?><br /> <input type="button" value="Delete" onclick="deleteRecord();" class="button" /><? endif; ?>
    </div>
	<table>
    	<tr>
          <td>Username</td>
          <td><input type="text" size="24" name="username" id="username" class="inputField" /></td>
        </tr>
    	<tr>
          <td>Password</td>
          <td><input type="text" size="24" name="newPassword" id="newPassword" class="inputField" placeholder="Enter new password" /></td>

        </tr>

    </table>
   
    <input type="hidden" name="id" id="id" value="0" />
    <input type="hidden" name="permissions" id="permissions" value='' class="inputField" />
    <input type="hidden" name="password" id="password" value='' class="inputField" />
</div>
<script language="javascript" type="text/javascript" src="/javascript/md5.js"></script>

<script language="javascript" type="text/javascript">
var controller = "<?=$this->uri->segment(3);?>";
var page = "<?=$this->uri->segment(4);?>";

function onBeforeSave(params) {
	if(params.newPassword != "") {
		params.password = hex_md5(params.newPassword);
		$('newPassword').value = "";
	}
	
	if(params.permissions == "")
		params.permissions = '[{"controller":"legal","isDefault":1},{"controller":"maintenance","isDefault":0},{"controller":"system","isDefault":0}]';
}
</script>




