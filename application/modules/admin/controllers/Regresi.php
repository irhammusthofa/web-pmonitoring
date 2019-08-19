<?php
require APPPATH . 'third_party/Regression/Matrix.php';
require APPPATH . 'third_party/Regression/Regression.php';
defined('BASEPATH') OR exit('No direct script access allowed');

use Regression\Matrix;
use Regression\Regression;

//use Phpml\Regression\LeastSquares;
class Regresi extends Admin_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_shipper');
        $this->load->model('m_wilayah');
        $this->load->model('m_setting');
       
    }
    public function index(){
    	$this->title 	= "Regresi";
		$this->content 	= "regresi/index";
		$this->assets 	= array();
		
    	$setting = $this->m_setting->by_id('dollar_mscf')->row();
    	if (empty($setting )){
    		$dollar = 0;
    	}else{
    		$dollar = $setting->s_value;
    	}
    	$wilayah = $this->m_wilayah->all()->result();

        $tmp_bln             = $this->input->get('bulan',TRUE);
        if (empty($tmp_bln)){
        	$bulan = date('Y-m');
        }else{
        	$bulan = date('Y-m',strtotime($tmp_bln));
        }
        $result = [];
        	$date = new DateTime('last day of '.$bulan);
        	$end = $date->format('d');
        $total_minggu = 0;
        foreach ($wilayah as $wil) {
        	$param['id_wilayah']    = $wil->l_id;
        	$pendapatan = 0;
        	$penjualan = 0;
        	$minggu = 0;
        	$s_minggu = 0;
        	$o_minggu = 0;
        	$hari = 0;
        	$prediksi_bulan = 0;
        	$pendapatan_minggu = [];
        	$single_minggu = [];
        	$offtaker_minggu = [];
        	$arr_minggu = [];
        	$array_minggu = [];
	        for ($i=1; $i <= $end; $i++) { 
	            $param['start_date']    = $bulan.'-'.$i;
	            $param['end_date']      = $bulan.'-'.$i;
	            $param['kategori']      = 'offtaker';
	            $single                 = $this->m_shipper->periode($param)->row();
	            $param['kategori']      = 'multi';
	            $multi                  = $this->m_shipper->periode($param)->row();
	            $param['kategori']      = 'shipper';
	            $shipper                = $this->m_shipper->periode($param)->row();
	            $x1 = 0;
	            $x2 = 0;
	            $y = 0;
	            if (!empty($single)){
	                $x1 = $single->total;
	            }
	            if (!empty($multi)){
	                $x2 = $multi->total;
	            }
	            if (!empty($shipper)){
	                $y = $shipper->total;
	            }
	            $pendapatan += $y;
	            $penjualan += ($x1 + $x2);
	            if ($hari < 7) {
	            	$minggu += $y - ($x1 + $x2);
	            	$s_minggu += $x1;
	            	$o_minggu += $x2;
	            	$arr_minggu[] = $y - ($x1 + $x2);
	            	if ($i==$end){
	            		$pendapatan_minggu[] = $minggu;
	            		$single_minggu[] = $s_minggu;
	            		$offtaker_minggu[] = $o_minggu;
	            		$array_minggu[] = $arr_minggu;
	            	}
	            }else{
	            	$array_minggu[] = $arr_minggu;
	            	$pendapatan_minggu[] = $minggu;
	            	$single_minggu[] = $s_minggu;
	            	$offtaker_minggu[] = $o_minggu;

        			$arr_minggu = [];
	            	$hari = 0;
	            	$minggu = 0;
	            	$s_minggu = 0;
	            	$o_minggu = 0;
	            	$minggu += $y - ($x1 + $x2);
	            	$s_minggu += $x1;
	            	$o_minggu += $x2;
	            	$arr_minggu[] = $y - ($x1 + $x2);
	            	if ($i==$end){
	            		$pendapatan_minggu[] = $minggu;
	            		$single_minggu[] = $s_minggu;
	            		$offtaker_minggu[] = $o_minggu;
	            		$array_minggu[] = $arr_minggu;
	            	}
	            }
	            $hari++;
	            $predictors[] = array($x1,$x2); 
	            $predicted[] = array($y);
	        }
	        $barrel = $pendapatan - $penjualan;
	        $ySum = 0;
	        $yArr = [];
	        for ($i=0; $i < count($predicted); $i++) { 
	            $ySum += $predicted[$i][0];
	            $yArr[] = $predicted[$i][0];
	            //$predicted[$i][0];
	        }
	        $stdev = $this->stdev($yArr);
	        $mean = $ySum/count($predicted);

	        for ($i=0; $i < count($predicted); $i++) { 
	            $normalize[] = $this->normalize($predicted[$i][0],$mean,$stdev);
	        }
	        

	        $regression = new Regression();
	        $regression->setX(new Matrix($predictors));
	        $regression->setY(new Matrix($predicted));
	        $regression->exec();

	        $prediksi = [];
	        $c[6] = [325.069,-4.545,1.23];
	        $c[7] = [-0.00000000000014,-5,1.759];
	        $c[2] = [3.319,3.342,1.207];
	        $c[4] = [-9.915,-4.797,1.847];
	        $c[5] = [-15.599,40.786,0.394];
	        $c[3] = [0,0,0];

	        for ($i=0; $i < count($pendapatan_minggu); $i++) { 
	        	$t_prediksi = $c[$wil->l_id][0] + ($c[$wil->l_id][1] * $single_minggu[$i]) + ($c[$wil->l_id][2] * $offtaker_minggu[$i]);
	        	if ($wil->l_id == 3){
	        		$prediksi[] = 0;
	        	}else{
	        		$prediksi[] = $t_prediksi - $single_minggu[$i] - $offtaker_minggu[$i];
	        	}
	        	$prediksi_bulan += $prediksi[$i];
	        	
	        }

	        $result[] = [
	        	'id_wilayah' => $wil->l_id,
	        	'nama_wilayah' => $wil->l_lokasi,
	            'Coefficients' => $regression->getCoefficients(),
	            'StdErr' => $regression->getStandardError(),
	            'Coef P' => $regression->getPValues(),
	            'RSquare' => $regression->getRSquare(),
	            'SSE' => $regression->getSSE(),
	            'SSR' => $regression->getSSR(),
	            'SSTO' => $regression->getSSTO(),
	            'F' => $regression->getF(),
	            'TStats' => $regression->getTStats(),
	            'PValues' => $regression->getPValues(),
	            'prediksi' => $prediksi,
	            'prediksi-bulan' => $prediksi_bulan,
	            'dollar' => $dollar,
	            //'Normalize' => $normalize,
	            'barrel' => $barrel,
	            'pendapatan-minggu' => $pendapatan_minggu,
	            'data' => $array_minggu,
	        ];
	        if ($total_minggu < count($pendapatan_minggu)){
	        	$total_minggu = count($pendapatan_minggu);
	        }
        }
        $this->array_sort_by_column($result,'barrel',SORT_DESC);
  //       usort($result, function($a, $b) {
		//     return $a['barrel'] <=> $b['barrel'];
		// });
		$bulan = $tmp_bln;
		$result['total_minggu'] = $total_minggu;
		$result['bulan'] = $bulan;
        $param = array(
        	'data' 			=> $result,
		);
        $this->template($param);


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
