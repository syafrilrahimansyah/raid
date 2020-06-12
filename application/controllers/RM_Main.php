<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RM_Main extends CI_Controller {
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
  public function fltr(){
    if(isset($_POST['update'])){
			$gen_id = $this->input->post('id');
			$act_id = $this->input->post('act_id');
      $value = $this->input->post('group');
			$data = [
				'act_id' => $act_id,
				'value' => $value
			];
			$this->db->where('gen_id',$gen_id);
			$this->db->update('fltr', $data);
			$msg = ['success','success updated group id: '.$act_id];
		}
		elseif(isset($_POST['new'])){
			$act_id = $this->input->post('payload_id');
			$data = [
				'act_id' => $act_id
			];
      if($this->db->get_where('fltr',['act_id'=>$act_id])->num_rows()>=1){
        $msg = ['warning','Payload ID already exist'];
      }else{
        $this->db->insert('fltr',$data);
        $msg = ['success','success added menu group'];
      }
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$this->db->where('gen_id',$id);
			$this->db->delete('fltr');
		}
		//pagination
		$config['base_url'] 		= base_url('RM_Main/fltr');
		$config['total_rows'] 		= $this->Logging->count_data('fltr');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'fltr', FALSE);
		$allPayload = $this->db->get('req_curl')->result();

    foreach ($allValue as $value) {
      $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
      if(isset($rp->lookup_group)){
        if($rp->lookup_group!=''){
          $lg = explode(',',$rp->lookup_group);
          foreach ($lg as $g) {
            if(strpos($g,'i')!==false || strpos($g,'h')!==false){
              $g_inp = explode('-',$g);
              $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
            }
            else
              $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
          }
        } else $group = [];
      } else $group = [];

      $allCombine[] = [
        'id' => $value->gen_id,
        'payload_id' => $value->act_id,
        'key' => $value->value,
        'rp_id' => (isset($rp->id))?$rp->id:'value not found',
        'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
        'rp_group' => $group
      ];
      unset($group);
    }

		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allPayload' => $allPayload,
			'allCombine' => $allCombine
		];
		$this->load->view('a_header');
		$this->load->view('rm_module/fltr',$metadata);
		$this->load->view('b_footer');
  }
  //######################################################################################################################
  //######################################################################################################################
  public function stval(){
    if(isset($_POST['update'])){
      $id = $this->input->post('id');
      $act_id = $this->input->post('payload_id');
      $fltr_id = $this->input->post('fltr_val');
      $seq_id = $this->input->post('seq');
      $value = $this->input->post('val');
      $data = [
        'act_id' => $act_id,
        'fltr_id' => $fltr_id,
        'seq_id' => $seq_id,
        'value' => $value,
			];
      $this->db->where('gen_id',$id);
      $this->db->update('header_val',$data);
			$msg = ['success','success updated static value id: '.$id];
		}
		elseif(isset($_POST['new'])){
			$act_id = $this->input->post('payload_id');
      $fltr_id = $this->input->post('fltr_val');
      $seq_id = $this->input->post('seq');
      $value = $this->input->post('val');
      $data = [
        'act_id' => $act_id,
        'fltr_id' => $fltr_id,
        'seq_id' => $seq_id,
        'value' => $value,
			];
      $this->db->insert('header_val',$data);
      $msg = ['success','success added static value'];
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$this->db->where('gen_id',$id);
			$this->db->delete('header_val');
      $msg = ['success','success delete static value id: '.$id];
		}
		//pagination
		$config['base_url'] 		= base_url('RM_Main/stval');
		$config['total_rows'] 		= $this->Logging->count_data('header_val');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'header_val', FALSE);
		$allPayload = $this->db->get('req_curl')->result();

    foreach ($allValue as $value) {
      $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
      if(isset($rp->lookup_group)){
        if($rp->lookup_group!=''){
          $lg = explode(',',$rp->lookup_group);
          foreach ($lg as $g) {
            if(strpos($g,'i')!==false || strpos($g,'h')!==false){
              $g_inp = explode('-',$g);
              $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
            }
            else
              $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
          }
        } else $group = [];
      } else $group = [];

      $allCombine[] = [
        'id' => $value->gen_id,
        'payload_id' => $value->act_id,
        'key' => $value->value,
        'rp_id' => (isset($rp->id))?$rp->id:'value not found',
        'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
        'rp_group' => $group
      ];
      unset($group);
    }
    $metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allPayload' => $allPayload,
		];
		$this->load->view('a_header');
		$this->load->view('rm_module/stval',$metadata);
		$this->load->view('b_footer');
  }
  //######################################################################################################################
  //######################################################################################################################
  public function dyval(){
      if(isset($_POST['update'])){
  			$gen_id = $this->input->post('id');
  			$act_id = $this->input->post('payload_id');
        $seq_id = $this->input->post('seq');
        $part_id = $this->input->post('pos');
        $param = $this->input->post('group');
  			$data = [
          'act_id' => $act_id,
          'seq_id' => $seq_id,
          'part_id' => $part_id,
          'param' => $param
  			];
  			$this->db->where('gen_id',$gen_id);
  			$this->db->update('param_form', $data);
  			$msg = ['success','success updated dynamic value id: '.$act_id];
  		}
  		elseif(isset($_POST['new'])){
  			$act_id = $this->input->post('payload_id');
        $data = [
          'act_id' => $act_id
  			];
        $this->db->insert('param_form',$data);
        $msg = ['success','success added dynamic value'];
  		}
  		elseif(isset($_POST['remove'])){
  			$id = $this->input->post('id');
  			$this->db->where('gen_id',$id);
  			$this->db->delete('param_form');
        $msg = ['success','success delete dynamic value id: '.$id];
  		}
  		//pagination
  		$config['base_url'] 		= base_url('RM_Main/dyval');
  		$config['total_rows'] 		= $this->Logging->count_data('param_form');
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

  		$allValue = $this->Logging->get($config['per_page'], $page, 'param_form', FALSE);
  		$allPayload = $this->db->get('req_curl')->result();
      foreach ($allValue as $value) {
        $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
        if(isset($rp->lookup_group)){
          if($rp->lookup_group!=''){
            $lg = explode(',',$rp->lookup_group);
            foreach ($lg as $g) {
              if(strpos($g,'i')!==false || strpos($g,'h')!==false){
                $g_inp = explode('-',$g);
                $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
              }
              else
                $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
            }
          } else $group = [];
        } else $group = [];

        $allCombine[] = [
          'id' => $value->gen_id,
          'payload_id' => $value->act_id,
          'part' => $value->part_id,
          'seq' => $value->seq_id,
          'group' => $value->param,
          'rp_id' => (isset($rp->id))?$rp->id:'value not found',
          'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
          'rp_group' => $group
        ];
        unset($group);
      }
      $metadata = [
  			'alert' => (isset($msg))?$msg:null,
  			'pagination' => $this->pagination->create_links(),
  			'allValue' => $allValue,
  			'allPayload' => $allPayload,
        'allCombine' => $allCombine
  		];
  		$this->load->view('a_header');
  		$this->load->view('rm_module/dyval',$metadata);
  		$this->load->view('b_footer');
  }
  //######################################################################################################################
  //######################################################################################################################
  public function data(){
      if(isset($_POST['update'])){
  			$gen_id = $this->input->post('id');
  			$act_id = $this->input->post('payload_id');
        $seq_id = $this->input->post('seq');
        $fltr_id = $this->input->post('fltr');
        $key = $this->input->post('key');
        $value = $this->input->post('group');
  			$data = [
          'act_id' => $act_id,
          'seq_id' => $seq_id,
          'fltr_id' => $fltr_id,
          'key' => $key,
          'value' => $value
  			];
  			$this->db->where('gen_id',$gen_id);
  			$this->db->update('data_form', $data);
  			$msg = ['success','success updated data id: '.$gen_id];
  		}
  		elseif(isset($_POST['new'])){
  			$act_id = $this->input->post('payload_id');
        $data = [
          'act_id' => $act_id
  			];
        $this->db->insert('data_form',$data);
        $msg = ['success','success added data'];
  		}
  		elseif(isset($_POST['remove'])){
  			$id = $this->input->post('id');
  			$this->db->where('gen_id',$id);
  			$this->db->delete('data_form');
        $msg = ['success','success delete dynamic value id: '.$id];
  		}
  		//pagination
  		$config['base_url'] 		= base_url('RM_Main/data');
  		$config['total_rows'] 		= $this->Logging->count_data('data_form');
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

  		$allValue = $this->Logging->get($config['per_page'], $page, 'data_form', FALSE);
  		$allPayload = $this->db->get('req_curl')->result();

      foreach ($allValue as $value) {
        $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
        if(isset($rp->lookup_group)){
          if($rp->lookup_group!=''){
            $lg = explode(',',$rp->lookup_group);
            foreach ($lg as $g) {
              if(strpos($g,'i')!==false || strpos($g,'h')!==false){
                $g_inp = explode('-',$g);
                $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
              }
              else
                $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
            }
          } else $group = [];
        } else $group = [];

        $allCombine[] = [
          'id' => $value->gen_id,
          'payload_id' => $value->act_id,
          'fltr' => $value->fltr_id,
          'seq' => $value->seq_id,
          'key' => $value->key,
          'group' => $value->value,
          'rp_id' => (isset($rp->id))?$rp->id:'value not found',
          'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
          'rp_group' => $group
        ];
        unset($group);
      }

      $metadata = [
  			'alert' => (isset($msg))?$msg:null,
  			'pagination' => $this->pagination->create_links(),
  			'allValue' => $allValue,
  			'allPayload' => $allPayload,
        'allCombine' => $allCombine
  		];
  		$this->load->view('a_header');
  		$this->load->view('rm_module/data',$metadata);
  		$this->load->view('b_footer');
  }
