<?php

class Rundate extends ActiveRecord {
	function __construct() {
		parent::ActiveRecord();
		$this->_table = "legal_rundates";
		$this->_has_many = array('legal_rundates'); //these tables have legal_rundate_id
	}
}

?>