<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UM_Main extends CI_Controller {

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
  public function usr_mgmt(){

		if(isset($_POST['update'])){
			$username_id = $this->input->post('username_id');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$dsply_name = $this->input->post('dsply_name');
			$role = $this->input->post('role');
			$allowed_raw = ($this->input->post('allowed')!=null) ? $this->input->post('allowed') : [''];
			$allowed = implode('|',$allowed_raw);
			$scr_key = $this->input->post('scr_key');

			$data = [
				'username' => $username,
				'password' => $password,
				'dsply_name' => $dsply_name,
				'role' => $role,
				'allowed' => $allowed,
				'scr_key' => $scr_key
			];

			if($username==$username_id){
				$this->db->set($data);
				$this->db->where('username',$username_id);
				$this->db->update('account');
				$msg = ['success','update success'];
			}
			else{
			$this->db->where('username',$username);
			$match = $this->db->get('account')->num_rows();
				if($match>0){
					$msg = ['warning','username : '.$username.' already exist!'];
				}
				else{
					$this->db->set($data);
					$this->db->where('username',$username_id);
					$this->db->update('account');
					$msg = ['success','update success'];
				}
			}
		}
		if(isset($_POST['submit'])){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$dsply_name = $this->input->post('dsply_name');
			$role = $this->input->post('role');
			$allowed_raw = ($this->input->post('allowed')!=null) ? $this->input->post('allowed') : [''];
			$allowed = implode('|',$allowed_raw);
			$scr_key = $this->input->post('scr_key');

			$data = [
				'username' => $username,
				'password' => $password,
				'dsply_name' => $dsply_name,
				'role' => $role,
				'allowed' => $allowed,
				'scr_key' => $scr_key
			];
			$this->db->where('username',$username);
			$match = $this->db->get('account')->num_rows();
			if($match>0){
				$msg = ['warning','username : '.$username.' already exist!'];
			}
			else{
				$this->db->insert('account',$data);
				$msg = ['success','user : '.$username.' added'];
			}
		}
		if(isset($_POST['remove'])){
			$username_id = $this->input->post('username_id');
			$this->db->where('username', $username_id);
			$this->db->delete('account');
			$msg = ['success','user : '.$username_id.' removed'];
		}
		//pagination
		$config['base_url'] 		= base_url('UM_Main/usr_mgmt');
		$config['total_rows'] 		= $this->Logging->count_data('account');
		$config['per_page'] 		= 10;
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

		$allUser = $this->Logging->get($config['per_page'], $page, 'account', FALSE);
		$allAct = $this->db->get('id_def')->result();
		$alert = (isset($msg))?$msg:null;
		$metadata=[
			'allUser'=>$allUser,
			'allAct' =>$allAct,
			'pagination' => $this->pagination->create_links(),
			'alert' =>$alert
		];
		$this->load->view('a_header');
		$this->load->view('um_module/user_mgmt',$metadata);
		$this->load->view('b_footer');

		//user denied

	}
}
