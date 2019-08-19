<?php
require APPPATH . 'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipper extends REST_Controller {

    var $_token;
    var $_user_id;
    var $_lokasi;
    var $_data_user;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_shipper');
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
    public function data_get()
    {
        $param['id_wilayah']     = $this->get('id_wilayah',TRUE);
        $param['s_kategori']     = $this->get('kategori',TRUE);
        $data = $this->m_shipper->all_data($param)->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data tersedia',
                'data'      => $data,
            ], REST_Controller::HTTP_OK); 
        }
        
    }
    public function create_data_post($id_wilayah){
        $data['s_perusahaan']          = $this->input->post('nama_perusahaan',TRUE);
        $data['s_kategori']            = $this->input->post('kategori',TRUE);
        $data['s_alamat']            = $this->input->post('alamat',TRUE);
        $data['id_wilayah']            = $id_wilayah;
        if (empty($id_wilayah) || $id_wilayah === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Wilayah belum diisi',
            ], REST_Controller::HTTP_OK);
        }else if (empty($data['s_perusahaan']) || $data['s_perusahaan'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Nama Shipper belum diisi',
            ], REST_Controller::HTTP_OK);
        }else{
            $shipper = $this->m_shipper->create_data($data);
            if ($shipper){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data berhasil disimpan',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data gagal disimpan',
                ], REST_Controller::HTTP_OK); 
            }
        }
    }
    public function update_data_post($id_shipper){
        $data['s_perusahaan']            = $this->input->post('nama_perusahaan',TRUE);
        $data['s_kategori']            = $this->input->post('kategori',TRUE);
        $data['id_wilayah']           = $this->input->post('id_wilayah',TRUE);
        $data['s_alamat']            = $this->input->post('alamat',TRUE);
        if (empty($id_shipper) || $id_shipper === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Shipper belum diisi',
            ], REST_Controller::HTTP_OK);
        }else if (empty($data['id_wilayah']) || $data['id_wilayah'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Wilayah belum diisi',
            ], REST_Controller::HTTP_OK);
        }else if (empty($data['s_perusahaan']) || $data['s_perusahaan'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Nama Shipper belum diisi',
            ], REST_Controller::HTTP_OK);
        }else{
            $shipper = $this->m_shipper->update_data($id_shipper,$data);
            if ($shipper){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data berhasil diupdate',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data gagal diupdate',
                ], REST_Controller::HTTP_OK); 
            }
        }
    }

    public function delete_data_post($id_shipper){
        if (empty($id_shipper) || $id_shipper === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Shipper belum diisi',
            ], REST_Controller::HTTP_OK);
        }else{
            $data['s_id'] = $id_shipper;
            $shipper = $this->m_shipper->delete_data($data);
            if ($shipper){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data berhasil dihapus',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data gagal dihapus',
                ], REST_Controller::HTTP_OK); 
            }
        }
    }
    public function create_post($id_shipper)
    {   
        $data['tgl']            = $this->input->post('tgl',TRUE);
        if (empty($id_shipper) || $id_shipper === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Shipper belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($data['tgl'])){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Tanggal belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else{
            $data['id_user']        = $this->_user_id;
            $data['tgl']            = $this->input->post('tgl',TRUE);
            $data['normal']         = $this->input->post('normal',TRUE);
            $data['temp']           = $this->input->post('temp',TRUE);
            $data['dp']             = $this->input->post('dp',TRUE);
            $data['pressure']       = $this->input->post('pressure',TRUE);
            $data['vol_last_hour']  = $this->input->post('vol_last_hour',TRUE);
            $data['vol_last_day']   = $this->input->post('vol_last_day',TRUE);
            $data['flow_rate']      = $this->input->post('flow_rate',TRUE);
            $data['comment']        = $this->input->post('comment',TRUE);
            $data['diff']           = $this->input->post('diff',TRUE);
            $shipper = $this->m_shipper->create($id_shipper,$data);
            if ($shipper){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data berhasil disimpan',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data gagal disimpan',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
    }
    public function index_get()
    {
        $param['id_wilayah']     = $this->get('id_wilayah',TRUE);
        $param['kategori']       = $this->get('id_kategori', TRUE);
        $param['date']          = $this->get('date',TRUE);
        if (empty($param['id_wilayah']) || $param['id_wilayah'] === NULL){
            $param['id_wilayah']  = $this->_data_user->id_lokasi;
        }
        $data = $this->m_shipper->all($param)->result();
        if (count($data)==0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data tersedia',
                'data'      => $data,
            ], REST_Controller::HTTP_OK); 
        }
        
    }
    public function date_get()
    {
        $param['id_wilayah']    = $this->get('id_wilayah',TRUE);
        $param['date']          = $this->get('date',TRUE);
        $data = $this->m_shipper->all($param)->result();
        if (count($data)<=0){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }
        $this->set_response([
            'status' => TRUE,
            'message' => 'Data tersedia',
            'data'      => $data,
        ], REST_Controller::HTTP_OK); 
    }
}
