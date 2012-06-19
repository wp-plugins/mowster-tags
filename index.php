<?php
/*
	Plugin Name: mowsterTags
	Plugin URI: http://wordpress.mowster.net
	Description: Wordpress plugin for tag suggestions using Yahoo! Term Extraction API
	Author: mowster
	Version: 1.30
	License: GPLv2 or later
	Author URI: http://jobs.mowster.net
*/

if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}

define('MOWSTERTAGS_VERSION', 		'1.30');
define('MOWSTERTAGS_URL_PATH', 		WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
define('MOWSTERTAGS_PATH',			basename(rtrim(dirname(__FILE__), '/')));
define('MOWSTERTAGS_CHARSET',		get_bloginfo('charset'));


function mowsterTags_scripts(){
	global $post;
	
	if ($post->post_type == 'post') {
			
		$count = get_option('mowsterTags_count');
	   
		wp_enqueue_script('mowsterTags', MOWSTERTAGS_URL_PATH . 'js/tags.js' , false, MOWSTERTAGS_VERSION, false);
		$mowstervars = array(
			'mowsterTags_countTags' => $count,
			'mowsterTags_html_add_to' => '.tagsdiv .ajaxtag',
			'mowsterTags_newtags' => '#new-tag-post_tag',
			'mowsterTags_fetchTags' => __("Fetch tags", "mowsterTags"),
			'mowsterTags_fetchingTags' => __("Fetching tags...", "mowsterTags"),
			'mowsterTags_logo_path' => MOWSTERTAGS_URL_PATH . 'images/mowsterTags_logo.gif',
			'mowsterTags_loader_path' => MOWSTERTAGS_URL_PATH . 'images/mowsterTags_loader.gif',
			'mowsterTags_ajax_path' => get_option('siteurl') . '/wp-admin/admin-ajax.php',
			'mowsterTags_insuficient_text' =>  __("Insufficient content text length.", "mowsterTags"),
			'mowsterTags_server_error' => __("Yahoo server seems to be down at the moment. Please try again later.", "mowsterTags"),
			'mowsterTags_misc_error' => __("An error occurred. Please inform the creator of this plugin.", "mowsterTags")
		);
		wp_localize_script('mowsterTags', 'mowsterVars', $mowstervars);	
	
	}
}
add_action('admin_print_scripts-post.php', 'mowsterTags_scripts');
add_action('admin_print_scripts-post-new.php', 'mowsterTags_scripts');


function mowsterTags_styles(){
	global $post;
	if ($post->post_type == 'post') wp_enqueue_style('mowsterTags', MOWSTERTAGS_URL_PATH . 'css/style.css', '', MOWSTERTAGS_VERSION);
}
add_action('admin_print_styles-post.php', 'mowsterTags_styles');
add_action('admin_print_styles-post-new.php', 'mowsterTags_styles');


function mowsterTags_admin_init(){
	load_plugin_textdomain('mowsterTags', false, MOWSTERTAGS_PATH . '/langs');
	
	require_once(dirname(__FILE__).'/mowsterTagsAjax.php');
}
add_action('admin_init', 'mowsterTags_admin_init');


function mowsterTags_plugin_activate(){
	add_option('mowsterTags_count', 20);
}
register_activation_hook(__FILE__,'mowsterTags_plugin_activate');


function mowsterTags_plugin_deactivate(){
	delete_option('mowsterTags_count');
}
register_deactivation_hook(__FILE__, 'mowsterTags_plugin_deactivate');


add_action('wp_ajax_join_post_tags', 'join_post_mowsterTags');
?>
