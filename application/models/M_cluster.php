<?php

class M_cluster extends CI_Model
{
    var $_table = 'cluster';

    var $table = 'cluster c';
    var $column_order = array('c.cl_bln', 'c.cl_c1temp', 'c.cl_c1pres','c.cl_c1vol', 'c.cl_c2temp', 'c.cl_c2pres','c.cl_c2vol', 'c.cl_c3temp', 'c.cl_c3pres','c.cl_c3vol'); //set column field database for datatable orderable
    var $column_search = array('c.cl_bln', 'c.cl_c1temp', 'c.cl_c1pres','c.cl_c1vol', 'c.cl_c2temp', 'c.cl_c2pres','c.cl_c2vol', 'c.cl_c3temp', 'c.cl_c3pres','c.cl_c3vol'); //set column field database for datatable searchable
    var $order = array('c.cl_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        
    
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
        return $this->db->count_all_results();
    }
    public function all(){
        return $this->db->from('cluster')->get();
    }
    public function by_bln($id){
        return $this->db->from('cluster')
            ->where('cl_bln',$id)->get();
    }
    public function by_id($id){
        return $this->db->from('cluster')
            ->where('cl_id',$id)->get();
    }

    public function insert($data){
        return $this->db->insert($this->_table,$data);
    }
    public function update($id,$data){
        return $this->db->where('cl_id',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
    }

    
}
