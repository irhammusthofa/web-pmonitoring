<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->auth();
		$this->load->model('m_user');
	}
	public function index()
	{
		$this->load->view('login');
	}
	public function logout(){
		$this->session->unset_userdata($this->_IS_LOGGEDIN);
		redirect('admin/auth/login');
	}
	public function dologin(){

		$paramLogin['u_name'] = $this->input->post('username');
		$paramLogin['u_password'] = $this->input->post('password');

		$query = $this->m_user->login($paramLogin)->row();
		if (count($query)>0){
			if ($query->u_status==1){
				$session = array(
					$this->_IS_LOGGEDIN => true,
					$this->_ROLE 		=> $query->u_role,
					$this->_U_ID 		=> $query->u_id,
				);
				$this->session->set_userdata($session);
				redirect('admin/auth/login');
			}else{
				$alert['message'] 	= fs_alert_status_user($query->u_status);
				$alert['type'] 		= 'danger';
				fs_create_alert($alert);
			}
		}else{
			$alert['message'] 	= 'Username atau password yang anda masukan salah.';
			$alert['type'] 		= 'danger';
			fs_create_alert($alert);
		}
		redirect('admin/auth/login');
	}
	
}
