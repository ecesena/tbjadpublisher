<?php

class Report extends Controller {

	function Report() {
		parent::Controller();
	}

	function generateRTF($jobNumber) {

		$query = $this->db->query("SELECT * FROM legal_ads WHERE id = $jobNumber");

			foreach ($query->result_array("id") as $row) {

			$mainds = $row['id'];
			$query1 = $this->db->query("SELECT * FROM legal_rundates WHERE legal_ad_id = $jobNumber");
			$maind = $row['adtext'];
				$items = array();
				foreach ($query1->result_array("date") as $row1) {
						$items[] = date('m/d/Y', strtotime($row1['date']));
				}

				$htmlStr = $maind . implode(", ",$items);

				$htmlStr = str_replace("<p>\xc2\xa0</p>",' ',$htmlStr);


				//echo $htmlStr;

			}

			$this->load->helper("/rtfgen/3rtf_helper.php");
			generateRTF($htmlStr, "Test Doc", "Proof For ".$mainds);
	}
}

?>