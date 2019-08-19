<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_perusahaan');
		$this->load->model('m_lokasi');

	}
	public function index()
	{
		$this->title 	= "Perusahaan";
		$this->content 	= "perusahaan/index";
		$this->assets 	= array('assets');
		$wilayah = $this->m_lokasi->all()->result();
		$data['wilayah'][''] = 'All';
		foreach ($wilayah as $item) {
			$data['wilayah'][$item->l_id] = $item->l_lokasi;
		}

		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Perusahaan";
		$this->content 	= "perusahaan/add";
		$this->assets 	= array('assets_form');

		$wilayah = $this->m_lokasi->all()->result();
		$data['wilayah'] = [];
		foreach ($wilayah as $item) {
			$data['wilayah'][$item->l_id] = $item->l_lokasi;
		}
		$data['kategori'] = ['shipper' => 'Shipper','offtaker' => 'Offtaker','multi' => 'Multi Offtaker'];
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Perusahaan";
		$this->content 	= "perusahaan/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['perusahaan'] = $this->m_perusahaan->by_id($id)->row();

		$wilayah = $this->m_lokasi->all()->result();
		$data['wilayah'] = [];
		foreach ($wilayah as $item) {
			$data['wilayah'][$item->l_id] = $item->l_lokasi;
		}
		$data['kategori'] = ['shipper' => 'Shipper','offtaker' => 'Offtaker','multi' => 'Multi Offtaker'];
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['s_perusahaan'] = $this->input->post('nama',TRUE);
		$data['s_kategori'] = $this->input->post('kategori',TRUE);
		$data['id_wilayah'] = $this->input->post('wilayah',TRUE);
		$data['s_alamat'] = $this->input->post('alamat',TRUE);
		if (empty($id)){
			$save = $this->m_perusahaan->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/perusahaan');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/perusahaan/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_perusahaan->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/perusahaan');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/perusahaan/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['s_id'] = $id;
		$delete = $this->m_perusahaan->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/perusahaan');
	}
	public function ajax_list()
	{
		$param['id_wilayah'] = $this->input->post('id_lokasi'); 
		$list = $this->m_perusahaan->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$arrParam = array(
				'id_encode' => base64_encode($item->s_id),
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
						<li>' . anchor("admin/perusahaan/edit/".base64_encode($item->s_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/perusahaan/hapus/".base64_encode($item->s_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->s_perusahaan;
			$row[] = $item->l_lokasi;
			$row[] = ($item->s_kategori =='multi') ? 'Multi Offtaker' : $item->s_kategori;
			$row[] = $item->s_alamat;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_perusahaan->count_all($param),
			"recordsFiltered" => $this->m_perusahaan->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
