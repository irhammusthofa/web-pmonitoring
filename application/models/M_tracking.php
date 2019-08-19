<?php

class M_tracking extends CI_Model
{
    var $_table = 'tracking';

    var $table = 'tracking t';

    public function __construct()
    {
        parent::__construct();
    }

    public function all(){
        return $this->db->from($this->_table)->get();
    }
    public function by_id_perusahaan($id){
        return $this->db->from($this->_table.' t')
            ->join('perusahaan p','p.s_id=t.id_perusahaan','inner')
            ->where('s_id',$id)->get();
    }
    public function last_tracking($id_tracking){
        return $this->db->from('tracking_detail td')
            ->join('tracking t','t.tr_id=td.id_tracking','inner')
            ->join('perusahaan p','p.s_id=t.id_perusahaan','inner')
            ->where('td.id_tracking',$id_tracking)
            ->order_by('td.td_id','desc')
            ->get();   
    }
    public function update($id,$data){
        return $this->db->where('tr_id',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
    }

    
}
