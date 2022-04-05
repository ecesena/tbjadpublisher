<?php

class Legal extends Controller {

	function Legal() {
		parent::Controller();	
	}
	
	//Main menu
	function index() {
		$this->load->library('layout', array("layout"=>"layout_main"));
		
		$data['title_for_layout'] = "Main Menu";
		
		$this->layout->view('legal/menu_view', $data);
	}
	
	//Enter / Edit ads
	function entry($jobNumber = 0) {
		$this->session->set_userdata('previous_jobnumber', $jobNumber);
		
		$this->load->library('layout', array("layout"=>"layout_main"));
		
		$data['title_for_layout'] = "Ad Entry";
		$data['use_small_header'] = true;
		
		$data['extra_header_content'] = "<script type='text/javascript' src='/javascript/fckeditor/fckeditor.js'></script>";
		
		$this->load->model('legal/ad');
		$this->load->model('legal/rundate');
		$this->load->model('legal/doctype');
		$this->load->model('legal/customer');
		
		if($jobNumber != 0) {
			$data['ad_info'] = $this->ad->find($jobNumber);
			if($data['ad_info']->legal_doctype_id == 0)
				$data['doctype'] = new Doctype();
			else
				$data['doctype'] = $this->doctype->find($data['ad_info']->legal_doctype_id);

			$data['customer'] = $this->customer->find($data['ad_info']->legal_customer_id);
			if($data['customer'] == NULL)
				$data['customer'] = new Customer();
			
			$data['rundates'] = $this->rundate->find_all_by_legal_ad_id($jobNumber);
		} else {
			$data['ad_info'] = new stdClass();
			$data['ad_info']->id = 0;
			$data['ad_info']->legal_customer_id = "";
			$data['ad_info']->legal_doctype_id = 0;
			$data['ad_info']->filenumber = "";
			$data['ad_info']->controlnumber = "";
			$data['ad_info']->name = "";
			$data['ad_info']->adtext = "";
			$data['ad_info']->adlength = "";
			$data['ad_info']->estimatedcost = "";
			$data['ad_info']->proofnotes = "";
			$data['ad_info']->isready = 0;
			
			$data['doctype'] = new Doctype();
			$data['customer'] = new Customer();

			$data['rundates'] = NULL;

			$this->session->set_userdata('resultspage', '');

		}
		
		$this->layout->view('legal/entry', $data);
	}
	
	function save_entry($jobNumber) {
		$this->load->model('legal/ad');
		$this->load->model('legal/rundate');
		
		$fld = $this->input->post('fld');
		$val = $this->input->post('val');
		
		if($jobNumber != 0)
			$ad = $this->ad->find($jobNumber);
		else {
			$ad = new Ad();
			$ad->adlength = 0;
		}
		
		if($fld == "runDates") {
			$rdates = explode(",", $val);

			if($jobNumber == 0) {
				$ad->adtext = "";
				$ad->save();
				$jobNumber = $ad->id;
			}
			
			//$this->db->delete('legal_rundates', array("legal_ad_id"=>$jobNumber));
			
			$qrds = $this->db->select('date')->where("legal_ad_id", $jobNumber)->get('legal_rundates');
			
			$rds = array();
			foreach($qrds->result() as $row) {
				$rds[] = $row->date;
			}
			
			for($i=0; $i<count($rdates); $i++) {
				if($rdates[$i] != "") {
					$rdpos = array_search(human_to_mysql($rdates[$i]), $rds);
					if($rdpos !== false) {
						$rds[$rdpos] = "";
					} else {
						$rd = new Rundate();
						$rd->legal_ad_id = $jobNumber;
						$rd->date = human_to_mysql($rdates[$i]);
						
						if($i == 0)
							$rd->isfirstrun = 1;
						else
							$rd->isfirstrun = 0;
						
						$rd->save();
					}
				}
			}
			
			foreach($rds as $rd) {
				$this->db->query("DELETE FROM legal_rundates WHERE date=? AND legal_ad_id=? LIMIT 1", array($rd, $ad->id));
			}
		} else {
			$ad->$fld = $val;
			
			if($fld == "adtext") {
				$this->load->helper("/fontcalc/fontcalc_helper");
				
				$ad->adlength = calculateWidth($val);
			}
			
			if($jobNumber != 0)
				$ad->update();
			else
				$ad->save();
		}

		$this->session->set_userdata('previous_jobnumber', $ad->id);

		echo $ad->id . "~" . round($ad->adlength, 2);
	}
	
