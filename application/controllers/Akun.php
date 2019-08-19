<?php
require APPPATH . 'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends REST_Controller {

    var $_token;
    var $_user_id;
    var $_lokasi;
    var $_data_user;
    public function __construct()
    {
        parent::__construct();
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
        $user = $this->m_user->all()->result();
        if (count($user)>0){
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data akun tersedia',
                'data'=>$user,
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'Data akun tidak tersedia',
            ], REST_Controller::HTTP_OK); 
        }
    }
    public function insert_post(){
        $param['u_name']        = $this->post('username',TRUE);
        $param['u_password']     = $this->post('password',TRUE);
        $param['id_lokasi']     = $this->post('id_wilayah',TRUE);
        $param['id_perusahaan']     = $this->post('id_perusahaan',TRUE);
        if (empty($param['u_name']) || $param['u_name'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Username belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($param['u_password']) || $param['u_password'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Password lama belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($param['id_lokasi']) || $param['id_lokasi'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Wilayah belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else{
            if (empty($param['id_perusahaan'])){
                $param['id_perusahaan'] = null;
                $param['u_role'] = "3";    
            }else{
                $param['u_role'] = "4";
            }
            $param['u_password'] = sha1($param['u_password']);
            $user = $this->m_user->insert($param);
            if (count($user)){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data akun baru berhasil ditambahkan',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data akun baru gagal ditambahkan',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
    }
    public function update_post($id_user){
        $param['u_name']        = $this->post('username',TRUE);
        $param['id_lokasi']     = $this->post('id_wilayah',TRUE);
        $param['id_perusahaan']     = $this->post('id_perusahaan',TRUE);
        if (empty($param['u_name']) || $param['u_name'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Username belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($param['id_lokasi']) || $param['id_lokasi'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id Wilayah belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else{
        	$pass= $this->post('password',TRUE);
        	if(!(empty($pass) || $pass===NULL)){
            	$param['u_password'] = sha1($pass);
        	}

            if (empty($param['id_perusahaan'])){
                $param['id_perusahaan'] = null;
                $param['u_role'] = "3";    
            }else{
                $param['u_role'] = "4";
            }
        
            $user = $this->m_user->update($id_user,$param);
            if (count($user)){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data akun berhasil diupdate',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data akun gagal diupdate',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
    }
    public function delete_get(){
        $param['u_id']        = $this->get('id_user',TRUE);

        if (empty($param['u_id']) || $param['u_id'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Id User belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else{
            $user = $this->m_user->delete($param);
            if (count($user)){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Data akun berhasil dihapus',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Data akun gagal dihapus',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
    }
    public function ubahusername_post()
	{
        $param['username']     = $this->post('username',TRUE);
        $param['password']     = $this->post('password',TRUE);
        if (empty($param['username']) || $param['username'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Username belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($param['password']) || $param['password'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Password lama belum diisi',
            ], REST_Controller::HTTP_OK); 

        }else{
            $ceklogin = $this->m_user->ceklogin(['username'=>$this->_data_user->u_name,'password'=>sha1($param['password'])]);
            if ($ceklogin){
                $ubah = $this->m_user->ubahusername($this->_user_id,$param['username']);
                if ($ubah){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Username berhasil diubah.',
                    ], REST_Controller::HTTP_OK); 
                }else{
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Ubah username gagal, silahkan coba lagi',
                    ], REST_Controller::HTTP_OK); 
                }
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Password lama salah',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
        
    }
    public function ubahpassword_post()
	{
        $param['passlama']     = $this->post('passlama',TRUE);
        $param['passbaru']     = $this->post('passbaru',TRUE);
        if (empty($param['passlama']) || $param['passlama'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Password lama belum diisi',
            ], REST_Controller::HTTP_OK); 
        }else if (empty($param['passbaru']) || $param['passbaru'] === NULL){
            $this->set_response([
                'status' => FALSE,
                'message' => 'Password baru belum diisi',
            ], REST_Controller::HTTP_OK); 

        }else{
            $ceklogin = $this->m_user->ceklogin(['username'=>$this->_data_user->u_name,'password'=>sha1($param['passlama'])]);
            if ($ceklogin){
                $ubah = $this->m_user->ubahpassword($this->_user_id,sha1($param['passbaru']));
                if ($ubah){
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'Password berhasil diubah.',
                    ], REST_Controller::HTTP_OK); 
                }else{
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Ubah Password gagal, silahkan coba lagi',
                    ], REST_Controller::HTTP_OK); 
                }
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Password lama salah',
                ], REST_Controller::HTTP_OK); 
            }
        }
        
    }
}
