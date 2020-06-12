<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PM_Main extends CI_Controller {
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
	public function menu_group()
	{
		if(isset($_POST['update'])){
			$id = $this->input->post('id');
			$title = $this->input->post('title');
			$icon = $this->input->post('icon');
			$member = implode(',',$this->input->post('member'));
			$data = [
				'title' => $title,
				'icon' => $icon,
				'member' => $member
			];
			$this->db->where('id',$id);
			$this->db->update('menu_group',$data);
			$msg = ['success','success updated group id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$title = $this->input->post('title');
			$icon = $this->input->post('icon');
			$member = implode(',',$this->input->post('member'));
			$data = [
				'title' => $title,
				'icon' => $icon,
				'member' => $member
			];
			$this->db->insert('menu_group',$data);
			$msg = ['success','success added menu group'];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$this->db->where('id',$id);
			$this->db->delete('menu_group');
		}
		//pagination
		$config['base_url'] 		= base_url('PM_Main/menu_group');
		$config['total_rows'] 		= $this->Logging->count_data('menu_group');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'menu_group', FALSE);
		$allPayload = $this->db->get('req_curl')->result();
		$allMember = $this->db->get('menu_member')->result();
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allPayload' => $allPayload,
			'allMember' => $allMember
		];
		$this->load->view('a_header');
		$this->load->view('pm_module/menu-group',$metadata);
		$this->load->view('b_footer');
	}
	public function menu_member()
	{
		if(isset($_POST['update'])){
			$id = $this->input->post('id');
			$rest_type = $this->input->post('rest_type');
			$pyld_name = $this->input->post('payload_id');
			$title = $this->input->post('title');
			$data = [
				'url' => $rest_type.'/'.$pyld_name,
				'title' => $title
			];
			$this->db->where('id',$id);
			$this->db->update('menu_member',$data);
			$msg = ['success','success updated member id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$rest_type = $this->input->post('rest_type');
			$pyld_name = $this->input->post('payload_id');
			$title = $this->input->post('title');
			$data = [
				'url' => $rest_type.'/'.$pyld_name,
				'title' => $title
			];
			$this->db->insert('menu_member',$data);
			$msg = ['success','success added menu member'];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$ref_member = [];
			foreach ($this->db->get('menu_group')->result() as $group) {
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
				$this->db->delete('menu_member');
				$msg = ['success','success deleted member id: '.$id];
			}

		}
		//pagination
		$config['base_url'] 		= base_url('PM_Main/menu_member');
		$config['total_rows'] 		= $this->Logging->count_data('menu_member');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'menu_member', FALSE);
		$allPayload = $this->db->get('req_curl')->result();
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allPayload' => $allPayload
		];
		$this->load->view('a_header');
		$this->load->view('pm_module/menu-member',$metadata);
		$this->load->view('b_footer');
	}
	public function req_payload_group($payload_id)
	{
		if(isset($_POST['new'])){
			$group_id =$this->input->post('group');
			if($group_id[0]!='l'){
				$id = implode('-',$group_id);
			}
			else {
				$id = $group_id[1];
			}
			$act_id = $this->input->post('act_id');
			$this->db->where('act_id',$act_id);
			$prev_group = $this->db->get('req_curl')->row()->lookup_group;
			if($prev_group==''){
				$this->db->where('act_id',$act_id);
				$this->db->update('req_curl',['lookup_group'=>$id]);
			}else{
				$this->db->where('act_id',$act_id);
				$this->db->update('req_curl',['lookup_group'=>$prev_group.','.$id]);
			}
			$msg = ['success','success added group on payload id: '.$act_id];

		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$act_id = $this->input->post('act_id');
			$group = explode(',',$this->db->get_where('req_curl',['act_id'=>$act_id])->row()->lookup_group);
			unset($group[$id]);
			$fin_group = implode(',',$group);

			$this->db->where('act_id',$act_id);
			$this->db->update('req_curl',['lookup_group'=>$fin_group]);

			$msg = ['success','success deleted group on payload id: '.$act_id];

		}
		elseif(isset($_POST['genlog'])){
			$act_id = $this->input->post('act_id');
			$log_group = explode(',',$this->db->get_where('req_curl',['act_id'=>$act_id])->row()->lookup_group);

			$ln = 1;
			foreach ($log_group as  $lg) {
				if(strpos($lg,'i')!==false){
					$lg_id = explode('-',$lg);
					$this->db->where('id',$lg_id[1]);
					$lg_fin = $this->db->get('input_group')->row();
					$log_val[] = ['act_id'=>$act_id,'seq_id'=>$ln,'key'=>$lg_fin->title,'value'=>$lg_fin->name];
				}
				elseif(strpos($lg,'h')!==false){
					$lg_id = explode('-',$lg);
					$this->db->where('id',$lg_id[1]);
					$lg_fin = $this->db->get('input_group')->row();
					$log_val[] = ['act_id'=>$act_id,'seq_id'=>$ln,'key'=>$lg_fin->title,'value'=>$lg_fin->name];
				}
				else{
					$this->db->where('id',$lg);
					$lg_fin = $this->db->get('lookup_group')->row();
					$log_val[] = [
							'act_id' => $act_id,
							'seq_id' => $ln,
							'key' => $lg_fin->title,
							'value'=> $lg_fin->name
						];
				}
				$ln +=1;

			}

			$this->db->where('act_id',$act_id);
			$this->db->delete('postlog_val');
			foreach ($log_val as $log) {
				$this->db->insert('postlog_val',$log);
			}
			$msg = ['success','success generate log on this payload '];
		}
		$this->db->where('act',$payload_id);
		$act_id = $this->db->get('id_def')->row()->act_id;

		$this->db->where('id',$payload_id);
		$rawValue = explode(',',$this->db->get('req_curl')->row()->lookup_group);
		$n = 0;
		foreach ($rawValue as $valuex) {

			if(strpos($valuex,'i')!==false){
				$valuexx = explode('-',$valuex);
				$allValue[]=['i',$this->db->get_where('input_group',['id'=>$valuexx[1]])->row(),$n];
			}
			elseif(strpos($valuex,'h')!==false){
				$valuexx = explode('-',$valuex);
				$allValue[]=['h',$this->db->get_where('input_group',['id'=>$valuexx[1]])->row(),$n];
			}
			else{
				if($valuex!=''){
						$allValue[] = ['l',$this->db->get_where('lookup_group',['id'=>$valuex])->row(),$n];
				}
				else
					$allValue[] ='';
			}

			$n += 1;
		}
		$InpGroup = $this->db->get('input_group')->result();
		$LookGroup = $this->db->get('lookup_group')->result();
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'allValue' => $allValue,
			'InpGroup' => $InpGroup,
			'LookGroup' => $LookGroup,
			'act_id' => $act_id,
			'payload_id' => $payload_id
		];
		$this->load->view('a_header');
		$this->load->view('pm_module/req-payload-group',$metadata);
		$this->load->view('b_footer');
	}
	public function req_payload()
	{
		if(isset($_POST['update'])){
			$new_id = $this->input->post('new_id');
			$old_id = $this->input->post('old_id');
			$act_id = $this->input->post('act_id');
			$param = $this->input->post('param');
			$header = $this->input->post('header');
			$url_add_path = $this->input->post('url_add_path');
			$title = $this->input->post('title');

			$data = [
				'id' => $new_id,
				'act_id' => $act_id,
				'param' => $param,
				'header' => $header,
				'url_add_path' => $url_add_path,
				'title' => $title
			];

			if($new_id==$old_id){
				$this->db->set($data);
				$this->db->where('id',$old_id);
				$this->db->update('req_curl');
				$msg = ['success','update success'];
			}
			else{
			$this->db->where('id',$new_id);
			$match = $this->db->get('req_curl')->num_rows();
				if($match>0){
					$msg = ['warning','payload_id : '.$new_id.' already exist!'];
				}
				else{
					$this->db->set($data);
					$this->db->where('id',$old_id);
					$this->db->update('req_curl');
					$msg = ['success','update success'];
				}
			}
			$data_def = [
				'act_id' =>$act_id,
				'act' => $new_id,
				'def' => $title
			];
			$this->db->where('act_id',$act_id);
			$this->db->update('id_def',$data_def);
		}
		elseif(isset($_POST['new'])){
			$id = $this->input->post('id');
			$act_id = $this->input->post('act_id');
			$param = $this->input->post('param');
			$header = $this->input->post('header');
			$url_add_path = $this->input->post('url_add_path');
			$title = $this->input->post('title');

			$data = [
				'id' => $id,
				'act_id' => $act_id,
				'param' => ($param==null)?0:$param,
				'header' => ($header==null)?0:$header,
				'url_add_path' => ($url_add_path==null)?0:$url_add_path,
				'title' => $title
			];
			$data_def = [
				'act_id' =>$act_id,
				'act' => $id,
				'def' => $title
			];

			$this->db->where('id',$id);
			$match = $this->db->get('req_curl')->num_rows();
			if($match>0){
				$msg = ['warning','username : '.$id.' already exist!'];
			}
			else{
				$this->db->insert('req_curl',$data);
				$msg = ['success','success added payload id: '.$id];
			}
			$this->db->insert('id_def',$data_def);
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$ref_member = [];

			$this->db->where('id',$id);
			$this->db->delete('req_curl');

			$msg = ['success','success deleted payload id: '.$id];
			$this->db->where('act_id',$id);
			$this->db->delete('id_def');
		}
		//pagination
		$config['base_url'] 		= base_url('PM_Main/req_payload');
		$config['total_rows'] 		= $this->Logging->count_data('req_curl');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'req_curl', FALSE);
		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue
		];
		$this->load->view('a_header');
		$this->load->view('pm_module/req-payload',$metadata);
		$this->load->view('b_footer');
	}
	//###################################################### LOOKUP ##########################################3



}
