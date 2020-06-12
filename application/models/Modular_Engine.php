<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modular_Engine extends CI_Model {

	public function component($act)
	{
    $this->db->where('id',$act);
    $rc = $this->db->get('req_curl')->row();
    $this->db->where('id',$act);
    if($this->db->get('req_curl')->num_rows()>=1){
      $lgval = explode(',',$rc->lookup_group);
      if($lgval!==['']){
        foreach ($lgval as $lg) {
          if(strpos($lg,'i')!==false){
            $inp_id = explode('-',$lg)[1];
            $this->db->where('id',$inp_id);
            $inp_fin = $this->db->get('input_group')->row();
            $rp[]=[
              'exst' => 1,
              'title' => $inp_fin->title,
              'dsply_tp' => 'inp',
              'member' => '',
              'plc_hldr' => $inp_fin->plc_hldr,
              'name'=>$inp_fin->name,
              'dsc'=>$inp_fin->dsc,
              'value'=>$inp_fin->value
            ];
          }
          else if(strpos($lg,'h')!==false) {
            $inp_id = explode('-',$lg)[1];
            $this->db->where('id',$inp_id);
            $inp_fin = $this->db->get('input_group')->row();
            $rp[]=[
              'exst' => 1,
              'title' => $inp_fin->title,
              'dsply_tp' => 'hdninp',
              'member' => '',
              'plc_hldr' => $inp_fin->plc_hldr,
              'name'=>$inp_fin->name,
              'dsc'=>$inp_fin->dsc,
              'value'=>$inp_fin->value
            ];
          }
          else{
            $this->db->where('id',$lg);
            $lgfin = $this->db->get('lookup_group')->row();
            $lm = explode(',',$lgfin->member);
            foreach ($lm as $value) {
              $this->db->where('id',$value);
              $lmval = $this->db->get('lookup_member')->row();
              $lmfin[] =[
                'name' => $lmval->name,
                'value' => $lmval->value,
								'dsc' => $lmval->dsc,
              ];
            }
            $rp[]=[
              'exst' => 1,
              'title' => $lgfin->title,
              'name' => $lgfin->name,
              'dsply_tp' => $lgfin->dsply_tp,
              'member' => $lmfin,
              'plc_hldr' => '',
              'dsc'=>'',
              'value'=>''
            ];
            unset($lmfin);
          }
        }
      }
      else{
        $rp[]=[
          'exst' => 0,
          'title' => '',
          'name' => '',
          'dsply_tp' => '',
          'member' => '',
          'plc_hldr' => '',
          'dsc'=>''
        ];
      }
    }
    else{
      $rp[]=[
        'exst' => 0,
        'title' => '',
        'name' => '',
        'dsply_tp' => '',
        'member' => '',
        'plc_hldr' => '',
        'dsc'=>''
      ];
    }
    return $rp;
	}
  public function conf($act){
    $this->db->where('id',$act);
    $conf = $this->db->get('req_curl')->row();
    $this->db->where('id',$act);
    if($this->db->get('req_curl')->num_rows()>=1){
      $rp_conf=[
        'act_id' => $conf->act_id,
        'param' => $conf->param,
        'header' => $conf->header,
        'url_add_path' => $conf->url_add_path,
        'title' => $conf->title,
        'id' => $conf->id
      ];
    }
    else{
      $rp_conf=[
        'act_id' => '',
        'param' => '',
        'header' => '',
        'url_add_path' => '',
        'title' => 'Payload Template Not Found',
        'id' => ''
      ];
    }

    return $rp_conf;
  }

}
