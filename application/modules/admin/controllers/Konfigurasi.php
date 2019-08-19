<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Konfigurasi extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_setting');

	}
	public function index()
	{
		$this->title 	= "Konfigurasi";
		$this->content 	= "konfigurasi/index";
		$this->assets 	= array('assets');
		
		$param = array(
		
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Konfigurasi";
		$this->content 	= "konfigurasi/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['setting'] = $this->m_setting->by_id($id)->row();

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{

		$data['s_value'] = $this->input->post('value',TRUE);
		if (empty($id)){
			fs_create_alert(['type'=>'danger','message'=>'Key Salah']);
			redirect('admin/konfigurasi');
			// $save = $this->m_perusahaan->insert($data);
			// if ($save){
			// 	fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
			// 	redirect('admin/perusahaan');
			// }else{
			// 	fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
			// 	redirect('admin/perusahaan/add');

			// }
		}else{
			$id = base64_decode($id);
			$save = $this->m_setting->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/konfigurasi');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/konfigurasi/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['s_key'] = $id;
		$delete = $this->m_setting->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/konfigurasi');
	}
	public function ajax_list()
	{
		$param = [];
		$list = $this->m_setting->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			
			$btngroup = '<div class="input-group">
					<button type="button" class="btn btn-xs btn-default pull-right dropdown-toggle" data-toggle="dropdown">
						<span> Action
						</span>
						<i class="fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu">
						<li>' . anchor("admin/konfigurasi/edit/".base64_encode($item->s_key),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->s_key;
			$row[] = $item->s_value;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_setting->count_all($param),
			"recordsFiltered" => $this->m_setting->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
