<?php

class M_trackingdetail extends CI_Model
{
    var $_table = 'tracking_detail';

    var $table = 'tracking t';
    var $column_order = array('td.td_tgl','td.td_vol','td.td_status', 'p.s_perusahaan'); //set column field database for datatable orderable
    var $column_search = array('td.td_tgl','td.td_vol','td.td_status', 'p.s_perusahaan'); //set column field database for datatable searchable
    var $order = array('td.td_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('perusahaan p','t.id_perusahaan=p.s_id','inner');
        $this->db->join('tracking_detail td','td.id_tracking=t.tr_id','inner');

        if (!empty($param['id_tracking'])){
            $this->db->where('td.id_tracking',$param['id_tracking']);    
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
        $this->db->join('perusahaan p','t.id_perusahaan=p.s_id','inner');
        $this->db->join('tracking_detail td','td.id_tracking=t.tr_id','inner');

        if (!empty($param['id_tracking'])){
            $this->db->where('td.id_tracking',$param['id_tracking']);    
        }
        return $this->db->count_all_results();
    }
    public function all(){
        return $this->db->from($this->_table)->get();
    }
    public function by_tracking($id){
        return $this->db->from($this->_table)
            ->where('id_tracking',$id)->get();
    }
    public function by_id($id){
        return $this->db->from($this->_table)
            ->where('td_id',$id)->get();
    }

    public function insert($data){
        return $this->db->insert($this->_table,$data);
    }
    public function update($id,$data){
        return $this->db->where('td_id',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
    }

    
}
