<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_tracking');
		$this->load->model('m_perusahaan');

	}
	public function index()
	{
		$this->title 	= "Tracking";
		$this->content 	= "tracking/index";
		$this->assets 	= array('assets');
		$wilayah = $this->m_perusahaan->all()->result();
		$data['perusahaan'][''] = 'All';
		foreach ($wilayah as $item) {
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}
		$param = array(
			'data'=>$data,
		);
		$this->template($param);
	}
	
	public function add()
	{
		$this->title 	= "Tracking";
		$this->content 	= "tracking/add";
		$this->assets 	= array('assets_form');

		$wilayah = $this->m_perusahaan->all()->result();
		$data['perusahaan'] = [];
		foreach ($wilayah as $item) {
			if (!($item->s_kategori=="multi" || $item->s_kategori=="offtaker")){
				continue;
			}
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Tracking";
		$this->content 	= "tracking/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['tracking'] = $this->m_tracking->by_id($id)->row();

		$wilayah = $this->m_perusahaan->all()->result();
		$data['perusahaan'] = [];
		foreach ($wilayah as $item) {
			if (!($item->s_kategori=="multi" || $item->s_kategori=="offtaker")){
				continue;
			}
			$data['perusahaan'][$item->s_id] = $item->s_perusahaan;
		}
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id="")
	{
		$data['tr_tgl'] 		= $this->input->post('tanggal',TRUE);
		$data['id_perusahaan'] 	= $this->input->post('perusahaan',TRUE);
		if (empty($id)){
			$data['td_vol'] 		= $this->input->post('vol',TRUE);
			$data['td_status'] 		= $this->input->post('status',TRUE);
			$save = $this->m_tracking->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/tracking');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/tracking/add');

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_tracking->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/tracking');
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/tracking/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id)
	{

		$id = base64_decode($id);
		$data['tr_id'] = $id;
		$delete = $this->m_tracking->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/tracking');
	}
	public function ajax_list()
	{
		$param['id_perusahaan'] = $this->input->post('id_perusahaan'); 
		$list = $this->m_tracking->get_datatables($param);
		$data = array();

		$no = $_POST['start'];

		foreach ($list as $item) {
			$td = $this->db->from('tracking_detail')->where('id_tracking',$item->tr_id)->order_by('td_id','desc')->get()->row();
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
						<li>' . anchor("admin/tracking/edit/".base64_encode($item->tr_id),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/tracking/hapus/".base64_encode($item->tr_id),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
						<li>' . anchor("admin/tracking/detail/".base64_encode($item->tr_id),"<i class=\"fa fa-edit\"></i>Detail Tracking") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = (empty($td)) ? $item->tr_tgl : $td->td_tgl;
			$row[] = $item->s_perusahaan;
			$row[] = @$td->td_vol;
			$row[] = @$td->td_status;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_tracking->count_all($param),
			"recordsFiltered" => $this->m_tracking->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
