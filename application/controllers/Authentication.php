<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
/**
* Created 6 February 2019
* @package 		Authentication
* @subpackage 	REST_Controller
* @category 	Controller
* @author 		Irham Mustofa Kamil
* @link         https://gitlab.com/irhammusthofa
@  
*/
class Authentication extends REST_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->config->set_item("rest_enable_keys",FALSE);
		$this->load->model('m_auth');
	}
	public function index_get(){
		redirect('admin');
	}
	
	public function logout($redirect)
	{
		$redirect = base64_decode($redirect);
		$this->session->sess_destroy();
		redirect($redirect);
	}
	public function login_get(){
		$username = $this->get("username",TRUE);
		$password = $this->get("password",TRUE);

		if (empty($username) || $username === NULL){
			$pesan_validasi 	= "Email tidak boleh kosong";
		}if (empty($password) || $password === NULL){
			$pesan_validasi 	= "Password tidak boleh kosong";
		}
		// Pesan Validasi
		if (!empty(@$pesan_validasi)){
			$this->response([
				'status'		=> FALSE,
				'message'		=> $pesan_validasi
			], REST_Controller::HTTP_OK);
		}
		// End Pesan Validasi 

		$datalogin = array(
			'username'		=> $username,
			'password'		=> $password
		);
		$result_auth 			= $this->m_auth->process_login($datalogin);
		if($result_auth===FALSE){  
			$this->response([
                'status' 		=> FALSE,
                'message' 		=> 'Username atau Password yang anda masukkan salah',
            ], REST_Controller::HTTP_OK);
		}else{
			$result_auth = json_decode($result_auth);
			
			if ($result_auth->result=='success'){
				$this->response([
	                'status' 		=> TRUE,
	                'message' 		=> 'Login anda berhasil diterima.',
	                'token'			=> $result_auth->token,
	                'wilayah'		=> $result_auth->wilayah,
	                'id_wilayah'	=> $result_auth->id_wilayah,
	                'id_perusahaan'	=> $result_auth->id_perusahaan,
	                'role'          => $result_auth->role,
	            ], REST_Controller::HTTP_OK);
			}else{
				$this->response([
	                'status' 		=> FALSE,
	                'message' 		=> $result_auth->message,
	            ], REST_Controller::HTTP_OK);
			}
		}
	}
}