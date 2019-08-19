<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->auth();
        $this->load->model('m_cluster');
        $this->load->model('m_shipper');
        $this->load->model('m_perusahaan');
       
    }
    public function index()
	{
		$this->title 	= "K-Means";
		$this->content 	= "cluster/index";
		$this->assets 	= array('assets');

		$param = array(
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "K-Means";
		$this->content 	= "cluster/add";
		$this->assets 	= array('assets_form');

		$data['bulan']  = [];
		$data['tahun']  = [];

		for($i=0;$i<12;$i++){
			$bln = str_pad($i+1, 2,"0",STR_PAD_LEFT);
			$data['bulan'][$bln] = convert_bulan($bln);
		} 
		for($i=0;$i<100;$i++){
			$data['tahun'][2000+$i] = 2000+$i;
		}   
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "K-Means";
		$this->content 	= "cluster/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['cluster'] = $this->m_cluster->by_id($id)->row();

		$data['bulan']  = [];
		$data['tahun']  = [];

		for($i=0;$i<12;$i++){
			$bln = str_pad($i+1, 2,"0",STR_PAD_LEFT);
			$data['bulan'][$bln] = convert_bulan($bln);
		} 
		for($i=0;$i<100;$i++){
			$data['tahun'][2000+$i] = 2000+$i;
		}   
		$param = array(
			'data'=>$data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['cl_bln'] = $this->input->post('bulan',TRUE).'-'.$this->input->post('tahun',TRUE);
		$data['cl_c1temp'] = $this->input->post('c1temp',TRUE);
		$data['cl_c1pres'] = $this->input->post('c1pres',TRUE);
		$data['cl_c1vol'] = $this->input->post('c1vol',TRUE);

		$data['cl_c2temp'] = $this->input->post('c2temp',TRUE);
		$data['cl_c2pres'] = $this->input->post('c2pres',TRUE);
		$data['cl_c2vol'] = $this->input->post('c2vol',TRUE);

		$data['cl_c3temp'] = $this->input->post('c3temp',TRUE);
		$data['cl_c3pres'] = $this->input->post('c3pres',TRUE);
		$data['cl_c3vol'] = $this->input->post('c3vol',TRUE);

		if (empty($id)){
			$save = $this->m_cluster->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/cluster');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/cluster/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_cluster->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/cluster');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/cluster/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['cl_id'] = $id;
		$delete = $this->m_cluster->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/cluster');
	}
	public function ajax_list()
	{
		$param['id_wilayah'] = [];
		$list = $this->m_cluster->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$btngroup_disable = '<div class="input-group">
			<button type="button" class="btn btn-xs btn-default pull-right dropdown-toggle" data-toggle="dropdown" disabled>
				<span> Action
				</span>
				<i class="fa fa-caret-down"></i>
			</button>
		</div>';
			$btngroup = '<div class="input-group">
					<button type="button" class="btn btn-xs btn-default pull-right dropdown-toggle" data-toggle="dropdown">
						<span> Action
						</span>
						<i class="fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu">
						<li>' . anchor("admin/cluster/edit/".base64_encode($item->cl_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/cluster/hapus/".base64_encode($item->cl_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
						<li>' . anchor("admin/cluster/hasil/".base64_encode($item->cl_id),"<i class=\"fa fa-file-text\"></i>K-Means") . '</li>
					</ul>
                </div>';
            $iterasi = $this->kmeans(base64_encode($item->cl_id));
            $last = $iterasi[count($iterasi)-1];
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = convert_bulan(substr($item->cl_bln, 0,2)).' '.substr($item->cl_bln, 3,4);
			$row[] = $last['cl_c1temp'];
			$row[] = $last['cl_c1pres'];
			$row[] = $last['cl_c1vol'];
			$row[] = $last['cl_c2temp'];
			$row[] = $last['cl_c2pres'];
			$row[] = $last['cl_c2vol'];
			$row[] = $last['cl_c3temp'];
			$row[] = $last['cl_c3pres'];
			$row[] = $last['cl_c3vol'];

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_cluster->count_all($param),
			"recordsFiltered" => $this->m_cluster->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
    public function hasil($id){
    	$this->title 	= "K-Means";
		$this->content 	= "cluster/cluster";
		$this->assets 	= array();
		
        $data['iterasi'] = $this->kmeans($id);
        //echo json_encode($data['iterasi']);
        $param = array(
        	'data' 	=> $data,
		);
        $this->template($param);


    }
    private function kmeans($id){
    	$id = base64_decode($id);
    	$cluster = $this->m_cluster->by_id($id)->row();

        $bulan          = $cluster->cl_bln;
        $bulan 			= substr($bulan, 3,4).'-'.substr($bulan, 0,2);
        $result = [];
        $date = new DateTime('last day of '.$bulan);
        $end = $date->format('d');
        $finish = false;
        $iterasi = 1;
        $data['iterasi'] = [];
        $temp = [];
        while ($finish==false) {
			$perusahaan = $this->m_perusahaan->all_kategori(['offtaker','multi'])->result();
			
			$sum_c1temp = 0;
			$sum_c1pres = 0;
			$sum_c1vol = 0;
			$sum_c2temp = 0;
			$sum_c2pres = 0;
			$sum_c2vol = 0;
			$sum_c3temp = 0;
			$sum_c3pres = 0;
			$sum_c3vol = 0;

			$count_c1 = 0;
			$count_c2 = 0;
			$count_c3 = 0;
			$prsh = [];
	        foreach ($perusahaan as $item) {
	        	$param['start_date'] = $date->format('Y').'-'.$date->format('m').'-01';
	        	$param['end_date'] = $date->format('Y').'-'.$date->format('m').'-'.$end;
	        	$param['id'] = $item->s_id;
	        	$data_tgl = $this->m_shipper->periode_kmeans($param)->row();
	        	if ($data_tgl->total_temp == null || $data_tgl->total_pressure == null || $data_tgl->total_vol_last_day == null){
					continue;
	        	}
	        	$item->temp = $data_tgl->total_temp;
	        	$item->pressure = $data_tgl->total_pressure;
	        	$item->vol_last_day = $data_tgl->total_vol_last_day;

	        	if ($iterasi==1){
	        		$c1temp = $cluster->cl_c1temp;
		        	$c1pres = $cluster->cl_c1pres;
		        	$c1vol = $cluster->cl_c1vol;

		        	$c2temp = $cluster->cl_c2temp;
		        	$c2pres = $cluster->cl_c2pres;
		        	$c2vol = $cluster->cl_c2vol;

		        	$c3temp = $cluster->cl_c3temp;
		        	$c3pres = $cluster->cl_c3pres;
		        	$c3vol = $cluster->cl_c3vol;
	        	}else{
	        		$cl_c1temp = ($tsum_c1temp>0) ? $tsum_c1temp / $tcount_c1 : 0;
		        	$cl_c1pres = ($tsum_c1pres>0) ? $tsum_c1pres / $tcount_c1 : 0;
		        	$cl_c1vol = ($tsum_c1vol>0) ? $tsum_c1vol / $tcount_c1 : 0;

		        	$cl_c2temp = ($tsum_c2temp > 0) ? $tsum_c2temp / $tcount_c2 : 0;
		        	$cl_c2pres = ($tsum_c2pres>0) ? $tsum_c2pres / $tcount_c2 : 0;
		        	$cl_c2vol = ($tsum_c2vol>0) ? $tsum_c2vol / $tcount_c2 : 0;

		        	$cl_c3temp = ($tsum_c3temp > 0) ? $tsum_c3temp / $tcount_c3 : 0;
		        	$cl_c3pres = ($tsum_c3pres > 0) ? $tsum_c3pres / $tcount_c3 : 0;
		        	$cl_c3vol = ($tsum_c3vol > 0) ? $tsum_c3vol / $tcount_c3 : 0;

	        		$c1temp = $cl_c1temp;
		        	$c1pres = $cl_c1pres;
		        	$c1vol = $cl_c1vol;

		        	$c2temp = $cl_c2temp;
		        	$c2pres = $cl_c2pres;
		        	$c2vol = $cl_c2vol;

		        	$c3temp = $cl_c3temp;
		        	$c3pres = $cl_c3pres;
		        	$c3vol = $cl_c3vol;
	        	}
	        	

	        	$dc1 = (($item->temp - $c1temp)*($item->temp - $c1temp));
	        	$dc1 += (($item->pressure - $c1pres)*($item->pressure - $c1pres));
	        	$dc1 += (($item->vol_last_day - $c1vol)*($item->vol_last_day - $c1vol));
	        	$dc1 = sqrt($dc1);

				$dc2 = (($item->temp - $c2temp)*($item->temp - $c2temp));
	        	$dc2 += (($item->pressure - $c2pres)*($item->pressure - $c2pres));
	        	$dc2 += (($item->vol_last_day - $c2vol)*($item->vol_last_day - $c2vol));
	        	$dc2 = sqrt($dc2); 

	        	$dc3 = (($item->temp - $c3temp)*($item->temp - $c3temp));
	        	$dc3 += (($item->pressure - $c3pres)*($item->pressure - $c3pres));
	        	$dc3 += (($item->vol_last_day - $c3vol)*($item->vol_last_day - $c3vol));
	        	$dc3 = sqrt($dc3);

	        	if ($dc1 < $dc3 && $dc1 < $dc2){
	        		$item->c1 = 'X';
	        		$sum_c1temp += $data_tgl->total_temp;
	        		$sum_c1pres += $item->pressure;
	        		$sum_c1vol += $item->vol_last_day;
	        		$count_c1++;
	        	}else{
	        		$item->c1 = '';
	        	}

	        	
	        	if ($dc2 < $dc3 && $dc2 < $dc1){
	        		$item->c2 = 'X';
	        		$sum_c2temp += $data_tgl->total_temp;
	        		$sum_c2pres += $item->pressure;
	        		$sum_c2vol += $item->vol_last_day;
	        		$count_c2++;
	        	}else{
	        		$item->c2 = '';
	        	}

	        	if ($dc3 < $dc2  && $dc3 < $dc1){
	        		$item->c3 = 'X';
	        		$sum_c3temp += $data_tgl->total_temp;
	        		$sum_c3pres += $item->pressure;
	        		$sum_c3vol += $item->vol_last_day;
	        		$count_c3++;
	        	}else{
	        		$item->c3 = '';
	        	}
	        	
	        	$item->dc1 = $dc1;
	        	$item->dc2 = $dc2;
	        	$item->dc3 = $dc3;
	        	$prsh[]		= $item;
	        }
	        if ($iterasi == 1){
	        	$cl_c1temp = $cluster->cl_c1temp;
	        	$cl_c1pres = $cluster->cl_c1pres;
	        	$cl_c1vol = $cluster->cl_c1vol;

	        	$cl_c2temp = $cluster->cl_c2temp;
	        	$cl_c2pres = $cluster->cl_c2pres;
	        	$cl_c2vol = $cluster->cl_c2vol;

	        	$cl_c3temp = $cluster->cl_c3temp;
	        	$cl_c3pres = $cluster->cl_c3pres;
	        	$cl_c3vol = $cluster->cl_c3vol;

	        	$tsum_c1temp = $sum_c1temp;
				$tsum_c1pres = $sum_c1pres;
				$tsum_c1vol = $sum_c1vol;
				$tsum_c2temp = $sum_c2temp;
				$tsum_c2pres = $sum_c2pres;
				$tsum_c2vol = $sum_c2vol;
				$tsum_c3temp = $sum_c3temp;
				$tsum_c3pres = $sum_c3pres;
				$tsum_c3vol = $sum_c3vol;


				$tcount_c1 = $count_c1;
				$tcount_c2 = $count_c2;
				$tcount_c3 = $count_c3;

	        }else{
	        	$cl_c1temp = ($tsum_c1temp>0) ? $tsum_c1temp / $tcount_c1 : 0;
	        	$cl_c1pres = ($tsum_c1pres>0) ? $tsum_c1pres / $tcount_c1 : 0;
	        	$cl_c1vol = ($tsum_c1vol>0) ? $tsum_c1vol / $tcount_c1 : 0;

	        	$cl_c2temp = ($tsum_c2temp > 0) ? $tsum_c2temp / $tcount_c2 : 0;
	        	$cl_c2pres = ($tsum_c2pres>0) ? $tsum_c2pres / $tcount_c2 : 0;
	        	$cl_c2vol = ($tsum_c2vol>0) ? $tsum_c2vol / $tcount_c2 : 0;

	        	$cl_c3temp = ($tsum_c3temp > 0) ? $tsum_c3temp / $tcount_c3 : 0;
	        	$cl_c3pres = ($tsum_c3pres > 0) ? $tsum_c3pres / $tcount_c3 : 0;
	        	$cl_c3vol = ($tsum_c3vol > 0) ? $tsum_c3vol / $tcount_c3 : 0;
	        	if(($count_c1.$count_c2.$count_c3) == ($tcount_c1.$tcount_c2.$tcount_c3)){
	        		$finish = true;
	        	}
	        	$tsum_c1temp = $sum_c1temp;
				$tsum_c1pres = $sum_c1pres;
				$tsum_c1vol = $sum_c1vol;
				$tsum_c2temp = $sum_c2temp;
				$tsum_c2pres = $sum_c2pres;
				$tsum_c2vol = $sum_c2vol;
				$tsum_c3temp = $sum_c3temp;
				$tsum_c3pres = $sum_c3pres;
				$tsum_c3vol = $sum_c3vol;


				$tcount_c1 = $count_c1;
				$tcount_c2 = $count_c2;
				$tcount_c3 = $count_c3;
	        }
	        $data['iterasi']['no'] = $iterasi;
	        $data['iterasi']['countc1'] = $count_c1;
	        $data['iterasi']['countc2'] = $count_c2;
	        $data['iterasi']['countc3'] = $count_c3;
	        $data['iterasi']['sum_c1temp'] = $sum_c1temp;
	        $data['iterasi']['sum_c1pres'] = $sum_c1pres;
	        $data['iterasi']['sum_c1vol'] = $sum_c1vol;
	        $data['iterasi']['sum_c2temp'] = $sum_c2temp;
	        $data['iterasi']['sum_c2pres'] = $sum_c2pres;
	        $data['iterasi']['sum_c2vol'] = $sum_c2vol;
	        $data['iterasi']['sum_c3temp'] = $sum_c3temp;
	        $data['iterasi']['sum_c3pres'] = $sum_c3pres;
	        $data['iterasi']['sum_c3vol'] = $sum_c3vol;

	        $data['iterasi']['cl_c1temp'] = $cl_c1temp;
	        $data['iterasi']['cl_c1pres'] = $cl_c1pres;
	        $data['iterasi']['cl_c1vol'] = $cl_c1vol;
	        $data['iterasi']['cl_c2temp'] = $cl_c2temp;
	        $data['iterasi']['cl_c2pres'] = $cl_c2pres;
	        $data['iterasi']['cl_c2vol'] = $cl_c2vol;
	        $data['iterasi']['cl_c3temp'] = $cl_c3temp;
	        $data['iterasi']['cl_c3pres'] = $cl_c3pres;
	        $data['iterasi']['cl_c3vol'] = $cl_c3vol;
	        $data['iterasi']['data'] = $prsh;

	        $temp[] = $data['iterasi'];
	        $iterasi++;
        }
        return $temp;
    }
    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	    $sort_col = array();
	    foreach ($arr as $key=> $row) {
	        $sort_col[$key] = $row[$col];
	    }

	    array_multisort($sort_col, $dir, $arr);
	}


    private function stdev($arr) 
    { 
        $num_of_elements = count($arr); 
          
        $variance = 0.0; 
          
                // calculating mean using array_sum() method 
        $average = array_sum($arr)/$num_of_elements; 
          
        foreach($arr as $i) 
        { 
            // sum of squares of differences between  
                        // all numbers and means. 
            $variance += pow(($i - $average), 2); 
        } 
          
        return (float)sqrt($variance/$num_of_elements); 
    } 
    function normalize($value, $min, $max) {
    	if ($value - $min != 0){
    		$normalized = ($value - $min) / ($max - $min);	
    	}else{
    		$normalized = 0;
    	}
        
        return $normalized;
    }
}
