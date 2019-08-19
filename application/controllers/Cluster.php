<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends REST_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cluster');
        $this->load->model('m_shipper');
        $this->load->model('m_perusahaan');
       
    }
    public function index_get(){
    	$date = $this->get('date',TRUE);
    	$bln = date('m-Y',strtotime($date));
    	$cluster = $this->m_cluster->by_bln($bln)->row();

    	if (empty($cluster)){
    		$this->set_response([
                'status' => FALSE,
                'message' => 'Data K-Means tidak tersedia',
            ], REST_Controller::HTTP_OK); 
    	}else{
    		$data = $this->kmeans($cluster->cl_id);
    		$this->set_response([
                'status' => TRUE,
                'message' => 'Data K-Means tersedia',
                'data' => $data,
            ], REST_Controller::HTTP_OK); 
    	}
    }
    private function kmeans($id){;
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
	        		$item->s_status_cluster = 'Under Nomination';
	        		$item->s_cluster = 'Cluster 1';
	        		$sum_c1temp += $data_tgl->total_temp;
	        		$sum_c1pres += $item->pressure;
	        		$sum_c1vol += $item->vol_last_day;
	        		$count_c1++;
	        	}else{
	        		$item->c1 = '';
	        	}

	        	
	        	if ($dc2 < $dc3 && $dc2 < $dc1){
	        		$item->c2 = 'X';
	        		$item->s_status_cluster = 'Normal';
	        		$item->s_cluster = 'Cluster 2';
	        		$sum_c2temp += $data_tgl->total_temp;
	        		$sum_c2pres += $item->pressure;
	        		$sum_c2vol += $item->vol_last_day;
	        		$count_c2++;
	        	}else{
	        		$item->c2 = '';
	        	}

	        	if ($dc3 < $dc2  && $dc3 < $dc1){
	        		$item->c3 = 'X';
	        		$item->s_status_cluster = 'Over Nomination';
	        		$item->s_cluster = 'Cluster 3';
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
        if (count($temp)>1){
        	return $temp[count($temp)-1];
        }else{
        	return $temp;	
        }
        
    }
    
}
