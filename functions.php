<?php

/* security : block direct access */
if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}


/* php versions not supporting anonymous functions */
function mwtags_callback_limit($x) { 
	
	return (strlen($x) > 2); 
}


/* unique terms */
function mwtags_callback_trim_lower($x){
	
	return (trim(mb_strtolower($x, MWTAGS_CHARSET)));
}


/* return fetched tags */
function mwtags_results($fetched_tags, $status = null, $message = null){
	
	echo json_encode(array(
		'tags' => $fetched_tags,
		'status' => $status,
		'message' => $message
	));
	
	die();
}


/* settings */
function mwtags_rt_settings(){  

	$rt_settings = new stdClass;
	
	$rt_settings->settings = @unserialize(get_option('mwtags_settings'));
	$rt_settings->api = @unserialize(get_option('mwtags_api'));
	
	return $rt_settings;
}


/* svn API request */
function mwtags_api_request($args, $action) {  
      
    // send request
    $request = wp_remote_post('http://wordpress.mowster.net/'.$action, $args);
	
    if (is_wp_error($request) || 200 != wp_remote_retrieve_response_code($request)) return false;           
		  
    $response = @unserialize(wp_remote_retrieve_body($request));
	
    if (is_object($response)) return $response;  
    else return false;  
}


/* current URL */ 
function mwtags_curURL() {
	
	$pageURL = 'http';	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	
	return $pageURL;
}


/* upgrader_post_install */
function mwtags_upgrader_post_install($true, $hook_extra, $result){
	mwtags_options('activate');
	mwtags_options('deactivate');
	
	return $result;
}
add_filter('upgrader_post_install', 'mwtags_upgrader_post_install', 10, 3);
?>