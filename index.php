<?php

/*
	Plugin Name: tags.mowster
	Plugin URI: http://wordpress.mowster.net
	Description: Tags suggestions using YQL Yahoo Content Analysis API
	Author: mowster
	Author URI: http://jobs.mowster.net
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	Version: 1.71
*/

if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"])) {
	$location = 'http://'.substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].'/'.$_SERVER["REQUEST_URI"], '/wp-content/'));
	header('Location: '.$location);
	die();
}

define('MWTAGS_VERSION',            '1.71');
define('MWTAGS_PLUGIN_NAME',        'tags.mowster');
define('MWTAGS_MAIN_ACTION',        'mwtags');
define('MWTAGS_URL_PATH',           WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)));
define('MWTAGS_PLUGIN_PATH',        realpath(dirname(__FILE__)));
define('MWTAGS_PLUGIN_FILE',        basename(__FILE__));
define('MWTAGS_PLUGIN_SLUG',        rtrim(str_replace(basename(__FILE__),"",plugin_basename(__FILE__)), '/'));
define('MWTAGS_BASENAME',           plugin_basename(__FILE__));
define('MWTAGS_CHARSET',            get_bloginfo('charset'));
define('MWTAGS_SITE_URL',           get_bloginfo('url'));
define('MWTAGS_DEFAULT_COUNT',     	20);


/* admin_print_scripts */
function mwtags_scripts(){
	
	/* jquery */
	wp_enqueue_script('jquery');	
	
	/* user_meta */
	$count = get_user_meta(get_current_user_id(), 'mwtags_count', true);
	if (empty($count)) $count = MWTAGS_DEFAULT_COUNT;
	
	/* enqueue on posts */
	global $post;
	
	if ($post->post_type == 'post'):
	   
		wp_enqueue_script( MWTAGS_MAIN_ACTION , MWTAGS_URL_PATH . 'js/tags.js' , array('jquery'), MWTAGS_VERSION, false);
		$mowstervars = array(
			'mwtags_countTags' => $count,
			'mwtags_html_add_to' => '.tagsdiv .ajaxtag',
			'mwtags_newtags' => '#new-tag-post_tag',
			'mwtags_fetchTags' => __('Fetch tags', MWTAGS_MAIN_ACTION),
			'mwtags_fetchingTags' => __('Fetching tags...', MWTAGS_MAIN_ACTION),
			'mwtags_ajax_path' =>  admin_url('admin-ajax.php'),
		);
		wp_localize_script( MWTAGS_MAIN_ACTION, 'mowsterVars', $mowstervars);	
	
	endif;
	
}
add_action('admin_print_scripts-post.php', 'mwtags_scripts');
add_action('admin_print_scripts-post-new.php', 'mwtags_scripts');

function mwtags_global_scripts(){

	/* settings */
	global $mwtags_st;
	if (!is_object($mwtags_st)) $mwtags_st = mwtags_rt_settings();	

	if (current_user_can('update_plugins') && $mwtags_st->api['last_version'] > MWTAGS_VERSION && mwtags_curURL() != admin_url('plugins.php') && mwtags_curURL() != admin_url('update-core.php') && esc_url(MWTAGS_curURL()) != esc_url(self_admin_url('update-core.php?action=do-plugin-upgrade'))) :			
		$update_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE, 'upgrade-plugin_' . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE);
		if (esc_url(mwtags_curURL()) != esc_url($update_url)):
			wp_enqueue_script('thickbox');
		endif;
	endif;	
}
add_action('admin_print_scripts', 'mwtags_global_scripts');


/* admin_print_styles */
function mwtags_styles(){
	
	/* enqueue on posts */
	global $post;
	if ($post->post_type == 'post') wp_enqueue_style(MWTAGS_MAIN_ACTION, MWTAGS_URL_PATH . 'css/style.css', '', MWTAGS_VERSION);

}
add_action('admin_print_styles-post.php', 'mwtags_styles');
add_action('admin_print_styles-post-new.php', 'mwtags_styles');

