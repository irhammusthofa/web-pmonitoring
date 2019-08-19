<?php
require APPPATH . 'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan extends REST_Controller {

    var $_token;
    var $_user_id;
    var $_lokasi;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_perusahaan');
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
        $param['kategori'] = $this->get('id_kategori',TRUE);
        $param['wilayah'] = $this->get('id_wilayah',TRUE);
        $param['perusahaan'] = $this->get('id_perusahaan',TRUE);
        if ($param['kategori']=='all_offtaker'){
            $param['kategori'] = ['offtaker','multi'];
        }
        $data = $this->m_perusahaan->all_kategori_wilayah($param)->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data perusahaan Offtaker/Multi tidak tersedia',
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
