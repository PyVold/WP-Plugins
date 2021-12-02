<?php
/**
Plugin Name: Show IP info
Description: The plugin fetchs the IP address of the visitor and then return information such like: languages, currency, country (and more in future versions)
Version: 1.0.1
Author URI: https://www.pyvold.com
 */

 function show_visitor_ip() {
	  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $ip = $_SERVER['HTTP_CLIENT_IP'];
	  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
	   $ip = $_SERVER['REMOTE_ADDR'];
	  }
  return apply_filters('wpb_get_ip', $ip);
 }
 add_shortcode('show_ip', 'show_visitor_ip');
  
 function show_visitor_infoByIp($showvip){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    if(filter_var($client, FILTER_VALIDATE_IP)){		
       $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
       $ip = $forward;
    } else {
        $ip = $remote;
    }
	$ip_info = @json_decode( wp_remote_retrieve_body( wp_remote_get( "https://www.ipdevops.com/api/remip/".$ip ) ) );;
	
      $showvip_data = '';
      $showvip_ltype = $showvip['type'];
	  
    if($ip_info && $ip_info->country != null){
		
	 if($showvip_ltype == 'country'){
	   $showvip_data = $ip_info->country;
	 } elseif($showvip_ltype == 'languages'){
	   $showvip_data = $ip_info->languages;
	 } elseif($showvip_ltype == 'currency'){
	   $showvip_data = $ip_info->currency;
	 }

    }

    return $showvip_data;
 } 
 add_shortcode('showip_info', 'show_visitor_infoByIp');       
?>