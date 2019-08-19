<?php
require APPPATH . 'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends REST_Controller {

    var $_token;
    var $_user_id;
    var $_lokasi;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_wilayah');
        $this->load->model('m_auth');
        $this->_token = $this->rest->key;

        $token              = $this->m_auth->get_token($this->_token);
        if (!empty($token)){
            $this->_user_id     = $token->user_id;    
            //$lokasi             = $this->m_lokasi->by_user($this->_user_id)->row();
            //$this->_id_lokasi   = $member->m_id;
        }
    }
    
	public function index_get()
	{
        $data = $this->m_wilayah->all()->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data tersedia'.count($data),
                'data'      => $data,
            ], REST_Controller::HTTP_OK); 
        }
        
    }
    
}
