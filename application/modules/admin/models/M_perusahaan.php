<?php

class M_perusahaan extends CI_Model
{
    var $_table = 'perusahaan';

    var $table = 'perusahaan p';
    var $column_order = array('p.s_perusahaan', 'l.l_lokasi', 'p.s_kategori','p.s_alamat'); //set column field database for datatable orderable
    var $column_search = array('p.s_perusahaan', 'l.l_lokasi', 'p.s_kategori','p.s_alamat'); //set column field database for datatable searchable
    var $order = array('p.s_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('lokasi l','l.l_id=p.id_wilayah','inner');
        if (!empty($param['id_wilayah'])){
            $this->db->where('l.l_id',$param['id_wilayah']);    
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
        $this->db->join('lokasi l','l.l_id=p.id_wilayah','inner');
        if (!empty($param['id_wilayah'])){
            $this->db->where('l.l_id',$param['id_wilayah']);    
        }
        return $this->db->count_all_results();
    }
    public function by_lokasi($id){
        return $this->db->where('id_wilayah',$id)->get('perusahaan');
    }
    public function all(){
        return $this->db->from('perusahaan')->get();
    }
    public function all_kategori($id){
        return $this->db->from('perusahaan')->where_in('s_kategori',$id)->get();
    }
    public function by_id($id){
        return $this->db->from('perusahaan')
            ->where('s_id',$id)->get();
    }

    public function insert($data){
        return $this->db->insert($this->_table,$data);
    }
    public function update($id,$data){
        return $this->db->where('s_id',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
    }

    
}
