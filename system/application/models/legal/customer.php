<?php

class Customer extends ActiveRecord {
	function __construct() {
		parent::ActiveRecord();
		$this->_table = "legal_customers";
		$this->_belongs_to = array('legal_ads'); //these tables have legal_customer_id
		
		//For generate Dropdown below and for maint forms
		$this->mainField = "businessname";
	}
	
	function generateDropdown($name='doctype_id', $extra="") {
		$all = $this->db->orderby($this->mainField)->get($this->_table);
		$ret = "<select name='$name' id='$name' $extra>\n<option></option>\n";
		
		$main = $this->mainField;
		foreach($all->result() as $cur) {
			if(isset($this->id) && $this->id == $cur->id)
				$ret .= "  <option selected='selected' value='" . $cur->id . "'>" . $cur->$main . "</option>\n";
			else
				$ret .= "  <option value='" . $cur->id . "'>" . $cur->$main . "</option>\n";
		}
		$ret .= "</select>";
		
		return $ret;
	}
}

?>