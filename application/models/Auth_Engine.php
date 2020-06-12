<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Engine extends CI_Model {
  public function __construct()
	{
    parent::__construct();

  }
  public function login($usn, $psw)
	{
		if($this->db->get_where('account', array('username'=>$usn))->num_rows() != 0){
			$this->db->where('username', $usn);
			$this->db->select('password');
			if($this->db->get('account')->row()->password == $psw){
          $l = 1;
          $data = $this->db->get_where('account', array('username'=>$usn))->row();
      }
			else{
				  $l = 2;
          $data = "";
      }
		}
		else{
			$l = 3;
      $data = "";
    }
    $metadata = array (
      'l' => $l,
      'data' => $data
    );
    return $metadata;
	}
  public function permission($usr,$act){
    $this->db->where('username',$usr);
    $allowed = explode('|',$this->db->get('account')->row()->allowed);
    if(in_array($act,$allowed)){
      return 1;
    }
    else return 0;
  }
}
