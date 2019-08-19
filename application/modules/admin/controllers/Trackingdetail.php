<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Trackingdetail extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth();
		$this->load->model('m_tracking');
		$this->load->model('m_trackingdetail');

	}
	public function index($id)
	{
		$id = base64_decode($id);
		$this->title 	= "Tracking Detail";
		$this->content 	= "trackingdetail/index";
		$this->assets 	= array('assets');
		$data['tracking'] = $this->m_tracking->by_id($id)->row();
		$data['tracking_detail'] = $this->m_trackingdetail->by_tracking($id)->result();
		$data['id_tracking'] = $id;
		$param = array(
			'data' => $data
			
		);
		$this->template($param);
	}
	
	public function add($id_tracking)
	{
		$this->title 	= "Tracking Detail";
		$this->content 	= "trackingdetail/add";
		$this->assets 	= array('assets_form');

		$data['id_tracking'] = base64_decode($id_tracking);
		$data['tracking'] = $this->m_tracking->by_id($id_tracking)->row();
		
		$param = array(
			'data' => $data,

		);
		$this->template($param);
	}
	
	public function edit($id)
	{
		$this->title 	= "Tracking Detail";
		$this->content 	= "trackingdetail/edit";
		$this->assets 	= array('assets_form');

		$id = base64_decode($id);
		$data['tracking_detail'] = $this->m_trackingdetail->by_id($id)->row();
		$param = array(
			'data' => $data,
		);
		$this->template($param);
	}
	
	
	public function simpan($id_tracking,$id="")
	{
		$id_tracking = base64_decode($id_tracking);
		$data['td_tgl'] 		= $this->input->post('tanggal',TRUE);
		$data['td_vol'] 		= $this->input->post('vol',TRUE);
		$data['td_status'] 		= $this->input->post('status',TRUE);
		$data['id_tracking'] 	= $id_tracking;
		if (empty($id)){
			$save = $this->m_trackingdetail->insert($data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil disimpan']);
				redirect('admin/tracking/detail/'.base64_encode($id_tracking));
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal disimpan']);
				redirect('admin/tracking/detail/add/'.base64_encode($id_tracking));

			}
		}else{
			$id = base64_decode($id);
			$save = $this->m_trackingdetail->update($id,$data);
			if ($save){
				fs_create_alert(['type'=>'success','message'=>'Data berhasil diupdate']);
				redirect('admin/tracking/detail/'.base64_encode($id_tracking));
			}else{
				fs_create_alert(['type'=>'danger','message'=>'Data gagal diupdate']);
				redirect('admin/tracking/detail/edit/'.base64_encode($id));

			}
		}
	}
	
	public function hapus($id,$id_tracking)
	{

		$id = base64_decode($id);
		$data['td_id'] = $id;
		$delete = $this->m_trackingdetail->delete($data);
		if ($delete){
			fs_create_alert(['type'=>'success','message'=>'Data berhasil dihapus']);
		}else{
			fs_create_alert(['type'=>'danger','message'=>'Data gagal dihapus']);
		}
		redirect('admin/tracking/detail/'.$id_tracking);
	}
	public function ajax_list()
	{
		$param['id_tracking'] = $this->input->post('id_tracking'); 
		$list = $this->m_trackingdetail->get_datatables($param);
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
						<li>' . anchor("admin/trackingdetail/edit/".base64_encode($item->td_id).'/'.base64_encode($param['id_tracking']),"<i class=\"fa fa-edit\"></i>Edit") . '</li>
						<li>' . anchor("admin/trackingdetail/hapus/".base64_encode($item->td_id).'/'.base64_encode($param['id_tracking']),"<i class=\"fa fa-trash\"></i>Hapus") . '</li>
					</ul>
                </div>';
                
			$no++;
			$row = array();
			$row[] = $btngroup;
			$row[] = $item->td_tgl;
			$row[] = $item->td_vol;
			$row[] = $item->td_status;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_trackingdetail->count_all($param),
			"recordsFiltered" => $this->m_trackingdetail->count_filtered($param),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
}