	function delete_entry($jobNumber) {
		$this->load->model('legal/ad');
		$ad = $this->ad->find($jobNumber);
		$ad->legal_customer_id = 0;
		$ad->legal_doctype_id = 0;
		$ad->filenumber = "";
		$ad->controlnumber = "";
		$ad->name = "";
		$ad->adtext = "";
		$ad->adlength = "";
		$ad->proofnotes = "";
		$ad->estimatedcost = "";
		$ad->isready = 0;
		
		$ad->update();
		//$this->db->delete('legal_ads', array('id'=>$jobNumber));
		$this->db->delete('legal_rundates', array('legal_ad_id'=>$jobNumber));
		redirect("/legal/entry/$jobNumber");
	}
	
	//Create / View batches
	function copyad($jobNumber) {
		if($jobNumber == 0) {
			redirect("/legal/entry/");
		} else {
			$this->load->model('legal/ad');
			$this->load->model('legal/rundate');
			
			$srcAd = $this->ad->find($jobNumber);
			
			$newAd = new Ad();
			$newAd->legal_customer_id = $srcAd->legal_customer_id;
			$newAd->legal_doctype_id = $srcAd->legal_doctype_id;
			
			$newAd->save();
			
			$srcAdRundates = $this->rundate->find_all_by_legal_ad_id($srcAd->id);
			for($i=0; $i<count($srcAdRundates); $i++) {
				$newRd = new Rundate();
				$newRd->legal_ad_id = $newAd->id;
				$newRd->date = $srcAdRundates[$i]->date;
				$newRd->isfirstrun = $srcAdRundates[$i]->isfirstrun;
				
				$newRd->save();
			}
			
			redirect("/legal/entry/".$newAd->id);
		}
	}

	function previousentry($jobNumber) {
		$sql = "SELECT id FROM legal_ads WHERE id<$jobNumber ORDER BY id DESC LIMIT 1;";
		$q = $this->db->query($sql);
		
		if($q->num_rows == 1) {
			$row = $q->row();
			redirect("/legal/entry/".$row->id);
		} else {
			$row = $q->row();
			redirect("/legal/entry/".$jobNumber);
		}
	}
	
	function nextentry($jobNumber) {
		$sql = "SELECT id FROM legal_ads WHERE id>$jobNumber ORDER BY id ASC LIMIT 1;";
		$q = $this->db->query($sql);
		
		if($q->num_rows == 1) {
			$row = $q->row();
			redirect("/legal/entry/".$row->id);
		} else {
			$row = $q->row();
			redirect("/legal/entry/".$jobNumber);
		}
	}
	
	function rdshortcutprompt() {
		$this->load->library('layout', array("layout"=>"layout_prompt"));

		$this->layout->view('legal/rdshortcut_prompt');
	}
	
	//Prompt user for date to output
	function outputprompt() {
		$this->load->library('layout', array("layout"=>"layout_prompt"));

		$this->layout->view('legal/output_prompt');
	}
	
