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
<div id="cont">
    <div style="clear:both; float:right">
	    <input type="button" value="Save  " onclick="<?= ($isPrompt?"savePopup();":"save();") ?>" class="button" /><? if(!$isPrompt): ?><br /> <input type="button" value="Delete" onclick="deleteRecord();" class="button" /><? endif; ?>
    </div>
    <div style="height: 40px;"></div>
	<table>
    	<tr>
          <td>Business Name</td>
          <td><input type="text" size="24" name="businessname" id="businessname" class="inputField" /></td>
        </tr>
    	<tr>
          <td>Address 1</td>
          <td><input type="text" size="24" name="address1" id="address1" class="inputField" /></td>
        </tr>
        <tr>
          <td>Address 2</td>
          <td><input type="text" size="24" name="address2" id="address2" class="inputField" /></td>
        </tr>
        <tr>
          <td>City</td>
          <td><input type="text" size="24" name="city" id="city" class="inputField" /></td>
        </tr>
    	<tr>
    	  <td>State</td>
    	  <td><input type="text" size="24" name="state" id="state" class="inputField" /></td>
  	  	</tr>
    	<tr>
    	  <td>Zip</td>
    	  <td><input type="text" size="24" name="zip" id="zip" class="inputField" /></td>
  	  	</tr>
    	<tr>
    	  <td>Email</td>
    	  <td><input type="text" size="24" name="email" id="email" class="inputField" /></td>
  	  	</tr>
    	<tr>
    	  <td>Email 2</td>
    	  <td><input type="text" size="24" name="email2" id="email2" class="inputField" /></td>
	  	</tr>
    	<tr>
    	  <td>Phone</td>
    	  <td><input type="text" size="24" name="phone" id="phone" class="inputField" /></td>
	  	</tr>
    	<tr>
    	  <td>Phone 2</td>
    	  <td><input type="text" size="24" name="phone2" id="phone2" class="inputField" /></td>
  		</tr>
    	<tr>
    	  <td>Fax</td>
    	  <td><input type="text" size="24" name="fax" id="fax" class="inputField" /></td>
		</tr>
    	<tr>
    	  <td>Contact Name</td>
    	  <td><input type="text" size="24" name="contactname" id="contactname" class="inputField" /></td>
  	  	</tr>
    	<tr>
          <td>Website</td>
    	  <td><input type="text" size="24" name="website" id="website" class="inputField" /></td>
        </tr>
    </table>
	
    <input type="hidden" name="id" id="id" value="0" />
</div>

<script language="javascript" type="text/javascript">
var controller = "<?=$this->uri->segment(3);?>";
var page = "<?=$this->uri->segment(4);?>";

</script>




