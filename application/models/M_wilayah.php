<?php

class M_wilayah extends CI_Model
{
    function all(){
        $this->db->from('lokasi');
        $this->db->where_not_in('l_id',['1']);
        $this->db->order_by('l_id','asc');
        return $this->db->get();
    }    
}
