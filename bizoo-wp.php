<?php
/*
Plugin Name: Data Feed plugin for Bizoo.ro
Plugin URI: https://github.com/alexandruspataru/bizoo-wp
Version: 1.0.1
Description: The plugin helps you syncronize your products (or any custom post type) with Bizoo platform
Author: Alex Spataru
Author URI: https://alexspataru.com
Text Domain: nexus
GitHub Plugin URI: alexandruspataru/bizoo-wp
GitHub Plugin URI: https://github.com/alexandruspataru/bizoo-wp
*/

/* Keep your sh*t secure, always! */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* 
 * Plugin settings
 */ 
function bizoo_feed_settings($data = ''){
	
	$settings = array(
		'path'			=> plugin_dir_path( __FILE__ ),
		'url'			=> plugin_dir_url( __FILE__ ),
	);
	
	if(!empty($data) && $data != '' && array_key_exists($data, $settings)){
		
		return $settings[$data];
		
	}
	
}

/* 
 * Helpers
 */ 
include_once(bizoo_feed_settings('path') . '/inc/helpers.php');

/* 
 * Options page
 */ 
include_once(bizoo_feed_settings('path') . 'inc/options.php');