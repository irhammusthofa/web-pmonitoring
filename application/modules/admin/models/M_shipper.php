<?php

class M_shipper extends CI_Model
{
    var $_table = 'tabel_shipper4';

    var $table = 'tabel_shipper4 sp';
    var $column_order = array('sp.tgl', 'p.s_perusahaan', 'p.s_kategori'); //set column field database for datatable orderable
    var $column_search = array('sp.tgl', 'p.s_perusahaan', 'p.s_kategori'); //set column field database for datatable searchable
    var $order = array('sp.no' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('perusahaan p','p.s_id=sp.id_shipper','inner');
        if (!empty($param['id_shipper'])){
            if ($param['id_shipper']=='shipper'){
                $this->db->where_in('p.s_kategori',['shipper']);        
            }else if ($param['id_shipper']=='offtaker'){
                $this->db->where_in('p.s_kategori',['offtaker','multi']);     
            }else{
                $this->db->where('sp.id_shipper',$param['id_shipper']);    
            }
            
        }
        if (!empty($param['tgl'])){
            $this->db->where('sp.tgl',$param['tgl']);    
        }
        
    
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($param='')
    {
        $this->_get_datatables_query($param);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($param='')
    {
        $this->_get_datatables_query($param);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($param='')
    {
        $this->db->from($this->table);
        $this->db->join('perusahaan p','p.s_id=sp.id_shipper','inner');
        if (!empty($param['id_shipper'])){
            if ($param['id_shipper']=='shipper'){
                $this->db->where_in('p.s_kategori',['shipper']);        
            }else if ($param['id_shipper']=='offtaker'){
                $this->db->where_in('p.s_kategori',['offtaker','multi']);     
            }else{
                $this->db->where('sp.id_shipper',$param['id_shipper']);    
            }
            
        }
        return $this->db->count_all_results();
    }
    public function all(){
        return $this->db->from($this->_table)->get();
    }
    public function by_id($id){
        return $this->db->from($this->_table)
            ->where('no',$id)->get();
    }

    public function insert($data){
        return $this->db->insert($this->_table,$data);
    }
    public function update($id,$data){
        return $this->db->where('no',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
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