function mwtags_global_styles(){

	/* settings */
	global $mwtags_st;	
	if (!is_object($mwtags_st)) $mwtags_st = mowsterwps_rt_settings();
	
	if (current_user_can('update_plugins') && $mwtags_st->api['last_version'] > MWTAGS_VERSION && mwtags_curURL() != admin_url('plugins.php') && mwtags_curURL() != admin_url('update-core.php') && esc_url(MWTAGS_curURL()) != esc_url(self_admin_url('update-core.php?action=do-plugin-upgrade'))) :			
		$update_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE, 'upgrade-plugin_' . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE);
		if (esc_url(mwtags_curURL()) != esc_url($update_url)):
			wp_enqueue_style('thickbox');
		endif;
	endif;	
}
add_action('admin_print_styles', 'mwtags_global_styles'); 


/* activation, deactivate, uninstall */
function mwtags_plugin_activate(){
	mwtags_options('activate');
}
register_activation_hook(__FILE__,'mwtags_plugin_activate');

function mwtags_plugin_deactivate(){
	mwtags_options('deactivate');
}
register_deactivation_hook( __FILE__, 'mwtags_plugin_deactivate');

function mwtags_plugin_uninstall(){
	mwtags_options('uninstall');
}
register_uninstall_hook(__FILE__,'mwtags_plugin_uninstall');

function mwtags_options($action){

	$mwtags_default_options = array (
		'mwtags_settings' => serialize(array('domain' => null, 'domain_check' => null)),
		'mwtags_api' => serialize(array('last_version' => MWTAGS_VERSION))
	);
	
	foreach ($mwtags_default_options as $key => $value) :
		switch ($action):
			case 'activate': 
				if (!get_option($key)) :
					add_option($key, $value);
				else:
					/* remove old options */
					if ($key == 'mwtags_settings'):
						$new_value = @unserialize(get_option('mwtags_settings'));
					
						/* mwtags_count : v1.63 */
						if (isset($new_value['mwtags_count'])):
							unset($new_value['mwtags_count']); 
							update_option($key, $new_value);
						endif;
					endif;
				endif;
				break;
			case 'deactivate': 
				if ($key == 'mwtags_api') update_option($key, $value);
				break;				
			case 'uninstall': 
				delete_option($key);
				break;			
		endswitch;
	endforeach;

	return;	
}


/* admin_init */
function mwtags_admin_init(){

	/* textdomain */
	load_plugin_textdomain(MWTAGS_MAIN_ACTION, false, basename(rtrim(dirname(__FILE__), '/')) . '/langs');
		
	/* required files */
	require_once(MWTAGS_PLUGIN_PATH . '/functions.php');
	require_once(MWTAGS_PLUGIN_PATH . '/ajax/ajax.php');	
	
	/* settings */
	global $mwtags_st;	
	if (!is_object($mwtags_st)) $mwtags_st = mwtags_rt_settings();	
	
	/* ajax action */
	add_action('wp_ajax_join_post_mwtags', 'join_post_mwtags');	
	
	require_once(MWTAGS_PLUGIN_PATH . '/update.php');	
	
}
add_action('admin_init', 'mwtags_admin_init');


/* admin_notices */
function mwtags_admin_notices(){

	if (current_user_can('update_plugins')):
	
		global $mwtags_st;
		if (!is_object($mwtags_st)) $mwtags_st = mwtags_rt_settings();
		
		if (isset($mwtags_st->api['last_version']) && $mwtags_st->api['last_version'] > MWTAGS_VERSION && mwtags_curURL() != admin_url('plugins.php') && mwtags_curURL() != admin_url('update-core.php') && esc_url(mwtags_curURL()) != esc_url(self_admin_url('update-core.php?action=do-plugin-upgrade'))) :			
			$update_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE, 'upgrade-plugin_' . MWTAGS_PLUGIN_SLUG.'/'.MWTAGS_PLUGIN_FILE);
			if (esc_url(mwtags_curURL()) != esc_url($update_url)):
				$details_url = self_admin_url('plugin-install.php?tab=plugin-information&plugin=' . MWTAGS_PLUGIN_SLUG . '&section=changelog&TB_iframe=true&width=600&height=800');			
				$message = sprintf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">update now</a>.'), MWTAGS_PLUGIN_NAME, esc_url($details_url), esc_attr(MWTAGS_PLUGIN_NAME), $mwtags_st->api['last_version'], $update_url );							
				echo '<div id="message" class="updated fade"><p><b>'.$message.'</b></p></div>';
			endif;
		endif;
	
	endif;

}
add_action('admin_notices', 'mwtags_admin_notices');
?>
