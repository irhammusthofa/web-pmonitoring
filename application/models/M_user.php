<?php

class M_user extends CI_Model
{
    function all(){
        return $this->db->from('user u')
            ->join('lokasi l','l.l_id=u.id_lokasi','inner')
            ->join('perusahaan p','p.s_id=u.id_perusahaan','left')
            ->get();
    }
    function update($id,$data){
        return $this->db->where('u_id',$id)->update('user',$data);
    }
    function delete($data){
        return $this->db->delete('user',$data);
    }
    function insert($data){
        return $this->db->insert('user',$data);
    }
    function by_id($id){
        return $this->db->where('u_id',$id)->get('user');
    }
    function ubahusername($id,$data){
        return $this->db->where('u_id',$id)->update('user',['u_name'=>$data]);
    }
    function ubahpassword($id,$data){
        return $this->db->where('u_id',$id)->update('user',['u_password'=>$data]);
    }
    
    function ceklogin($param){
        $q = $this->db->where('u_name',$param['username'])
            ->where('u_password',$param['password'])
            ->get('user');
        if (count($q->result())>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
