<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipper extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_shipper');
		$this->load->model('m_perusahaan');

	}
	public function index()
	{
		$this->title 	= "Shipper";
		$this->content 	= "shipper/index";
		$this->assets 	= array('assets');
		$wilayah = $this->m_perusahaan->all_kategori(['shipper'])->result();
		$data['perusahaan']['shipper'] = 'All';
		foreach ($wilayah as $item) {
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Shipper";
		$this->content 	= "shipper/add";
		$this->assets 	= array('assets_form');

		$wilayah = $this->m_perusahaan->all_kategori(['shipper'])->result();
		$data['perusahaan'] = [];
		foreach ($wilayah as $item) {
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Shipper";
		$this->content 	= "shipper/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['shipper'] = $this->m_shipper->by_id($id)->row();

		$wilayah = $this->m_perusahaan->all_kategori(['shipper'])->result();
		$data['perusahaan'] = [];
		foreach ($wilayah as $item) {
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['tgl'] = $this->input->post('tanggal',TRUE);
		$data['id_shipper'] = $this->input->post('perusahaan',TRUE);
		$data['normal'] = $this->input->post('normal',TRUE);
		$data['dp'] = $this->input->post('dp',TRUE);
		$data['temp'] = $this->input->post('temp',TRUE);
		$data['pressure'] = $this->input->post('pressure',TRUE);
		$data['vol_last_hour'] = $this->input->post('vol_last_hour',TRUE);
		$data['vol_last_day'] = $this->input->post('vol_last_day',TRUE);
		$data['flow_rate'] = $this->input->post('flow_rate',TRUE);
		$data['comment'] = $this->input->post('comment',TRUE);
		$data['diff'] = $this->input->post('diff',TRUE);
		$data['id_user'] = $this->user->u_id;

		if (empty($id)){
			$save = $this->m_shipper->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/shipper');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/shipper/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_shipper->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/shipper');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/shipper/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['no'] = $id;
		$delete = $this->m_shipper->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/shipper');
	}
	public function ajax_list()
	{
		$param['id_shipper'] = $this->input->post('id_shipper'); 
		$param['tgl'] = $this->input->post('tgl'); 
		$list = $this->m_shipper->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->no),
				'item' => $item,
			);
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
						<li>' . anchor("admin/shipper/edit/".base64_encode($item->no),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/shipper/hapus/".base64_encode($item->no),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->tgl;
			$row[] = $item->s_perusahaan;
			$row[] = ($item->s_kategori =='multi') ? 'Multi Offtaker' : $item->s_kategori;
			$row[] = $item->normal;
			$row[] = $item->dp;
			$row[] = $item->temp;
			$row[] = $item->pressure;
			$row[] = $item->vol_last_hour;
			$row[] = $item->vol_last_day;
			$row[] = $item->flow_rate;
			$row[] = $item->comment;
			$row[] = $item->diff;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_shipper->count_all($param),
			"recordsFiltered" => $this->m_shipper->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