//######################################################################################################################
//######################################################################################################################
  public function data_tmplt(){
    if(isset($_POST['update'])){
			$gen_id = $this->input->post('id');
      $act_id = $this->input->post('act_id');
      $fltr_id = $this->input->post('fltr_id');
      $value = $this->input->post('value');
			$data = [
        'act_id' => $act_id,
        'fltr_id' => $fltr_id,
        'value' => $value
			];
			$this->db->where('gen_id',$gen_id);
			$this->db->update('data_tmplt', $data);
			$msg = ['success','success updated group id: '.$act_id];
		}
		elseif(isset($_POST['new'])){
			$act_id = $this->input->post('act_id');
      $fltr_id = $this->input->post('fltr_id');
      $value = $this->input->post('value');
			$data = [
        'act_id' => $act_id,
        'fltr_id' => $fltr_id,
        'value' => $value
			];
      if($this->db->get_where('data_tmplt',['act_id'=>$act_id])->num_rows()>=1){
        $msg = ['warning','Payload ID already exist'];
      }else{
        $this->db->insert('data_tmplt',$data);
        $msg = ['success','success added menu group'];
      }
		}
		elseif(isset($_POST['remove'])){
			$id = $this->input->post('id');
			$this->db->where('gen_id',$id);
			$this->db->delete('data_tmplt');
		}
		//pagination
		$config['base_url'] 		= base_url('RM_Main/data_tmplt');
		$config['total_rows'] 		= $this->Logging->count_data('data_tmplt');
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

		$allValue = $this->Logging->get($config['per_page'], $page, 'data_tmplt', FALSE);
		$allPayload = $this->db->get('req_curl')->result();

    foreach ($allValue as $value) {
      $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
      if(isset($rp->lookup_group)){
        if($rp->lookup_group!=''){
          $lg = explode(',',$rp->lookup_group);
          foreach ($lg as $g) {
            if(strpos($g,'i')!==false || strpos($g,'h')!==false){
              $g_inp = explode('-',$g);
              $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
            }
            else
              $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
          }
        } else $group = [];
      } else $group = [];

      $allCombine[] = [
        'gen_id' => $value->gen_id,
        'act_id' => $value->act_id,
        'fltr_id' => $value->fltr_id,
        'value' => str_replace('"','&quot;',$value->value),
        'rp_id' => (isset($rp->id))?$rp->id:'value not found',
        'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
        'rp_group' => $group
      ];
      unset($group);
    }

		$metadata = [
			'alert' => (isset($msg))?$msg:null,
			'pagination' => $this->pagination->create_links(),
			'allValue' => $allValue,
			'allPayload' => $allPayload,
			'allCombine' => $allCombine
		];
		$this->load->view('a_header');
		$this->load->view('rm_module/data_tmplt',$metadata);
		$this->load->view('b_footer');
  }
  //######################################################################################################################
  //######################################################################################################################
    public function add_path(){
      if(isset($_POST['update'])){
  			$gen_id = $this->input->post('id');
        $act_id = $this->input->post('act_id');
        $fltr_id = $this->input->post('fltr');
        $val_type = $this->input->post('val_type');
        $value = $this->input->post('group');
  			$data = [
          'act_id' => $act_id,
          'fltr_id' => $fltr_id,
          'val_type' => $val_type,
          'value' => $value
  			];
  			$this->db->where('gen_id',$gen_id);
  			$this->db->update('url_add_path', $data);
  			$msg = ['success','success updated group id: '.$gen_id];
  		}
  		elseif(isset($_POST['new'])){
  			$act_id = $this->input->post('act_id');
        $data = [
          'act_id' => $act_id
  			];
        $this->db->insert('url_add_path',$data);
        $msg = ['success','success added menu group'];
  		}
  		elseif(isset($_POST['remove'])){
  			$id = $this->input->post('id');
  			$this->db->where('gen_id',$id);
  			$this->db->delete('url_add_path');
  		}
  		//pagination
  		$config['base_url'] 		= base_url('RM_Main/add_path');
  		$config['total_rows'] 		= $this->Logging->count_data('url_add_path');
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

  		$allValue = $this->Logging->get($config['per_page'], $page, 'url_add_path', FALSE);
  		$allPayload = $this->db->get('req_curl')->result();

      foreach ($allValue as $value) {
        $rp = $this->db->get_where('req_curl',['act_id'=>$value->act_id])->row();
        if(isset($rp->lookup_group)){
          if($rp->lookup_group!=''){
            $lg = explode(',',$rp->lookup_group);
            foreach ($lg as $g) {
              if(strpos($g,'i')!==false || strpos($g,'h')!==false){
                $g_inp = explode('-',$g);
                $group[] = [$g,$this->db->get_where('input_group',['id'=>$g_inp[1]])->row()->name];
              }
              else
                $group[] = [$g,$this->db->get_where('lookup_group',['id'=>$g])->row()->name];
            }
          } else $group = [];
        } else $group = [];

        $allCombine[] = [
          'gen_id' => $value->gen_id,
          'act_id' => $value->act_id,
          'fltr_id' => $value->fltr_id,
          'val_type' => $value->val_type,
          'key' => $value->value,
          'rp_id' => (isset($rp->id))?$rp->id:'value not found',
          'rp_act_id' => (isset($rp->act_id))?$rp->act_id:'value not found',
          'rp_group' => $group
        ];
        unset($group);
      }

  		$metadata = [
  			'alert' => (isset($msg))?$msg:null,
  			'pagination' => $this->pagination->create_links(),
  			'allValue' => $allValue,
  			'allPayload' => $allPayload,
  			'allCombine' => $allCombine
  		];
  		$this->load->view('a_header');
  		$this->load->view('rm_module/add_path',$metadata);
  		$this->load->view('b_footer');
    }
    //######################################################################################################################
    //######################################################################################################################
      public function url(){
        if(isset($_POST['update'])){
    			$gen_id = $this->input->post('id');
          $act_id = $this->input->post('act_id');
          $fltr_id = $this->input->post('fltr');
          $value = $this->input->post('value');
          $data = [
            'act_id' => $act_id,
            'fltr_id' => $fltr_id,
            'value' => $value
    			];
    			$this->db->where('gen_id',$gen_id);
    			$this->db->update('url_val', $data);
    			$msg = ['success','success updated group id: '.$gen_id];
    		}
    		elseif(isset($_POST['new'])){
    			$act_id = $this->input->post('act_id');
          $fltr_id = $this->input->post('fltr');
          $value = $this->input->post('value');
          $data = [
            'act_id' => $act_id,
            'fltr_id' => $fltr_id,
            'value' => $value
    			];
          $this->db->insert('url_val',$data);
          $msg = ['success','success added menu group'];
    		}
    		elseif(isset($_POST['remove'])){
    			$id = $this->input->post('id');
    			$this->db->where('gen_id',$id);
    			$this->db->delete('url_val');
    		}
    		//pagination
    		$config['base_url'] 		= base_url('RM_Main/url');
    		$config['total_rows'] 		= $this->Logging->count_data('url_val');
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

    		$allValue = $this->Logging->get($config['per_page'], $page, 'url_val', FALSE);
    		$allPayload = $this->db->get('req_curl')->result();

        $metadata = [
    			'alert' => (isset($msg))?$msg:null,
    			'pagination' => $this->pagination->create_links(),
    			'allValue' => $allValue,
    			'allPayload' => $allPayload
    		];
    		$this->load->view('a_header');
    		$this->load->view('rm_module/url',$metadata);
    		$this->load->view('b_footer');
      }
}