	//Create RTF file and ouput for download
	function outputfile($date) {
		
		$q = $this->db->select('legal_ads.*, legal_rundates.isfirstrun, remainingRuns')->from('legal_ads')->join('legal_rundates', 'legal_ads.id=legal_rundates.legal_ad_id')->join('(SELECT legal_ad_id, COUNT(*) AS remainingRuns FROM legal_rundates WHERE date>"' . $date . '" GROUP BY legal_ad_id) AS subqry', 'legal_ads.id=subqry.legal_ad_id', 'left')->where('legal_rundates.date', $date)->where('legal_ads.isready', 1)->order_by('legal_ads.legal_doctype_id')->order_by('legal_rundates.isfirstrun', "DESC")->order_by('remainingRuns')->get();
		
		$this->load->model("legal/ad");
		$this->load->model("legal/doctype");

		$htmlStr = "";
		$curDocType = 0;
		
		$noLine = false;
		foreach($q->result() as $row) {
			if($row->legal_doctype_id != $curDocType) {
				$curDocType = $row->legal_doctype_id;			

				$doc = $this->doctype->find($curDocType);
				if($htmlStr != "")
					$htmlStr .= "<BR>-----------------------------------<BR>";
				$htmlStr .= "<BR><BR><H3>" . $doc->name . "</H3><BR>";
				$noLine = true;
			}
			
			if(!$noLine)
				$htmlStr .= "<BR>-----------------------------------<BR><BR>";
			$noLine = false;
			
			if($row->isfirstrun == 1)
				$htmlStr .= "  (1)<BR>";
			
			$htmlStr .= preg_replace('/<br\s*(|\/)>\s*<\/p>$/', "</p>", $row->adtext);
			
			$ad = $this->ad->find($row->id);
			$htmlStr .= "  " . $ad->formattedRunDates();
			
//			$htmlStr .= "<BR><BR>";
		}
		//echo $htmlStr;
//		$htmlStr .= "<BR>-----------------------------------<BR><BR>";
		$this->load->helper("/rtfgen/rtf_helper.php");
		generateRTF($htmlStr, "Test Doc", "Output For ".$date);
	}
	
	//Prompt user for date to calculate
	function calculateprompt() {
		$this->load->library('layout', array("layout"=>"layout_prompt"));

		$this->layout->view('legal/calculate_prompt');
	}
	
	//Display page count for date
	function calculateshow() {
		$date = $this->input->post('date');

		$q = $this->db->select('legal_ads.*, legal_rundates.isfirstrun')->from('legal_ads')->join('legal_rundates', 'legal_ads.id=legal_rundates.legal_ad_id')->where('legal_rundates.date', $date)->order_by('legal_ads.legal_doctype_id')->get();
		
		$numAds = $q->num_rows();
		
		$totalAdSize = 0;
		foreach($q->result() as $row) {
			$totalAdSize += $row->adlength;
		}

		$this->load->helper("/fontcalc/fontcalc_helper");

		echo $numAds . "|" . inchesToPages($totalAdSize);
	}
	
	//Prompt user for search criteria
	function searchprompt() {
		$this->load->library('layout', array("layout"=>"layout_prompt"));

		$this->layout->view('legal/search_prompt');
	}
	
	//Perform search, redirect to entry/jobid if one found, detail list if multiple
	function searchprocess() {
		$this->load->library('layout', array("layout"=>"layout_main"));
		
		$data['title_for_layout'] = "Search Results";
		$data['use_small_header'] = false;
		
		if($this->input->post('date') != "") {
			$rdate = $this->input->post('date');
			$q = $this->db->select('legal_ads.*')->from('legal_ads')->join('legal_rundates', 'legal_ads.id=legal_rundates.legal_ad_id')->where('legal_rundates.date', $rdate)->order_by('legal_ads.id', 'desc')->get();
			
		} elseif($this->input->post('filenumber') != "") {
			$q = $this->db->like('filenumber', $this->input->post('filenumber'))->order_by('legal_ads.id', 'desc')->get('legal_ads');
			
		} elseif($this->input->post('controlnumber') != "") {
			$q = $this->db->like('controlnumber', $this->input->post('controlnumber'))->order_by('legal_ads.id', 'desc')->get('legal_ads');
			
		} elseif($this->input->post('name') != "") {
			$q = $this->db->like('name', $this->input->post('name'))->order_by('legal_ads.id', 'desc')->get('legal_ads');
			
		} elseif($this->input->post('jobnumber') != "") {
			$q = $this->db->where('id', $this->input->post('jobnumber'))->order_by('legal_ads.id', 'desc')->get('legal_ads');
			
		} elseif($this->session->userdata('resultspage') == "search") {
			$q = $this->db->query($this->session->userdata('lastsearch'));
			
		} else {
			$this->session->set_userdata('errs', 'You did not enter any search criteria.');
			redirect('/');
		}
		
		$this->session->set_userdata('lastsearch', $this->db->last_query());
		$this->session->set_userdata('resultspage', 'search');
		
		$custListQ = $this->db->get('legal_customers');
		$custList[0] = "";
		foreach($custListQ->result() as $row) {
			$custList[$row->id] = $row->businessname;
		}
		$data['custList'] = $custList;
		
		$docTypeQ = $this->db->get('legal_doctypes');
		$docType[0] = "";
		foreach($docTypeQ->result() as $row) {
			$docType[$row->id] = $row->name;
		}
		$data['docType'] = $docType;
		
		if($q->num_rows() == 1) {
			redirect('/legal/entry/'.$q->row()->id);
		} elseif($q->num_rows() > 1) {
			$data['search_results'] = $q;
			$this->layout->view('legal/search_results', $data);
		} else {
			$this->session->set_userdata('errs', 'No records found.');
			redirect('/');
		}
	}
	
