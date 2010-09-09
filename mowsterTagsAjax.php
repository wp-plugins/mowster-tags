<?php
/*
mowsterTags
mowsterTags Plugin for Wordpress 3.0 (or newer)
Copyright (C) 2010 jobs.mowster.net

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Include wp-config.php
$wp_root = '../../..';
if (file_exists($wp_root.'/wp-load.php')) {
	require_once($wp_root.'/wp-load.php');
} else {
	require_once($wp_root.'/wp-config.php');
}

 

// Save count option
$option_name = "mowsterTags_count";
$text = $_POST['text'];
$count = $_POST['count'];
$tags = $_POST['tags'];
if (get_option($option_name)) {
   update_option($option_name, $count);
} else {
   add_option($option_name, $count);
}




// Get tags
status_header( 200 );
header("Content-Type: text/plain; charset=" . get_option('blog_charset'));

		
// Get data
$content = stripslashes($text) .' '. stripslashes($_POST['title']);
$content = trim($content);
if ( empty($content) ) {
			echo $tags;
			exit();
}
		
// Build params
$param = 'appid=h4c6gyLV34Fs7nHCrHUew7XDAU8YeQ_PpZVrzgAGih2mU12F0cI.ezr6e7FMvskR7Vu.AA--'; // Yahoo ID
$param .= '&context='.urlencode($content); // Post content
if ( !empty($_POST['tags']) ) {
	$param .= '&query='.urlencode(stripslashes($_POST['tags'])); // Existing tags
}
$param .= '&output=php'; // Get PHP Array !
		
$data = array();
$reponse = wp_remote_post( 'http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction', array('body' =>$param) );
if( !is_wp_error($reponse) && $reponse != null ) {
	if ( wp_remote_retrieve_response_code($reponse) == 200 ) {
		$data = maybe_unserialize( wp_remote_retrieve_body($reponse) );
	}
}
		
if ( empty($data) || empty($data['ResultSet']) || is_wp_error($data) ) {
	echo $tags;
	exit();
}
		
// Get result value
$data = (array) $data['ResultSet']['Result'];
		
// Remove empty terms
$data = array_unique($data);
		
foreach ( (array) $data as $term ) {
	$display = $display . esc_html($term).",";
}

if ($tags && substr($tags,-1) != ',') $tags=$tags.",";

echo $tags.substr($display,0,-1);
exit();
?>
