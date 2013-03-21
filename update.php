<?php

/* security : block direct access */
if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}


/* pre_set_site_transient_update_plugins */
function mwtags_pre_set_site_transient_update_plugins($transient) {  
    
	// plugin options
	global $mwtags_st;
	if (!is_object($mwtags_st)) $mwtags_st = mwtags_rt_settings();
	
	// check if API is about this plugin	
	if (empty($transient->checked) || $mwtags_st->settings['domain'] === null) return $transient;  
  
  
	// request API
    $args = array(
		'body' => array('plugin' => get_plugin_data(MWTAGS_PLUGIN_PATH.'/'.MWTAGS_PLUGIN_FILE), 'domain' => urlencode($mwtags_st->settings['domain']), 'url' => urlencode(MWTAGS_SITE_URL)), 'timeout' => 10
	);
    	
	$response = mwtags_api_request($args, 'check-latest-version'); 
	if (isset($response->error) || !isset($response)) return $transient;
	
	$response = $response->plugin;
	
    $response->new_version = $response->version;
    $response->url = $response->homepage;  
    $response->package = $response->download_link;

        
    // check new version, modify the transient  
    if( version_compare( $response->new_version, $transient->checked[MWTAGS_BASENAME], '>' ) ) : 
        $mwtags_st->api['last_version'] = $response->new_version; 
		update_option('mwtags_api', serialize($mwtags_st->api));
		$transient->response[MWTAGS_BASENAME] = $response;  
	else:
		if ($mwtags_st->api['last_version'] < $response->new_version) :
			$mwtags_st->api['last_version'] = $response->new_version; 
			update_option('mwtags_api', serialize($mwtags_st->api));
		endif;
	endif;
	
    return $transient; 
	
}  
add_filter( 'pre_set_site_transient_update_plugins', 'mwtags_pre_set_site_transient_update_plugins' ); 


if ($mwtags_st->settings['domain'] === null || strpos(MWTAGS_SITE_URL, $mwtags_st->settings['domain']) === false) :	
	
	/* last check */
	if ($mwtags_st->settings['domain'] !== null && $mwtags_st->settings['domain'] === false && $mwtags_st->settings['domain_check'] + 1800 > time()) return;
	
	
	$response = mwtags_api_request(array('body' => array('plugin' => get_plugin_data(MWTAGS_PLUGIN_PATH.'/'.MWTAGS_PLUGIN_FILE), 'url' => urlencode(MWTAGS_SITE_URL)), 'timeout' => 10), 'get-domain');
	
	if ($response):
		if ($response->code == 200) :
			$mwtags_st->settings['domain'] = $response->domain; $mwtags_st->settings['domain_check'] = null;
		else :
			$mwtags_st->settings['domain'] = false; $mwtags_st->settings['domain_check'] = time();
		endif;

		update_option('mwtags_settings', serialize($mwtags_st->settings));
	endif;
endif;

?>