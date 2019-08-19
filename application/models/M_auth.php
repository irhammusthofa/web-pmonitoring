<?php
/**
* Created 6 February 2019
* @package 		M_auth
* @subpackage 	CI_Model
* @category 	Model
* @author 		Irham Mustofa Kamil
* @link         https://gitlab.com/irhammusthofa
*/
class M_auth extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

    public function get_token($token){
        return $this->_get_key($token);
    }
	public function process_login($param){
		$param['password'] = sha1($param['password']);
        $row = $this->db->from('user u')
            ->join('lokasi l','l.l_id=u.id_lokasi','left')
            ->where('u.u_name',$param['username'])
			->where('u.u_password',$param['password'])
			->get()->row();
		if (empty($row)){
			return FALSE;
		// }else if ($row->u_status==0){
		// 	$result = array(
		// 		'result' => 'failed', 
		// 		'message' => 'Akun tidak aktif',
		// 	);
		// }else if ($row->u_status==2){
		// 	$result = array(
		// 		'result' => 'failed', 
		// 		'message' => 'Akun diblokir, silahkan hubungi Admin.',
		// 	);

		}else {
			$token = $this->_generate_token();
            $this->generate_token($token,['user_id'=>$row->u_id]);
            $result = array(
                'result' => 'success', 
                'token' => $token,
                'wilayah'=> $row->l_lokasi,
                'id_wilayah'=> $row->l_id,
                'id_perusahaan'=> $row->id_perusahaan,
                'role' => $row->u_role,
                'message' => 'Login berhasil diterima',
            );
			
		}
		return json_encode($result);
	}
    public function process_logout($token){
        $delete = $this->_delete_key($token);
        if ($delete){
            return TRUE;
        }else{
            return FALSE;
        }
    }
	function generate_token($key, $data){
		$data[config_item('rest_key_column')] = $key;
        $data['date_created'] = function_exists('now') ? now() : time();

        return $this->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));;
	}
	private function _generate_token()
    {
        do
        {
            $salt 	= hash('sha256', time() . mt_rand());

            $new_key = substr($salt, 0, config_item('rest_key_length'));

            $new_key = sha1($new_key);
        }
        while ($this->_key_exists($new_key));

        return $new_key;
    }
    /* Private Data Methods */

    private function _get_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->get(config_item('rest_keys_table'))
            ->row();
    }

    private function _key_exists($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->count_all_results(config_item('rest_keys_table')) > 0;
    }

    private function _insert_key($key, $data)
    {
        $data[config_item('rest_key_column')] = $key;
        $data['date_created'] = date("Y-m-d H:i:s",strtotime("+05 minutes", strtotime(date("Y-m-d H:i:s")))) /*function_exists('now') ? now() : time()*/;

        return $this->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));
    }

    private function _update_key($key, $data)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->update(config_item('rest_keys_table'), $data);
    }

    private function _delete_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->delete(config_item('rest_keys_table'));
    }
    private function _user_online($user_id){
        return $this->db
            ->where('user_id', $user_id)
            ->get(config_item('rest_keys_table'))
            ->row();
    }
}