	function returnToResults() {
		if($this->session->userdata('resultspage') == "search") {
			redirect('/legal/searchprocess/');
		} elseif($this->session->userdata('resultspage') == "calendar") {
			redirect('/legal/calendarview/' . $this->session->userdata('calendar') . '/' . $this->session->userdata('calendardate'));
		}
	}
	
	//Prompt user for calendar date
	function calendarprompt($destCalendar) {
		$this->load->library('layout', array("layout"=>"layout_prompt"));

		$this->layout->view('legal/calendar_prompt');
	}
	
	//Display calendar to user
	function calendarview($calendar, $date="") {
		if($date == "") {
			$date = $this->input->post('date');
			$data['friendly_date'] = mysql_to_human($this->input->post('date'));
		} else {
			$date_parts = explode("-", $date);
			$data['friendly_date'] = $date_parts[1] . "-" . $date_parts[2] . "-" . $date_parts[0];
		}
		$data['mysql_date'] = $date;
		
		$this->session->set_userdata('resultspage', 'calendar');
		$this->session->set_userdata('calendar', $calendar);
		$this->session->set_userdata('calendardate', $date);

		if($calendar == "day") {
			$data['num_first_runs'] = $this->db->from('legal_rundates')->where('legal_rundates.date', $date)->where('isfirstrun', 1)->count_all_results();
			
			
			$data['results'] = $this->db->select('legal_ads.*, legal_rundates.isfirstrun, legal_customers.businessname, legal_doctypes.name AS doctype')->from('legal_ads')->join('legal_rundates', 'legal_ads.id=legal_rundates.legal_ad_id')->join('legal_customers', 'legal_ads.legal_customer_id=legal_customers.id')->join('legal_doctypes', 'legal_ads.legal_doctype_id=legal_doctypes.id')->where('legal_rundates.date', $date)->where('legal_ads.isready', 1)->order_by('legal_ads.legal_doctype_id')->get();
			
			$data['tbl'] = array(
				"Job Number"=>"id",
				"Doc Type"=>"doctype",
				"Customer"=>"businessname",
				"File Number"=>"filenumber",
				"Control Number"=>"controlnumber",
				"Name"=>"name"
			);
		} elseif($calendar == "firstrun") {
			$data['num_first_runs'] = -1;			
			
			$data['results'] = $this->db->select('legal_ads.*, legal_rundates.isfirstrun, legal_customers.businessname, legal_doctypes.name AS doctype')->from('legal_ads')->join('legal_rundates', 'legal_ads.id=legal_rundates.legal_ad_id')->join('legal_customers', 'legal_ads.legal_customer_id=legal_customers.id')->join('legal_doctypes', 'legal_ads.legal_doctype_id=legal_doctypes.id')->where('legal_rundates.date', $date)->where('legal_rundates.isfirstrun', 1)->where('legal_ads.isready', 1)->order_by('legal_ads.legal_doctype_id')->get();
			
			$data['tbl'] = array(
				"Job Number"=>"id",
				"Doc Type"=>"doctype",
				"Customer"=>"businessname",
				"File Number"=>"filenumber",
				"Control Number"=>"controlnumber",
				"Name"=>"name"
			);
		}



		$data['title_for_layout'] = "Calendar";
		
		$this->load->library('layout', array("layout"=>"layout_main"));
		$this->layout->view('legal/calendar_view', $data);
	}
	
