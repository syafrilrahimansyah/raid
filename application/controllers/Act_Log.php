<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Act_Log extends CI_Controller {
	//adjust ubuntu
	public function __construct()
	{
    parent::__construct();
		if($this->session->userdata('login')!=TRUE)
			redirect(base_url('login'));
		$this->load->model('Logging');
		date_default_timezone_set('Asia/Jakarta');
  }
	public function index()
	{
		if(isset($_GET['search']) && $this->input->get('search_in')!='')
		{
      $field = $this->input->get('search_col');
      $val = $this->input->get('search_in');
			$sess_filter = array(
				'filter' => TRUE,
				'field' => $field,
				'val'	=> $val
			);
      $this->session->set_userdata($sess_filter);
    }
		elseif (isset($_GET['search']) && $this->input->get('search_in')=='')
		{
			$this->session->set_userdata('filter',FALSE);
		}
		//pagination
		$config['base_url'] 		= base_url().'Act_Log/index';
		$config['total_rows'] 		= $this->Logging->count_data('curl_log');
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
		//get data to display
		if($this->session->userdata('filter')==TRUE)
		{
				$log_data = $this->Logging->get($config['per_page'], $page, 'curl_log', TRUE, $this->session->userdata('field'), $this->session->userdata('val'));
		}
		else
		{
				$log_data = $this->Logging->get($config['per_page'], $page, 'curl_log', FALSE);
		}
		//fetch all data to pass
		$metadata = array(
			'log_data' => $log_data,
			'pagination' => $this->pagination->create_links(),
			'search_form' => 1,
		);
		//front-end load
		$this->load->view('a_header', $metadata);
		$this->load->view('module/activity_log', $metadata);
		$this->load->view('b_footer');
	}
}
