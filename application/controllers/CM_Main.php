<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CM_Main extends CI_Controller {


  public function __construct()
	{
    parent::__construct();
		if($this->session->userdata('login')!=TRUE)
			redirect(base_url('login'));
		$role	= $this->session->userdata('role');
		//user allowed
		if($role != 'sysadm'){
			redirect(base_url('Main/restricted'));
		}
		$this->load->model('Rest_Engine');
		$this->load->model('Form_Orchestrator');
		$this->load->model('Logging');
		$this->load->model('Auth_Engine');
		date_default_timezone_set('Asia/Jakarta');
		$this->session->set_userdata(array('filter'=>FALSE, 'field'=>'', 'val'=>''));
	}
  public function lookup_member()
	{
		if(isset($_POST['update'])){
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$value = $this->input->post('value');
			$dsc = $this->input->post('dsc');
			$data = [
				'name' => $name,
				'value' => $value,
				'dsc' => $dsc
			];
			$this->db->where('id',$id);
			$this->db->update('lookup_member',$data);
			$msg = ['success','success updated member id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$name = $this->input->post('name');
			$value = $this->input->post('value');
			$dsc = $this->input->post('dsc');
			$data = [
				'name' => $name,
				'value' => $value,
				'dsc' => $dsc
			];
			$this->db->insert('lookup_member',$data);
			$msg = ['success','success added member id: '.$this->db->insert_id()];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$ref_member = [];
			foreach ($this->db->get('lookup_group')->result() as $group) {
				foreach (explode(',',$group->member) as $local_member_id) {
					if(in_array($local_member_id,$ref_member)==false){
							$ref_member[] = [$group->id,$local_member_id];
					}
				}
			}
			$sav_group =[];
			foreach ($ref_member as $valuex) {
				if($valuex[1]==$id){
					$sav_group[] =$valuex[0];
				}
			}

			if($sav_group!=[]){
				$msg = ['warning','cannot delete member id: '.$id.' as it is referenced by group id: '.implode(',',$sav_group)];
			}
			else{
				$this->db->where('id',$id);
				$this->db->delete('lookup_member');
				$msg = ['success','success deleted member id: '.$id];
			}

		}
		//pagination
		$config['base_url'] 		= base_url('CM_Main/lookup_member');
		$config['total_rows'] 		= $this->Logging->count_data('lookup_member');
		$config['per_page'] 		= 20;
		$config['uri_segment'] 		= 3;
		//pagination styles
		$config['first_link']       = 'First';
		$config['last_link']        = 'Last';
		$config['next_link']        = 'Next';
		$config['prev_link']        = 'Prev';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination float-right">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		//pagination initialize
		$this->pagination->initialize($config);
		$page = $this->uri->segment(3,0);

		$allValue = $this->Logging->get($config['per_page'], $page, 'lookup_member', FALSE);
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue
		];
		$this->load->view('a_header');
		$this->load->view('cm_module/lookup-member',$metadata);
		$this->load->view('b_footer');
	}
	public function lookup_group()
	{
		if(isset($_POST['update'])){
			$id =$this->input->post('id');
			$title =$this->input->post('title');
			$name =$this->input->post('name');
			$dsply_tp =$this->input->post('dsply_tp');
			$member = implode(',',$this->input->post('member'));

			$data = [
				'title' => $title,
				'name' => $name,
				'dsply_tp' => $dsply_tp,
				'member' => $member
			];
			$this->db->where('id',$id);
			$this->db->update('lookup_group',$data);
			$msg = ['success','success updated value id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$title = $this->input->post('title');
			$name = $this->input->post('name');
			$dsply_tp = $this->input->post('dsply_tp');
			$member = implode(',',$this->input->post('member'));
			$member_ref = $this->input->post('member');
			$data = [
				'title' => $title,
				'name' => $name,
				'dsply_tp' => $dsply_tp,
				'member' => $member
			];
			$this->db->insert('lookup_group',$data);
			$msg = ['success','success added value id: '.$this->db->insert_id()];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$ref_group = [];
			foreach ($this->db->get('req_curl')->result() as $req) {
				foreach (explode(',',$req->lookup_group) as $local_group_id) {
					if(in_array($local_group_id,$ref_group)==false){
							$ref_group[] = [$req->id,$local_group_id];
					}
				}
			}
			$sav_req =[];
			foreach ($ref_group as $valuex) {
				if($valuex[1]==$id){
					$sav_req[] =$valuex[0];
				}
			}

			if($sav_req!=[]){
				$msg = ['warning','cannot delete group id: '.$id.' as it is referenced by request payload id: '.implode(',',$sav_req)];
			}
			else{
				$this->db->where('id',$id);
				$this->db->delete('lookup_group');
				$msg = $msg = ['success','success deleted group id: '.$id];
			}

		}
		//pagination
		$config['base_url'] 		= base_url('CM_Main/lookup_group');
		$config['total_rows'] 		= $this->Logging->count_data('lookup_group');
		$config['per_page'] 		= 20;
		$config['uri_segment'] 		= 3;
		//pagination styles
		$config['first_link']       = 'First';
		$config['last_link']        = 'Last';
		$config['next_link']        = 'Next';
		$config['prev_link']        = 'Prev';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination float-right">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		//pagination initialize
		$this->pagination->initialize($config);
		$page = $this->uri->segment(3,0);

		$allValue = $this->Logging->get($config['per_page'], $page, 'lookup_group', FALSE);
		$allMember = $this->db->get('lookup_member')->result();
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allMember' => $allMember
		];
		$this->load->view('a_header');
		$this->load->view('cm_module/lookup-group',$metadata);
		$this->load->view('b_footer');
	}
	public function input_group()
	{
		if(isset($_POST['update'])){
			$id =$this->input->post('id');
			$name = $this->input->post('name');
			$dsc = $this->input->post('dsc');
			$title = $this->input->post('title');
			$plc_hldr = $this->input->post('plc_hldr');
			$value = $this->input->post('value');


			$data = [
				'name' => $name,
				'dsc' => $dsc,
				'title' => $title,
				'plc_hldr' => $plc_hldr,
				'value' => $value
			];
			$this->db->where('id',$id);
			$this->db->update('input_group',$data);
			$msg = ['success','success updated value id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$name = $this->input->post('name');
			$dsc = $this->input->post('dsc');
			$title = $this->input->post('title');
			$plc_hldr = $this->input->post('plc_hldr');
			$value = $this->input->post('value');
			$data = [
				'name' => $name,
				'dsc' => $dsc,
				'title' => $title,
				'plc_hldr' => $plc_hldr,
				'value' => $value
			];
			$this->db->insert('input_group',$data);
			$msg = ['success','success added value id: '.$this->db->insert_id()];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$ref_group = [];
			foreach ($this->db->get('req_curl')->result() as $req) {
				foreach (explode(',',$req->lookup_group) as $local_group_id) {
					if(in_array($local_group_id,$ref_group)==false){
							$ref_group[] = [$req->id,$local_group_id];
					}
				}
			}
			$sav_req =[];
			foreach ($ref_group as $valuex) {
				if($valuex[1]=='i-'.$id || $valuex[1]=='h-'.$id){
					$sav_req[] =$valuex[0];
				}
			}

			if($sav_req!=[]){
				$msg = ['warning','cannot delete group id: '.$id.' as it is referenced by request payload id: '.implode(',',$sav_req)];
			}
			else{
				$this->db->where('id',$id);
				$this->db->delete('input_group');
				$msg = $msg = ['success','success deleted group id: '.$id];
			}

		}
		//pagination
		$config['base_url'] 		= base_url('CM_Main/input_group');
		$config['total_rows'] 		= $this->Logging->count_data('input_group');
		$config['per_page'] 		= 20;
		$config['uri_segment'] 		= 3;
		//pagination styles
		$config['first_link']       = 'First';
		$config['last_link']        = 'Last';
		$config['next_link']        = 'Next';
		$config['prev_link']        = 'Prev';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination float-right">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		//pagination initialize
		$this->pagination->initialize($config);
		$page = $this->uri->segment(3,0);

		$allValue = $this->Logging->get($config['per_page'], $page, 'input_group', FALSE);
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue
		];
		$this->load->view('a_header');
		$this->load->view('cm_module/input-group',$metadata);
		$this->load->view('b_footer');
	}
}
