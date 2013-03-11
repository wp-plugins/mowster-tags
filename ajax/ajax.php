<?php

if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}

function join_post_mwtags(){


	/* settings */
	global $mwtags_st;
	if (!is_object($mwtags_st)) $mwtags_st = mwtags_rt_settings();	

	
	/* ajax $_REQUEST */
	$text = trim(stripslashes(wp_filter_nohtml_kses($_REQUEST['text'])));
	$count = $_REQUEST['count'];
	$tags = stripslashes(wp_filter_nohtml_kses($_REQUEST['tags']));
	
	
	/* limit option */
	if ($mwtags_st->settings['mwtags_count'] != $count):
		$mwtags_st->settings['mwtags_count'] = $count;
		update_option('mwtags_settings', serialize($mwtags_st->settings));
	endif;
		
		
	/* post content */
	$content = trim($text);
	if (empty($content)) mwtags_results($tags);
			
			
	/* build params */
	$param = 'appid=h4c6gyLV34Fs7nHCrHUew7XDAU8YeQ_PpZVrzgAGih2mU12F0cI.ezr6e7FMvskR7Vu.AA--'; /* yahoo id */
	$param .= '&context='.urlencode($content); /* post content */
	if (!empty($tags)):
		$param .= '&query='.urlencode($tags); /* existing tags */
	endif;
	$param .= '&output=json'; 
			
			
	/* post array */
	$response = wp_remote_post('http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction', array('body' =>$param) );

	if (!is_wp_error($response) && $response['response']['code'] == 200) $data = json_decode(maybe_unserialize($response['body']));
	else mwtags_results($tags);
	
	if (empty($data) || empty($data->ResultSet->Result) || is_wp_error($data)) mwtags_results($tags);

		
	/* unique terms */
	$data = array_unique($data->ResultSet->Result);
	array_filter($data, 'mwtags_callback_limit');
		
	$check_tags = array_map('mwtags_callback_trim_lower', (explode(',', $tags)));

	
	/* display terms */
	$display = null; $limit = null;
	foreach ($data as $term) :		
		if (!in_array($term, $check_tags)) $display .= esc_html($term).",";
		$limit++;	
		if ($limit == $count) break;
	endforeach;
	
	if ($tags && substr($tags,-1) != ',') $tags .= ",";
	mwtags_results($tags.trim($display, ','));

	
	die();
}
?>
