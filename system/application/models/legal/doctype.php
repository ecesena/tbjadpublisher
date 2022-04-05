<?php

class Doctype extends ActiveRecord {
	function __construct() {
		parent::ActiveRecord();
		$this->_table = "legal_doctypes";
		$this->_belongs_to = array('legal_ads'); //these tables have legal_doctype_id
	}
	
	function generateDropdown($name='doctype_id', $extra="") {
		$all = $this->db->get($this->_table);
		$ret = "<select name='$name' id='$name' $extra>\n<option></option>\n";
		
		foreach($all->result() as $cur) {
			if(isset($this->id) && $this->id == $cur->id)
				$ret .= "  <option selected='selected' value='" . $cur->id . "'>" . $cur->name . "</option>\n";
			else
				$ret .= "  <option value='" . $cur->id . "'>" . $cur->name . "</option>\n";
		}
		$ret .= "</select>";
		
		return $ret;
	}
}

?>