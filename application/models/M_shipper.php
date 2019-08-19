<?php

class M_shipper extends CI_Model
{
    function all_data($param){
        $this->db->from('perusahaan s');
        if (!(empty($param['id_wilayah']) || $param['id_wilayah'] === NULL)){
            $this->db->where('s.id_wilayah',$param['id_wilayah']);
        }
        if (!(empty($param['s_kategori']) || $param['s_kategori'] === NULL)){
            $this->db->where('s.s_kategori',$param['s_kategori']);
        }
        
        $this->db->order_by('s_id','asc');
        return $this->db->get();
    }
    function periode($param){
        $this->db->select('ts.*,s.*,sum(ts.vol_last_day) as total');
        $this->db->from('tabel_shipper4 ts');
        $this->db->join('perusahaan s','s.s_id=ts.id_shipper');
        if (!(empty($param['id_wilayah']) || $param['id_wilayah'] === NULL)){
            $this->db->where('s.id_wilayah',$param['id_wilayah']);
        }
        if (!(empty($param['start_date']) || $param['start_date'] === NULL)){
            $this->db->where('ts.tgl BETWEEN "'. date('Y-m-d', strtotime($param['start_date'])). '" and "'. date('Y-m-d', strtotime($param['end_date'])).'"');
        }
        $this->db->where('s.s_kategori', $param['kategori']);
        $this->db->order_by('s_id','asc');
        return $this->db->get();
    }
    function all($param){
        $this->db->from('tabel_shipper4 ts');
        $this->db->join('perusahaan s','s.s_id=ts.id_shipper');
        if (!(empty($param['id_wilayah']) || $param['id_wilayah'] === NULL)){
            $this->db->where('s.id_wilayah',$param['id_wilayah']);
        }
        if (!(empty($param['date']) || $param['date'] === NULL)){
            $this->db->where('ts.tgl',$param['date']);
        }else{
            $this->db->where('ts.tgl',date('Y-m-d'));
        }
        $this->db->where('s.s_kategori', $param['kategori']);
        $this->db->order_by('s_id','asc');
        return $this->db->get();
    }
    function data_by_wilayah($id_wilayah){
        $this->db->from('perusahaan');
        $this->db->where('id_wilayah',$id_wilayah);

        $this->db->order_by('s_id','asc');
        return $this->db->get();
    }  
    function create($id_shipper,$data){
        $create_exist = $this->create_exist($id_shipper,$data);
        if ($create_exist===FALSE){
            $data['id_shipper']  = $id_shipper;
            return $this->db->insert('tabel_shipper4',$data);                
        }else{
            return $this->db->where('no',$create_exist)->update('tabel_shipper4',$data);
        }
        
    }   
    function create_data($data){
        return $this->db->insert('perusahaan',$data);     
    }   
    function update_data($id,$data){
        return $this->db->where('s_id',$id)->update('perusahaan',$data);     
    }   
    function delete_data($data){
        return $this->db->delete('perusahaan',$data);     
    }   
    
    function create_exist($id_shipper,$data){
        $this->db->from('tabel_shipper4');
        $this->db->where('id_shipper',$id_shipper);
        $this->db->where('tgl',$data['tgl']);
        $q = $this->db->get()->row();
        if (empty($q)){
            return FALSE;
        }else{
            return $q->no;
        }
    }   
    function periode_kmeans($param){
        $this->db->select('sum(ts.vol_last_day) as total_vol_last_day,sum(ts.temp) as total_temp,sum(ts.pressure) as total_pressure');
        $this->db->from('tabel_shipper4 ts');
        $this->db->join('perusahaan s','s.s_id=ts.id_shipper');
        if (!(empty($param['start_date']) || $param['start_date'] === NULL)){
            $this->db->where('ts.tgl BETWEEN "'. date('Y-m-d', strtotime($param['start_date'])). '" and "'. date('Y-m-d', strtotime($param['end_date'])).'"');
        }
        $this->db->where('ts.id_shipper', $param['id']);
        $this->db->order_by('s_id','asc');
        return $this->db->get();
    }
    
}
