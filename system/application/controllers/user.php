<?php

class User extends Controller {

	function User() {
		parent::Controller();
	}
	
	//display login screen if user isn't logged in, call _login_redirect if logged in
	function index($error="") {
		if(is_logged_in() && $error == "") {
			$this->_login_redirect();
		} else {
			$this->load->library('layout', array("layout"=>"layout_main"));
			
			$data['title_for_layout'] = "Please Log In";
			
			switch($error) {
				case "failed":
					$data['error_messages'][] = "You have entered an incorrect Username or Password. Please try again.";
					break;
				case "timeout":
					$data['error_messages'][] = "Your session has expired. Please log in again.";
					break;
				case "denied":
					$data['error_messages'][] = "You do not have permission to access that page.";
					break;
			}
			$this->layout->view('login_view', $data);
		}
	}
	
	//process login, if pass call _login_redirect, fail redirect to index with error
	function login() {
		if(login_user($this->input->post('username'),$this->input->post('password'))) {
			$this->_login_redirect();
		} else {
			redirect("/user/index/failed");
		}
	}
	
	//process logout and redirect to index
	function logout() {
		logout_user();
		redirect("");
	}
	
	//determine which controller to send the user to and send them
	//if returnpage in session is set, go there
	function _login_redirect() {
		if($this->session->userdata('returnpage') != "") {
			$pg = $this->session->userdata('returnpage');
			$this->session->unset_userdata('returnpage');
			redirect($pg);
		} else {
			redirect(getdefaultpage());
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
?>