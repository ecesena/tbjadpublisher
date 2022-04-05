<?php

class Ad extends ActiveRecord {
	function __construct() {
		parent::ActiveRecord();
		$this->_table = "legal_ads";
		$this->_belongs_to = array('legal_adlogs', 'legal_rundates'); //these tables have legal_ad_id
		$this->_has_one = array('legal_customers', 'legal_doctypes'); //legal_ads has these ids
	}
	
	function formattedRunDates() {
//		$q = $this->db->select("DATE_FORMAT(date, %m-%d-%Y) AS d")->get_where('legal_rundates', array('legal_ad_id'=>$this->id));
		$q = $this->db->query("SELECT DATE_FORMAT(date, '%m/%d/%Y') AS d FROM legal_rundates WHERE legal_ad_id=?", array($this->id));
		$ret = "";
		foreach($q->result() as $row) {
			if($ret != "")
				$ret .= ", ";

			$ret .= $row->d;
		}
		
		return $ret;
	}
}

?>