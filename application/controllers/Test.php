<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public function __construct()
	{
    parent::__construct();
		$this->load->model('Rest_Engine');
		$this->load->model('Form_Orchestrator');
		$this->load->model('Logging');
		$this->load->model('Auth_Engine');
		$this->load->model('Modular_Engine');
		date_default_timezone_set('Asia/Jakarta');
		$this->session->set_userdata(array('filter'=>FALSE, 'field'=>'', 'val'=>''));
	}
	public function jtest()
	{
		$postval = array(
					'Msisdn'  => '6281247110488',
					'Profile' => array([
						'name'  => 'LOCATION',
						'value' => 'CONDET']
					)
				);
		echo json_encode($postval);
	}
  public function multiselect()
  {
    print_r($_POST);
    $this->load->view('a_header');
    $this->load->view('test');
    $this->load->view('b_footer');
  }
	public function test_param()
	{

			//$rp = $this->Modular_Engine->component('ov');
			//$rp_conf = $this->Modular_Engine->conf('ov');

			//submited
			if(isset($_POST['submit']))
			{
				//-----------------VALUE PROCESS--------------------------
				//get param value ($postval)
				if($_POST['param']=='1'){
					$postval = $this->Form_Orchestrator->orc_param($_POST['act_id']);
				}
				else{
					$postval = array();
				}
				//get filter id
				$this->db->where('act_id', $_POST['act_id']);
				$fltr = $this->db->get('fltr')->row()->value;
				//get header value ($header)
				if($_POST['header']=='1'){
					$header = $this->Form_Orchestrator->orc_header($_POST['act_id'],$_POST[$fltr]);
				}
				else{
					$header = array();
				}
				//get url value ($url->value)
				$this->db->where('act_id', $_POST['act_id']);
				$this->db->where('fltr_id', $_POST[$fltr]);
				$url = $this->db->get('url_val')->row();
				//get url add path ($path)
				if($_POST['url_add_path']=='1'){
					$path = $this->Form_Orchestrator->url_add_path($_POST['act_id']);
				}
				else
					$path = '';
				//---------------------REST START------------------------
				//$server_output = $this->Rest_Engine->rest_get($postval, $header, $url->value.$path);
				print_r($postval);
				print_r($header);
				print_r($url->value.$path);
				//HIT REST ENGINE (dev)
				/*
				$server_output = array(
					'server_output' => '{out : test}',
					'httpcode' => 200
				);
				*/
				//set HTTP status display
				/*
				if($server_output['httpcode'] == 200)
					$stat = 'success';
				else
					$stat = 'fail';
				//---------------------LOGGING-----------------------------
				//get postlog values ($postlog)
				$postlog = $this->Logging->log_gen($_POST['act_id']);
				//fetch input and output data to log
				$log_val = array(
					'date' => date("Y-m-d H:i:sa"),
					'act' => $this->db->get_where('id_def', array('act_id' => $_POST['act_id']))->row()->def,
					'username' => $this->session->userdata('username'),
					'req' => json_encode($postlog),
					'res' => $server_output['server_output'],
					'stat' => (string) $server_output['httpcode']
				);
				//record log
				$this->Logging->post('curl_log',$log_val);
				//----------------DISPLAY DATA--------------------------
				$metadata = array(
					'x' => 1,
					'curl' => $server_output['server_output'],
					'stat' => $stat,
					'raw_curl' => $server_output['server_output'],
					'test_out' => $server_output['httpcode'],
					'rp' => $rp,
					'rp_conf' => $rp_conf
					//'test_outin' => [$postlog]
				);
			}
			//unsubmited
			else
			{
				//display data
				$metadata = array(
					'x' => 0,
					'curl' => '',
					'stat' => '',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}

			//load front-end
			//$this->load->view('a_header');
			//$this->load->view('module/rest_get',$metadata);
			//$this->load->view('b_footer');
			*/
		}
	}
	public function orc_header($p_actid, $fltr)
	{
		$data = [
			'act_id'=> $p_actid,
			'fltr_id'=> $fltr
		];
		$this->db->order_by('seq_id', 'ASC');
		$e = $this->db->get_where('header_val',$data)->result();
		if($e!==[]){
			foreach($e as $row){
				$header[] = $row->value;
			}
		}
		//################
		$datax = [
			'act_id' => $p_actid,
			'part_id' => 2
		];
		$a = $this->db->get_where('param_form',$datax)->result();
		if($a!==[]){
			foreach($a as $row){
				if(isset($_POST[$row->param]))
					$header[] = $_POST[$row->param];
					echo $_POST[$row->param];
				}
		}
		print_r($a);
	}
}
