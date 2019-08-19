<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
/**
* Created 6 February 2019
* @package 		Logout
* @subpackage 	REST_Controller
* @category 	Controller
* @author 		Irham Mustofa Kamil
* @link         https://gitlab.com/irhammusthofa 
*/
class Logout extends REST_Controller
{
	var $_token;	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_auth');
		$this->_token = $this->rest->key;
	}
	public function index_get(){
		$result_auth 			= $this->m_auth->process_logout($this->_token);
		if($result_auth===FALSE){  
			$this->response([
                'status' 		=> FALSE,
                'message' 		=> 'Gagal melakukan logout. Silahkan coba beberapa saat lagi.',
            ], REST_Controller::HTTP_OK);
		}else{
			$this->response([
                'status' 		=> TRUE,
                'message' 		=> 'Logout berhasil diterima.'
            ], REST_Controller::HTTP_OK);
			
		}
	}
}