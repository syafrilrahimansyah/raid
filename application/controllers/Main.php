<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct()
	{
    parent::__construct();
		if($this->session->userdata('login')!=TRUE)
			redirect(base_url('login'));
		$this->load->model('Rest_Engine');
		$this->load->model('Form_Orchestrator');
		$this->load->model('Logging');
		$this->load->model('Auth_Engine');
		$this->load->model('Modular_Engine');
		$this->load->model('Validation');
		date_default_timezone_set('Asia/Jakarta');
		$this->session->set_userdata(array('filter'=>FALSE, 'field'=>'', 'val'=>''));
	}
	//RAID NEW BACK-END v 1.1.0
	public function c_flex_get($act = '')
	{
		//allowed user session_is_registered
		$usr 	= $this->session->userdata('username');
		$role	= $this->session->userdata('role');
		//user allowed
		if($this->Auth_Engine->permission($usr,$act) || $role == 'sysadm')
		{
			$rp = $this->Modular_Engine->component($act);
			$rp_conf = $this->Modular_Engine->conf($act);

			//submited
			if(isset($_POST['submit']))
			{
				//validate

				$pl_fin = $this->Validation->payload_lookup($_POST['act_id']);
				foreach ($pl_fin as $lg_fin) {
					if(isset($_POST[$lg_fin[0]])){
						if(!in_array($_POST[$lg_fin[0]],$lg_fin[1])) {
							$error[] = 'value ('.$_POST[$lg_fin[0]].') is not registered or associated with lookup group ('.$lg_fin[0].') . please assign this value using RAID Workbench.';
						}
					}else{
						$error[] = 'lookup group ('.$lg_fin[0].') cannot be empty';
					}
				}
				if(isset($error)){
					$message = '<b>error : Element injection detected</b> <br>'.implode('<br>',$error);
				}else{$message ='';}
				$valid = $message;
				//valid
				if($valid==''){
					if($_POST['param']=='1'){
						$postval = $this->Form_Orchestrator->orc_param($_POST['act_id']);
					}
					else{
						$postval = array();
					}
					//get filter id
					//$this->db->where('act_id', $_POST['act_id']);

					$fltr = (isset($this->db->get_where('fltr',['act_id'=> $_POST['act_id']])->row()->value))?$this->db->get_where('fltr',['act_id'=> $_POST['act_id']])->row()->value:'';
					//get header value ($header)
					if($_POST['header']=='1' && isset($_POST[$fltr])){
						$header = $this->Form_Orchestrator->orc_header($_POST['act_id'],(isset($_POST[$fltr]))?$_POST[$fltr]:'' );

					}
					else{
						$header = array();
					}
					
					// X SIGNATURE
					if($_POST[$fltr]=='prod'){
						$sig = md5('mdcshbkqcbkn3wtqjnvcrrwu'.'r4IDt53L95'.gmdate('U'));
					}else{
						$sig = md5('qw6avppx5pk9zpcn8d65skhf'.'3pCr41DOk3'.gmdate('U'));
					}
					$header[] = 'x-signature: '.$sig;
					
					//get url value ($url->value)
					$url_data = [
						'act_id'=>$_POST['act_id'],
						'fltr_id'=>(isset($_POST[$fltr])?$_POST[$fltr]:'')
					];
					$url = $this->db->get_where('url_val',$url_data)->row();

					//get url add path ($path)
					if($_POST['url_add_path']=='1'){
						$path = $this->Form_Orchestrator->url_add_path($_POST['act_id']);
					}
					else
						$path = '';
					//---------------------REST START------------------------
					$server_output = $this->Rest_Engine->rest_get($postval, $header, (isset($url->value))?$url->value.$path:'#');

					//HIT REST ENGINE (dev)
					/*
					$server_output = array(
						'server_output' => '{out : test}',
						'httpcode' => 200
					);
					*/
					//set HTTP status display
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
				//invalid
				else{
					//display data
					$metadata = array(
						'x' => 1,
						'curl' => $message,
						'stat' => 'fail',
						'raw_curl' => $message,
						'test_out' => 'raid-sec-fault',
						'rp' => $rp,
						'rp_conf' => $rp_conf
					);
				}
				//-----------------VALUE PROCESS--------------------------
				//get param value ($postval)
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
			$this->load->view('a_header');
			$this->load->view('module/rest_get',$metadata);
			$this->load->view('b_footer');
		}
		//user denied
		else
		{
			$this->load->view('a_header');
			$this->load->view('user_denied');
			$this->load->view('b_footer');
		}
	}
	public function c_flex_put($act ='')
	{
		//allowed user session_is_registered

		$usr 	= $this->session->userdata('username');
		$role	= $this->session->userdata('role');
		//user allowed
		if($this->Auth_Engine->permission($usr,$act) || $role == 'sysadm')
		{
			$rp = $this->Modular_Engine->component($act);
			$rp_conf = $this->Modular_Engine->conf($act);
			//submited
			if(isset($_POST['submit']))
			{
				//validate

				$pl_fin = $this->Validation->payload_lookup($_POST['act_id']);
				foreach ($pl_fin as $lg_fin) {
					if(isset($_POST[$lg_fin[0]])){
						if(!in_array($_POST[$lg_fin[0]],$lg_fin[1])) {
							$error[] = 'value ('.$_POST[$lg_fin[0]].') is not registered or associated with lookup group ('.$lg_fin[0].') . please assign this value using RAID Workbench.';
						}
					}else{
						$error[] = 'lookup group ('.$lg_fin[0].') cannot be empty';
					}
				}
				if(isset($error)){
					$message = '<b>error : Element injection detected</b> <br>'.implode('<br>',$error);
				}else{$message ='';}
				$valid = $message;
				//valid
				if($valid==''){
				//-----------------VALUE PROCESS--------------------------
				//get data value ($postval)
				$postval = $this->Form_Orchestrator->orc_data($_POST['act_id']);
				//get filter id
				$this->db->where('act_id', $_POST['act_id']);
				$fltr = $this->db->get('fltr')->row()->value;
				//get header value ($header)
				if(isset($_POST['header']) && isset($_POST[$fltr])){
					$header = $this->Form_Orchestrator->orc_header($_POST['act_id'],$_POST[$fltr]);
				}
				else{
					$header = array();
				}
				// X SIGNATURE
				if($_POST[$fltr]=='prod'){
					$sig = md5('mdcshbkqcbkn3wtqjnvcrrwu'.'r4IDt53L95'.gmdate('U'));
				}else{
					$sig = md5('qw6avppx5pk9zpcn8d65skhf'.'3pCr41DOk3'.gmdate('U'));
				}
				$header[] = 'x-signature: '.$sig;
				//get url value ($url->value)
				$url_data = [
					'act_id'=>$_POST['act_id'],
					'fltr_id'=>(isset($_POST[$fltr])?$_POST[$fltr]:'')
				];
				$url = $this->db->get_where('url_val',$url_data)->row();
				//get url add path ($path)
				if(isset($_POST['url_add_path'])){
					$path = $this->Form_Orchestrator->url_add_path($_POST['act_id']);
				}
				else
					$path = '';
				//---------------------REST START------------------------
				$server_output = $this->Rest_Engine->rest_put($postval, $header, (isset($url->value))?$url->value.$path:'#');
				//get postlog values ($postlog)
				//set HTTP status display
				if($server_output['httpcode'] == 200)
					$stat = 'success';
				else
					$stat = 'fail';
				//---------------------LOGGING-----------------------------
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
				//display response module when submit
				$this->Logging->post('curl_log',$log_val);
				//fetch data to display on response module
				$metadata = array(
					'x' => 1,
					'stub' => $stat,
					'raw_curl' => $server_output['server_output'],
					'test_out' => $server_output['httpcode'],
					//'test_outin' => $info,
					'curl' => $server_output,
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//invalid
			else{
				//display data
				$metadata = array(
					'x' => 1,
					'curl' => $message,
					'stub' => 'fail',
					'raw_curl' => $message,
					'test_out' => 'raid-sec-fault',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			}
			else
			{
				$metadata = array(
					'x' => 0,
					'stat' => '',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//load front-end
			$this->load->view('a_header');
			$this->load->view('module/rest_put',$metadata);
			$this->load->view('b_footer');
		}
		//user denied
		else
		{
			$this->load->view('a_header');
			$this->load->view('user_denied');
			$this->load->view('b_footer');
		}
	}
	public function c_flex_post($act ='')
	{
		//allowed user session_is_registered

		$usr 	= $this->session->userdata('username');
		$role	= $this->session->userdata('role');
		//user allowed
		if($this->Auth_Engine->permission($usr,$act) || $role == 'sysadm')
		{
			$rp = $this->Modular_Engine->component($act);
			$rp_conf = $this->Modular_Engine->conf($act);
			//submited
			if(isset($_POST['submit']))
			{
				//validate
				$pl_fin = $this->Validation->payload_lookup($_POST['act_id']);
				foreach ($pl_fin as $lg_fin) {
					if(isset($_POST[$lg_fin[0]])){
						if(!in_array($_POST[$lg_fin[0]],$lg_fin[1])) {
							$error[] = 'value ('.$_POST[$lg_fin[0]].') is not registered or associated with lookup group ('.$lg_fin[0].') . please assign this value using RAID Workbench.';
						}
					}else{
						$error[] = 'lookup group ('.$lg_fin[0].') cannot be empty';
					}
				}
				if(isset($error)){
					$message = '<b>error : Element injection detected</b> <br>'.implode('<br>',$error);
				}else{$message ='';}
				$valid = $message;
				//valid
				if($valid==''){
				//-----------------VALUE PROCESS--------------------------
				//get data value ($postval)
				$postval = $this->Form_Orchestrator->orc_data($_POST['act_id']);
				//get filter id
				$this->db->where('act_id', $_POST['act_id']);
				$fltr = $this->db->get('fltr')->row()->value;
				//get header value ($header)
				if(isset($_POST['header']) && isset($_POST[$fltr])){
					$header = $this->Form_Orchestrator->orc_header($_POST['act_id'],$_POST[$fltr]);
				}
				else{
					$header = array();
				}
				// X SIGNATURE
				if($_POST[$fltr]=='prod'){
					$sig = md5('mdcshbkqcbkn3wtqjnvcrrwu'.'r4IDt53L95'.gmdate('U'));
				}else{
					$sig = md5('qw6avppx5pk9zpcn8d65skhf'.'3pCr41DOk3'.gmdate('U'));
				}
				$header[] = 'x-signature: '.$sig;
				//get url value ($url->value)
				$url_data = [
					'act_id'=>$_POST['act_id'],
					'fltr_id'=>(isset($_POST[$fltr])?$_POST[$fltr]:'')
				];
				$url = $this->db->get_where('url_val',$url_data)->row();
				//get url add path ($path)
				if(isset($_POST['url_add_path'])){
					$path = $this->Form_Orchestrator->url_add_path($_POST['act_id']);
				}
				else
					$path = '';
				//---------------------REST START------------------------
				$server_output = $this->Rest_Engine->rest_post($postval, $header, (isset($url->value))?$url->value.$path:'#');
				//get postlog values ($postlog)
				//set HTTP status display
				if($server_output['httpcode'] == 200)
					$stat = 'success';
				else
					$stat = 'fail';
				//---------------------LOGGING-----------------------------
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
				//display response module when submit
				$this->Logging->post('curl_log',$log_val);
				//fetch data to display on response module
				$metadata = array(
					'x' => 1,
					'stub' => $stat,
					'raw_curl' => $server_output['server_output'],
					'test_out' => $server_output['httpcode'],
					//'test_outin' => $info,
					'curl' => $server_output,
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//invalid
			else{
				//display data
				$metadata = array(
					'x' => 1,
					'curl' => $message,
					'stub' => 'fail',
					'raw_curl' => $message,
					'test_out' => 'raid-sec-fault',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			}
			else
			{
				$metadata = array(
					'x' => 0,
					'stat' => '',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//load front-end
			$this->load->view('a_header');
			$this->load->view('module/rest_post',$metadata);
			$this->load->view('b_footer');
		}
		//user denied
		else
		{
			$this->load->view('a_header');
			$this->load->view('user_denied');
			$this->load->view('b_footer');
		}
	}
	public function c_flex_del($act='')
	{
		//allowed user session_is_registered
		$usr 	= $this->session->userdata('username');
		$role	= $this->session->userdata('role');
		//user allowed
		if($this->Auth_Engine->permission($usr,$act) || $role == 'sysadm')
		{
			$rp = $this->Modular_Engine->component($act);
			$rp_conf = $this->Modular_Engine->conf($act);
			//submited
			if(isset($_POST['submit']))
			{
				//validate
				$pl_fin = $this->Validation->payload_lookup($_POST['act_id']);
				foreach ($pl_fin as $lg_fin) {
					if(isset($_POST[$lg_fin[0]])){
						if(!in_array($_POST[$lg_fin[0]],$lg_fin[1])) {
							$error[] = 'value ('.$_POST[$lg_fin[0]].') is not registered or associated with lookup group ('.$lg_fin[0].') . please assign this value using RAID Workbench.';
						}
					}else{
						$error[] = 'lookup group ('.$lg_fin[0].') cannot be empty';
					}
				}
				if(isset($error)){
					$message = '<b>error : Element injection detected</b> <br>'.implode('<br>',$error);
				}else{$message ='';}
				$valid = $message;
				//valid
				if($valid==''){
				//-----------------VALUE PROCESS--------------------------
				//get param value ($postval)
				if(isset($_POST['param'])){
					$postval = $this->Form_Orchestrator->orc_param($_POST['act_id']);
				}
				else{
					$postval = array();
				}
				//get filter id
				$this->db->where('act_id', $_POST['act_id']);
				$fltr = $this->db->get('fltr')->row()->value;
				//get header value ($header)
				if(isset($_POST['header']) && isset($_POST[$fltr])){
					$header = $this->Form_Orchestrator->orc_header($_POST['act_id'],$_POST[$fltr]);
				}
				else{
					$header = array();
				}
				// X SIGNATURE
				if($_POST[$fltr]=='prod'){
					$sig = md5('mdcshbkqcbkn3wtqjnvcrrwu'.'r4IDt53L95'.gmdate('U'));
				}else{
					$sig = md5('qw6avppx5pk9zpcn8d65skhf'.'3pCr41DOk3'.gmdate('U'));
				}
				$header[] = 'x-signature: '.$sig;
				//get url value ($url->value)
				$url_data = [
					'act_id'=>$_POST['act_id'],
					'fltr_id'=>(isset($_POST[$fltr])?$_POST[$fltr]:'')
				];
				$url = $this->db->get_where('url_val',$url_data)->row();
				//get url add path ($path)
				if(isset($_POST['url_add_path'])){
					$path = $this->Form_Orchestrator->url_add_path($_POST['act_id']);
				}
				else
					$path = '';
				//---------------------REST START------------------------
				$server_output = $this->Rest_Engine->rest_delete($postval, $header, (isset($url->value))?$url->value.$path:'#');
				//HIT REST ENGINE (dev)
				/*
				$server_output = array(
					'server_output' => '{out : test}',
					'httpcode' => 200
				);
				*/
				//set HTTP status display
				if($server_output['httpcode'] == 200)
					$stat = 'success';
				else
					$stat = 'fail';
				//---------------------LOGGING-----------------------------
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
				//display response module when submit
				$this->Logging->post('curl_log',$log_val);
				//fetch data to display on response module
				$metadata = array(
					'x' => 1,
					'stub' => $stat,
					'raw_curl' => $server_output['server_output'],
					'test_out' => $server_output['httpcode'],
					'curl' => $server_output,
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//invalid
			else{
				//display data
				$metadata = array(
					'x' => 1,
					'curl' => $message,
					'stub' => 'fail',
					'raw_curl' => $message,
					'test_out' => 'raid-sec-fault',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			}
			else
			{
				$metadata = array(
					'x' => 0,
					'stat' => '',
					'rp' => $rp,
					'rp_conf' => $rp_conf
				);
			}
			//load front-end
			$this->load->view('a_header');
			$this->load->view('module/rest_del',$metadata);
			$this->load->view('b_footer');
		}
		//user denied
		else
		{
			$this->load->view('a_header');
			$this->load->view('user_denied');
			$this->load->view('b_footer');
		}
	}
	//OLD RAID BACKEND
	public function c_ordersub()
	{
		//get inputed data
		$this->load->view('a_header');
		$this->load->view('module/curl_ordersub');
		$this->load->view('b_footer');
	}
	public function c_eligibleoffer()
	{
		//get inputed data
		$this->load->view('a_header');
		$this->load->view('module/curl_eligibleoffer');
		$this->load->view('b_footer');
	}
	public function c_ordershistory()
	{
		//unavailable
		$this->load->view('a_header');
		$this->load->view('module/curl_orderhistory');
		$this->load->view('b_footer');
	}
	public function about()
	{
		$this->load->view('a_header');
		$this->load->view('about');
		$this->load->view('b_footer');
	}
	public function restricted()
	{
		$this->load->view('a_header');
		$this->load->view('user_denied');
		$this->load->view('b_footer');
	}
}
