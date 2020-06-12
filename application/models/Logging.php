<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logging extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function log_gen($p_actid)
	{
		$this->db->where('act_id', $p_actid);
		$h = $this->db->get('postlog_val')->result();
		if($h!=[]){
			foreach($h as $row){
				$postlog[$row->key] = (isset($_POST[$row->value]))?$_POST[$row->value]:'';
			}
			return $postlog;
		}
		else return '';
	}
	public function get($limit, $start, $log_name, $filter, $field='', $val='')
	{
		$this->db->limit($limit, $start);
		if($filter == TRUE){
		  $this->db->like($field, $val);
		  $log_output = $this->db->get($log_name)->result();
		}
		else
		{
		  $log_output = $this->db->get($log_name)->result();
		}
		return $log_output;
	}
	public function post($log_name, $val)
	{
		$this->db->insert($log_name, $val);
	}
	public function count_data($log_name)
	{
		return $this->db->get($log_name)->num_rows();
	}
	public function count_group($id,$tbl)
	{
		$this->db->where('id',$id);
		return $this->db->get($tbl)->num_rows();
	}
}
