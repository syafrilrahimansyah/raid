<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('raid_list'))
{
    function raid_list()
    {
        $CI =& get_instance();
        $CI->load->database();
        $dir = $CI->db->get('menu_group')->result();
        if($dir!=[]){
          foreach ($dir as $value) {
            $mmbr = explode(',',$value->member);
            foreach ($mmbr as $lst_idx) {
              $CI->db->where('id',$lst_idx);
              $lst = $CI->db->get('menu_member')->row();
              if($lst!=[]){
                $mn_mmbr[] = [
                  'url'=>$lst->url,
                  'title'=>$lst->title
                ];
              }
              else{
                $mn_mmbr[] = [
                  'url'=>'',
                  'title'=>''
                ];
              }

            }
            $mn[] = [
              'title' => $value->title,
              'icon' => $value->icon,
              'member' => $mn_mmbr
            ];
            unset($mn_mmbr);
          }
          return $mn;
        }
        else return [];
    }
}
