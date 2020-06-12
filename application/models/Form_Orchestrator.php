<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_Orchestrator extends CI_Model {

	public function orc_data($actid)
	{
		$this->db->where('act_id',$actid);
		$a = $this->db->get('data_form')->result();
		if($a!=''){
			foreach($a as $row){
				$b[]=$row->value;
			}
			foreach($a as $row){
				$replace[]=$row->key;
			}
			$this->db->where('act_id',$actid);
			$string = $this->db->get('data_tmplt')->row()->value;
			$n = 0;
			foreach($b as $row){
				$info[$replace[$n]] = $_POST[$row];
				$n += 1;
			}
			return str_replace($replace,$info,$string);
		}
		else return '';
	}
	public function orc_param($p_actid)
	{
		$this->db->where(['act_id'=> $p_actid,'part_id'=> 3]);
		$a = $this->db->get('param_form')->result();
		if($a!=[]){
			foreach($a as $row){
				if(isset($_POST[$row->param]))
					$postval[$row->param] = $_POST[$row->param];
				else
					$postval[$row->param] = '';
			}
			return $postval;
		}
		else return '';

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
				if(isset($_POST[$row->param])){
					$header[] = $_POST[$row->param];
				}
			}
		}
		return (isset($header))?$header:'';
	}
	public function url_add_path($p_actid)
	{
		$this->db->where('act_id', $_POST['act_id']);
		$f = $this->db->get('url_add_path')->result();
		if($f!=[]){
			foreach($f as $row){
				$g[] = '/';
				$g[] = $_POST[$row->value];
			}
			$path = implode('',$g);
			return $path;
		}
		else return '';
	}

}
