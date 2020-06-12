<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
  public function __construct()
	{
    parent::__construct();
    $this->load->model('Auth_Engine');
    $this->session->set_userdata(array('filter'=>FALSE, 'field'=>'', 'val'=>''));
  }
  public function login()
	{
		$usn = $this->input->post('username');
		$psw = $this->input->post('password');
		$metadata = array(
			'err' => 1
		);
		if(isset($_POST['submit']))
		{
			$data = $this->Auth_Engine->login($usn, $psw);
			if($data['l'] == 1)
			{
			  $datax = array(
				  'login' => TRUE,
				  'name' => $data['data']->dsply_name,
				  'role' => $data['data']->role,
				  'sk' => $data['data']->scr_key,
				  'username' => $data['data']->username
			  );
			  $this->session->set_userdata($datax);
			  redirect(base_url());
			}
			else
			{
				$metadata = array(
					'err' => 1
				);
				$this->load->view('login', $metadata);
			}
		}
		else
		{
			$metadata = array(
				'err' => 0
			);
			$this->load->view('login', $metadata);
		}
		
			

	}
  public function logout()
  {
    if($this->session->userdata('login')==TRUE)
    {
      $this->session->sess_destroy('userdata');
      redirect(base_url('login'));
    }
    else
      redirect(base_url());
  }
}
