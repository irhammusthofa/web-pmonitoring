<?php

class M_tracking extends CI_Model
{
    var $_table = 'tracking';

    var $table = 'tracking t';
    var $column_order = array('t.tr_tgl', 'p.s_perusahaan'); //set column field database for datatable orderable
    var $column_search = array('t.tr_tgl', 'p.s_perusahaan'); //set column field database for datatable searchable
    var $order = array('t.tr_id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($param='')
    {
        $this->db->from($this->table);
        $this->db->join('perusahaan p','t.id_perusahaan=p.s_id','inner');
        $this->db->join('tracking_detail td','td.id_tracking=t.tr_id','left');

        if (!empty($param['id_perusahaan'])){
            $this->db->where('t.id_perusahaan',$param['id_perusahaan']);    
        }
        $this->db->order_by('td.td_id','desc');
        $this->db->group_by('t.tr_id');
        
        
    
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
        $this->db->join('tracking_detail td','td.id_tracking=t.tr_id','left');

        if (!empty($param['id_perusahaan'])){
            $this->db->where('t.id_perusahaan',$param['id_perusahaan']);    
        }
        $this->db->order_by('td.td_id','desc');
        $this->db->group_by('t.tr_id');
        return $this->db->count_all_results();
    }
    public function all(){
        return $this->db->from($this->_table)->get();
    }
    public function by_id($id){
        return $this->db->from($this->_table.' t')
            ->join('perusahaan p','p.s_id=t.id_perusahaan','inner')
            ->where('tr_id',$id)->get();
    }

    public function insert($data){
        $this->db->trans_begin();
            $tracking['tr_tgl'] = $data['tr_tgl'];
            $tracking['id_perusahaan'] = $data['id_perusahaan'];

            $this->db->insert($this->_table,$tracking);
            $id = $this->db->insert_id();
            $tracking_detail['td_tgl'] = $data['tr_tgl'];
            $tracking_detail['id_tracking'] = $id;
            $tracking_detail['td_vol'] = $data['td_vol'];
            $tracking_detail['td_status'] = $data['td_status'];

            $this->db->insert('tracking_detail',$tracking_detail);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function update($id,$data){
        return $this->db->where('tr_id',$id)->update($this->_table,$data);
    }

    public function delete($data){
        return $this->db->delete($this->_table,$data);
    }

    
}