	function generateproof($jobNumber) {
		require("classes/fpdf/pdf.php");
		
		$this->load->model('legal/ad');
		
		$ad = $this->ad->find($jobNumber);

		
		$adText = str_replace(array("\r\n", "\r", "\n"), '', $ad->adtext);
		$adText .= "<br>  " . $ad->formattedRunDates();
		$adText = str_replace(array("<p>", "</p>", "<br>"), array("\n", '', "\n"), $adText);
		$adText = str_replace("Â", '', $adText);
		$adText = str_replace(array('<STRONG>', '<EM>', '<U>', '<strong>', '<em>', '<u>', '</STRONG>', '</EM>', '</U>', '</strong>', '</em>', '</u>'), '', $adText);;
		
		$pdf=new PDF('P', 'in');
		$pdf->AddPage();
		$pdf->SetTopMargin(.75);
//	    $pdf->IncludeJS("this.print({bShrinkToFit:false});");
		
		$pdf->Image('images/bj_logo.gif', .5, .75, 3.5);
		
		$pdf->SetFont('Times','', 24);
		$pdf->SetXY(.5, 1.75);
		$pdf->Cell(3.5, 0, "Proof Sheet", 0, 0, 'C');

		if($ad->estimatedcost != "") {
			$pdf->setXY(1.5, $pdf->getY() + .5);
			$pdf->SetFont('Times','B', 12);
			$pdf->Cell(1.1, .15, "Cost Estimate: ", 0, 0, 'L');
			
			$pdf->SetFont('Times','',12);
			$pdf->Cell(1, .15,  $ad->estimatedcost);
		}
		
		$pdf->SetFont('Times','', 20);
		$pdf->SetXY(.5, 4);
		$pdf->Cell(3.5, 0, "Proof OK's by:", 0, 0, 'C');
		$pdf->Line(.75, 4.5, 3.75, 4.5);

		$pdf->Line(.5, 4.75, 4.25, 4.75);
		
		$pdf->SetFont('Times','I', 12);
		$pdf->SetXY(.5, 5);
		$pdf->MultiCell(3.5, .15, "Note: Hyphenation and column inch may\r\n fluctuate according to actual page column.", 0, 'C');

		$pdf->SetFont('Times','', 12);
		$pdf->setXY(.6, $pdf->getY() + .35);
		$pdf->MultiCell(3.5, .25, $ad->proofnotes, 0, 'L');
		
		
		$pdf->Line(.5, 10, 4.25, 10);
		
		$pdf->SetFont('Times','', 14);
		$pdf->SetXY(.4, -1.65);
		$pdf->MultiCell(3.75, .2, "The Business Journal\r\n1315 Van Ness, Suite 200, Fresno, CA 93721\r\n(559) 490-3400 • (559) 490-3531", 0, 'C');
		
		$pdf->Line(4.25, .5, 4.25, 10.75);
		
		$pdf->SetXY(5.5, .75);
		
		$pdf->SetFillColor(0);
		$pdf->SetTextColor(255);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(2, .3, "Job Number: " . $ad->id, 0, 2, 'C', true);


		$pdf->SetFont('Times','',8);
		$pdf->SetTextColor(0);
		$pdf->SetCol(2);
		$pdf->y0 = $pdf->tMargin;
		$pdf->MultiCell(2,.15,$adText);
//		$pdf->WriteHTML($adText);
		

		$pdf->Output('Proof Sheet Job Number ' . $ad->id . '.pdf', 'I');

	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */?>