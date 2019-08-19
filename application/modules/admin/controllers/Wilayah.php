<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_lokasi');

	}
	public function index()
	{
		$this->title 	= "Wilayah";
		$this->content 	= "wilayah/index";
		$this->assets 	= array('assets');
		$param = array(
		);
		$this->template($param);
	}
	
	public function ajax_list()
	{
		$param  = [];
		$list = $this->m_lokasi->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$no++;
			$row = array();

			$row[] = $item->l_lokasi;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_lokasi->count_all($param),
			"recordsFiltered" => $this->m_lokasi->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
