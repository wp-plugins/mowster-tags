<?php

if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}

function join_post_mwtags(){

	
	/* ajax $_REQUEST */
	$text = trim(stripslashes(wp_filter_nohtml_kses($_REQUEST['text'])));
	$count = $_REQUEST['count'];
	$tags = stripslashes(wp_filter_nohtml_kses($_REQUEST['tags']));
	
	
	/* limit user option */
	$user_id = get_current_user_id();
	$mwtags_count = get_user_meta($user_id, 'mwtags_count', true);
	if ( !empty($mwtags_count) || $mwtags_count != $count ):
		update_user_meta( $user_id, 'mwtags_count', $count );
	endif;
		
		
	/* post content */
	$content = trim($text);
	if (strlen($content) < 20) :
		mwtags_results($tags, 'error', __('Insufficient content text length.', MWTAGS_MAIN_ACTION));
	endif;
			

	/* build url params */
	$url = 'http://query.yahooapis.com/v1/public/yql?q=';
	
	$content = addslashes(str_replace('"', ' ', strip_tags($content)));
	/* text lenght warning */
	if (strlen($content) >= 7280) :		
		$content = substr($content, 0, 7000);
		$warning = __('Text length is over the limit handled by Yahoo. Only first 7000 chars were analyzed to find related tags.', MWTAGS_MAIN_ACTION); 		
	endif;	
	
	$q = urlencode( sprintf('select * from search.termextract where context = "%s"', $content) ); /* post content */
	if (!empty($tags)):
		$url .= $q.urlencode(' and query="'.$tags.'"');	/* existing tags */	
	else:
		$url .= $q;
	endif;
	$url .= '&format=json&diagnostics=true';
	
			
	/* get request */
	$response = wp_remote_get($url, $args = array('timeout' => $_REQUEST['timeout']));
	
	if (!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200):
		$data = json_decode(maybe_unserialize($response['body']));
	else:
		$msg = __('Yahoo YQL server did not reply due one of the following reasons:', MWTAGS_MAIN_ACTION) . "\n"; 		
		$msg .= __('1. Remote Yahoo service is down, try again within few seconds.', MWTAGS_MAIN_ACTION) . "\n";
		$msg .= __('2. Your IP reached the 2000 limit requests per hour accepted, register at Yahoo for an API key.', MWTAGS_MAIN_ACTION); 				
		
		mwtags_results($tags, 'error', $msg);
	endif;

	if (empty($data) || empty($data->query->results->Result)) :
		mwtags_results($tags, 'error', __('Yahoo YQL did not find any tags for the content provided.', MWTAGS_MAIN_ACTION));
	endif;
	
	/* process tags */
	$new_tags = array_unique($data->query->results->Result); /* avoid repeated terms */	
	$new_tags = array_filter($new_tags, 'mwtags_callback_limit'); /* eliminate small tags */
	
	/* check existing tags */
	$check_tags = array_map('mwtags_callback_trim_lower', (explode(',', $tags)));
	
	$display = null; $limit = null;	
	foreach ($new_tags as $tag) :		
		if (!in_array(mwtags_callback_trim_lower($tag), $check_tags)):
			$display .= esc_html($tag).","; 
		endif;
		$limit++;	
		if ($limit == $count) :
			break;
		endif;
	endforeach;
	
	
	/* display terms */
	if (!empty($tags) && substr($tags,-1) != ','):
		$tags .= ",";
	endif;
	
	$output = $tags.trim($display, ',');
	
	if (empty($warning)):
		mwtags_results( $output );	
	else:
		mwtags_results( $output, 'warning', $warning );	
	endif;
	
	
	die();
}
?>
