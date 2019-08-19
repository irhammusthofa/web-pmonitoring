<?php
require APPPATH . 'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking extends REST_Controller {

    var $_token;
    var $_user_id;
    var $_lokasi;
    var $_data_user;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_tracking');
        $this->load->model('m_auth');
        $this->load->model('m_user');
        $this->_token = $this->rest->key;

        $token              = $this->m_auth->get_token($this->_token);
        if (!empty($token)){
            $this->_user_id     = $token->user_id;  
            $this->_data_user   = $this->m_user->by_id($token->user_id)->row();  
              
            //$user             = $this->m_lokasi->by_user($this->_user_id)->row();
            //$this->_id_lokasi   = $member->m_id;
        }
    }
    public function index_get(){
        $id_perusahaan = $this->get('id_perusahaan',TRUE);

        $data = $this->m_tracking->by_id_perusahaan($id_perusahaan)->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tracking tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }else{
            foreach ($data as $item) {
                $tdetail = $this->m_tracking->last_tracking($item->tr_id)->row();
                if (!empty($tdetail)){
                    $item->td_id = $tdetail->td_id;
                    $item->td_tgl = $tdetail->td_tgl;
                    $item->td_vol = $tdetail->td_vol;
                    $item->td_status = $tdetail->td_status;
                }else{
                    $item->td_id = NULL;
                    $item->td_tgl = NULL;
                    $item->td_vol = NULL;
                    $item->td_status = NULL;
                }
            }
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data tersedia '.count($data),
                'data'      => $data,
            ], REST_Controller::HTTP_OK); 
        }
    }
    public function detail_get(){
        $id_tracking = $this->get('id_tracking',TRUE);

        $data = $this->m_tracking->last_tracking($id_tracking)->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tracking tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data tersedia '.count($data),
                'data'      => $data,
            ], REST_Controller::HTTP_OK); 
        }
    }
}
