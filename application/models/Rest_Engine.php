<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_Engine extends CI_Model {
  public function __construct()
	{
    parent::__construct();

  }
  //new
  public function rest_delete($postval, $header, $url)
  {
	$params = '';
    foreach($postval as $key=>$value)
    {
      $params .= $key.'='.$value;
    }
    $params = trim($params, '&');
	$ch = curl_init($url.'?'.$params);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$server_output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$server_error = curl_error($ch);
    curl_close ($ch);
    $outx = array(
      'server_output' => $server_output,
      'httpcode' => $httpcode,
	  //'test_out_curl' => $server_error
    );

    return $outx;
  }
  public function rest_put($postval, $header, $url)
  {


	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postval);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$server_output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$server_error = curl_error($ch);
    curl_close ($ch);
    $outx = array(
      'server_output' => $server_output,
      'httpcode' => $httpcode,
	  //'test_out_curl' => $server_error
    );

    return $outx;

  }
  public function rest_post($postval, $header, $url)
  {


	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postval);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$server_output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$server_error = curl_error($ch);
    curl_close ($ch);
    $outx = array(
      'server_output' => $server_output,
      'httpcode' => $httpcode,
	  //'test_out_curl' => $server_error
    );

    return $outx;

  }

  public function rest_get($postval, $header, $url)
  {
    if($postval!=''){
      $params = '';
      foreach($postval as $key=>$value)
      {
        $params .= $key.'='.$value.'&';
      }
      $params = trim($params, '&');
    }
    else $params='';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url.'?'.$params );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $server_output = curl_exec($ch);
	  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	  $server_error = curl_error($ch);
    curl_close ($ch);
    $outx = array(
      'server_output' => $server_output,
      'httpcode' => $httpcode,
	  //'test_out_curl' => $server_error
    );

    return $outx;
  }

  public function transaction_id()
  {
    return $this->db->query('SELECT MAX(log_id) FROM curl_log')->row_array();
  }

}
