<?php

if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}

function join_post_mowsterTags(){

	// $_POST
	$text = trim(stripslashes(wp_filter_nohtml_kses($_POST['text'])));
	$count = $_POST['count'];
	$tags = stripslashes(wp_filter_nohtml_kses($_POST['tags']));
	
	// Update count option
	if (get_option('mowsterTags_count') != $count) update_option('mowsterTags_count', $count);
	
	
	// Get data	
	$content = trim($text);
	if (empty($content)) mowsterTags_results($tags);

			
	// Build params
	$param = 'appid=h4c6gyLV34Fs7nHCrHUew7XDAU8YeQ_PpZVrzgAGih2mU12F0cI.ezr6e7FMvskR7Vu.AA--'; // Yahoo ID
	$param .= '&context='.urlencode($content); // Post content
	if (!empty($tags)) {
		$param .= '&query='.urlencode($tags); // Existing tags
	}
	$param .= '&output=json'; 
			
			
	// Get PHP Array
	$response = wp_remote_post('http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction', array('body' =>$param) );

	if (!is_wp_error($response) && $response['response']['code'] == 200) $data = json_decode(maybe_unserialize($response['body']));
	else mowsterTags_results($tags);
	
	if (empty($data) || empty($data->ResultSet->Result) || is_wp_error($data)) mowsterTags_results($tags);

		
	// Unique terms
	$data = array_unique($data->ResultSet->Result);
	array_filter($data, 'mowsterTags_callback_limit');
		
	$check_tags = array_map('mowsterTags_callback_trim_lower', (explode(',', $tags)));

	
	// Display terms
	foreach ($data as $term) {		
		if (!in_array($term, $check_tags)) $display .= esc_html($term).",";
		$counter++;			
		if ($counter == $count) break;
	}
	
	if ($tags && substr($tags,-1) != ',') $tags .= ",";
	mowsterTags_results($tags.trim($display, ','));

}


function mowsterTags_results($tags){
	echo $tags;
	die();
}


// php not supporting anonymous functions
function mowsterTags_callback_limit($x) { 
	return (strlen($x) > 2); 
}

function mowsterTags_callback_trim_lower($x){
	return (trim(mb_strtolower($x, MOWSTERTAGS_CHARSET)));
}

?>
