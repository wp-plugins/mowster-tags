<?php
/*
Plugin Name: mowsterTags
Plugin URI: http://development.mowster.net
Description: Wordpress plugin for tag suggestions using Yahoo! Term Extraction API
Author: PedroDM
Version: 1.11
License: GPL
Author URI: http://jobs.mowster.net
*/


$plugin_url_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$pathinfo = pathinfo($_SERVER['REQUEST_URI']);

function mowsterTags_scripts () {
	global $plugin_url_path, $pathinfo;

	if (is_admin() && (strpos($pathinfo["basename"], 'post.php') === 0 || strpos($pathinfo["basename"], 'post-new.php') === 0)) {

		load_plugin_textdomain('mowsterTags', 'wp-content/plugins/mowster-tags');	   
	
		if (get_option('mowsterTags_count') > 0) {
			$count = get_option('mowsterTags_count');
		} else {
			$count = 20; update_option('mowsterTags_count', $count);
		}
	   
		wp_enqueue_script('mowsterTags', $plugin_url_path . 'tags.js' , false, '1.1', false);
		$mowstervars = array(
			'mowsterTags_countTags' => $count,
			'mowsterTags_fetchTags' => __("Fetch tags", "mowsterTags"),
			'mowsterTags_fetchingTags' => __("Fetching tags...", "mowsterTags"),
			'mowsterTags_timeout' => __("mowsterTags: Yahoo server seems to be down at the moment. Please try again later.", "mowsterTags"),
			'mowsterTags_error' => __("mowsterTags: An error occurred. Please inform the creator of this plugin.", "mowsterTags"),
			'mowsterTags_logo_path' => get_option('siteurl') . '/wp-content/plugins/mowster-tags/mowsterTags_logo.gif',
			'mowsterTags_ajax_path' => get_option('siteurl') . '/wp-admin/admin-ajax.php',
			'mowsterTags_insuficient_text' =>  __("mowsterTags: Insufficient content text length.", "mowsterTags"),
			'mowsterTags_server_error' => __("mowsterTags: Yahoo server seems to be down at the moment. Please try again later.", "mowsterTags"),
			'mowsterTags_misc_error' => __("mowsterTags: An error occurred. Please inform the creator of this plugin.", "mowsterTags")
		);
		wp_localize_script('mowsterTags', 'mowsterVars', $mowstervars);	
	}
   
}
add_action('admin_print_scripts', 'mowsterTags_scripts');


function mowsterTags_admin_init() {
	global $plugin_url_path, $pathinfo;
	
	if (is_admin() && (strpos($pathinfo["basename"], 'post.php') === 0 || strpos($pathinfo["basename"], 'post-new.php') === 0)) 
	wp_enqueue_style('mowsterTags', $plugin_url_path . 'style.css', '', '1.1');
	
	include(dirname(__FILE__).'/mowsterTagsAjax.php');
}
add_action('admin_init', 'mowsterTags_admin_init');


add_action('wp_ajax_join_post_tags', 'join_post_tags');
?>
