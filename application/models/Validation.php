<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends CI_Model {

	public function payload_lookup($actid)
	{
		$lg = $this->db->get_where('req_curl',['act_id'=>$actid])->row();
    $lg_exp = explode(',',$lg->lookup_group);
    foreach ($lg_exp as $value) {
      if(strpos($value,'i')!==false){

      }elseif (strpos($value,'h')!==false) {
         
      }else{
        $lm = $this->db->get_where('lookup_group',['id'=>$value])->row();
        $lm_exp = explode(',',$lm->member);
        foreach ($lm_exp as $m_value) {
          $lm_fin[] = $this->db->get_where('lookup_member',['id'=>$m_value])->row()->value;
        }
        $lg_fin[] = [$this->db->get_where('lookup_group',['id'=>$value])->row()->name,$lm_fin];
        $lm_fin=[];
      }
    }
    return $lg_fin;
	}
}
