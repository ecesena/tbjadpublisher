<?php

class User extends ActiveRecord {
	function __construct() {
		parent::ActiveRecord();
		$this->_belongs_to = array('legal_exports', 'legal_adlogs'); //these tables have legal_customer_id
		
		//For maint forms
		$this->mainField = "username";
	}
}

?>