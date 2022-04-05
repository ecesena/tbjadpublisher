<?php

class Maintenance extends Controller {

	function Maintenance() {
		parent::Controller();
	}
	
	function index() {
		$data['maintPages'] = array('Customer'=>'/maintenance/view/legal/customer', 'Users'=>'/maintenance/view/system/user');
		$data['title_for_layout'] = 'Please select a maintenance page';
		
		$this->load->library('layout', array("layout"=>"layout_main"));
		$this->layout->view("maintenance/maintlist", $data);
	}
	
	function view($controller, $page) {
		if(can_access($controller)) {
			$this->load->library('layout', array("layout"=>"layout_main"));
			
			$data['title_for_layout'] = "Manage $page for $controller";
			$data['dropdownHTML'] = $this->_generate_dropdown($controller, $page);

			$data['isPrompt'] = false;
			
			$this->layout->view("maintenance/$controller/$page", $data);
		} else {
			echo "You do not have permission to manage that controller.";
		}
	}
	
	function get($controller, $page, $id) {
		if(can_access($controller)) {
			$this->load->model("$controller/$page");
			$cur = $this->$page->find($id);
			
			$retArr = array();
			foreach($this->db->list_fields($cur->_table) as $field) {
				$retArr[$field] = $cur->$field;
			}
			
			$retArr["error"] = "";
			header("Content-Type: application/json");
			echo json_encode($retArr);
		} else {
			header("Content-Type: application/json");
			echo json_encode(array("error"=>"Access Denied"));
		}
	}
	
	function _generate_dropdown($controller, $page) {
		$retStr = "<select name='maintSelector' id='maintSelector'>";
		
		$retStr .= "<option></option>";
		$retStr .= "<option value='0'>&lt;New&gt;</option>";
		
		$this->load->model("$controller/$page");
		
		$t = new $page();
		$mainField = $t->mainField;
		
		$allRecs = $this->$page->find_all($mainField);
		foreach($allRecs as $row) {
			$retStr .= "<option value='{$row->id}'>{$row->$mainField}</option>";
		}
		
		$retStr .= "</select>";
		return $retStr;
	}
	
	function prompt($controller, $page) {
		if(can_access($controller)) {
			$this->load->library('layout', array("layout"=>"layout_prompt"));

			$this->load->model("$controller/$page");
			$t = new $page();
			$data['mainField'] = $t->mainField;
			
			$data['isPrompt'] = true;
			
			$this->layout->view("maintenance/$controller/$page", $data);
		} else {
			echo "You do not have permission to manage that controller.";
		}
	}

	function save($controller, $page) {
		if(can_access($controller)) {
			$this->load->model($controller . "/" . $page);
			if($this->input->post('id') != 0) {
				$obj = $this->$page->find($this->input->post('id'));
			} else {
				$obj = new $page;
				$obj->id = 0;
			}
			
			$fldList = $this->db->list_fields($obj->_table);
			foreach($fldList as $fld) {
				if($fld != "id" && $this->input->post($fld)) {
					$obj->$fld = $this->input->post($fld);
				}
			}
			
			if($obj->id == 0) {
				$obj->save();
			} else {
				$obj->update();
			}

			$this->load->model("$controller/$page");
			$t = new $page();
			$mainFld = $t->mainField;
			
			$retArr = array("id"=>$obj->id, "fldName"=>$this->input->post($mainFld), "error"=>"");
			header("Content-Type: application/json");
			echo json_encode($retArr);
		} else {
			header("Content-Type: application/json");
			echo json_encode(array("error"=>"Access Denied"));
		}
	}
	
	function delete($controller, $page, $id) {
		if(can_access($controller)) {
			$this->load->model($controller . "/" . $page);
			$obj = $this->$page->find($id);
			$obj->delete();
		} else {
			echo "You do not have permission to access this page.";
		}
	}
}

/* End of file maintenance.php */
/* Location: ./system/application/controllers/maintenance.php */
?>