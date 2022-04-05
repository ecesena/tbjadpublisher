<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//controller load hook
if ( ! function_exists('hook_logincheck')) {
	function hook_logincheck() {
		$CI =& get_instance();
		
		$controller = $CI->uri->segment(1);
		
		if(!can_access($controller)) {
			$CI->session->set_userdata("returnpage", $CI->uri->uri_string());
			redirect('/user/index/denied');
		}
	}
}

// Check if logged in
if ( ! function_exists('is_logged_in')) {
	function is_logged_in() {
		$CI =& get_instance();
		
		return $CI->session->userdata("LoggedIn");
	}
}

// ------------------------------------------------------------------------

// Check for permission to page
if ( ! function_exists('can_access')) {
	function can_access($controller) {
		if($controller == "" || $controller == "user") {
			return true;
		} elseif(is_logged_in()) {
			$CI =& get_instance();
			
			$usr = $CI->session->userdata('user');

			if(in_array($controller, $usr['permissions'])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

// ------------------------------------------------------------------------

// Process login
if ( ! function_exists('login_user')) {
	function login_user($username, $password) {
		$CI =& get_instance();
		
		$q = $CI->db->select('id, username, permissions')->from('users')->where(array('username'=>$username, 'password'=>md5($password)))->get();
		
		if($q->num_rows() == 1) {
			$res = $q->row_array();
			
			$json = json_decode($res['permissions']);
			
			foreach($json as $perm) {
				$permArr[] = $perm->controller;
				if($perm->isDefault)
					$res['defaultController'] = $perm->controller;
			}
			
			$res['permissions'] = $permArr;
			
			$CI->session->set_userdata("LoggedIn", true);
			$CI->session->set_userdata("user", $res);
			return true;
		} else {
			logout_user();
			return false;
		}
	}
}
// ------------------------------------------------------------------------

// Process logout
if ( ! function_exists('logout_user')) {
	function logout_user() {
		$CI =& get_instance();
		
		$CI->session->set_userdata(array("LoggedIn"=>false, "user"=>array("id"=>0,"username"=>"","permissions"=>array()), "returnpage"=>""));
	}
}

// ------------------------------------------------------------------------

// Default page
if ( ! function_exists('getdefaultpage')) {
	function getdefaultpage() {
		$CI =& get_instance();
		
		$usr = $CI->session->userdata('user');
		return "/".$usr['defaultController'];
	}
}



/* End of file user_helper.php */
/* Location: ./system/application/helpers/user_helper.php */