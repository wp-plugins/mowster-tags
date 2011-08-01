<?php

function join_post_mowsterTags(){

	// Save count option
	$title = strip_tags(stripslashes($_POST['title']));
	$text = strip_tags(stripslashes($_POST['text']));
	$count = strip_tags(stripslashes($_POST['count']));
	$tags = strip_tags(stripslashes($_POST['tags']));
	
	if (get_option('mowsterTags_count') != $count) update_option('mowsterTags_count', $count);

		
	// Get data
	$content = trim(stripslashes($text) .' '. stripslashes($title));

	if (empty($content)) {
		echo $tags;
		die();
	}

			
	// Build params
	$param = 'appid=h4c6gyLV34Fs7nHCrHUew7XDAU8YeQ_PpZVrzgAGih2mU12F0cI.ezr6e7FMvskR7Vu.AA--'; // Yahoo ID
	$param .= '&context='.urlencode($content); // Post content
	if (!empty($tags)) {
		$param .= '&query='.urlencode($tags); // Existing tags
	}
	$param .= '&output=json'; // Get PHP Array !
			
	$data = array();
	$reponse = wp_remote_post('http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction', array('body' =>$param) );
	if( !is_wp_error($reponse) && $reponse != null ) {
		if (wp_remote_retrieve_response_code($reponse) == 200) 
			$data = maybe_unserialize(wp_remote_retrieve_body($reponse));
	}
	$data = json_decode($data);
			
	if (empty($data) || empty($data->ResultSet->Result) || is_wp_error($data)) {
		echo $tags;
		die();
	}
		
		
	// Remove empty terms
	$data = array_unique($data->ResultSet->Result);
	$data = array_filter($data, 'mowsterTags_callback');

	$check_tags = explode(',', str_replace(' ', '', $tags));
	
	foreach ($data as $term) {		
		if (!in_array($term, $check_tags)) $display .= esc_html($term).',';
		$counter++;
		if ($counter == $count) break;
	}
	
	if ($tags && substr($tags,-1) != ',') $tags=$tags.",";

	echo $tags.substr($display,0,-1);
	die();
	
}

// fix: PHP versions not supporting anonymous functions
function mowsterTags_callback($x) { 
	return (strlen($x) > 2); 
}

?>
