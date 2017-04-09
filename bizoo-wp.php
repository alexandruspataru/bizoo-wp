<?php
/*
Plugin Name: Data Feed plugin for Bizoo.ro
Plugin URI: https://github.com/alexandruspataru/bizoo-wp
Version: 1.0.2
Description: The plugin helps you syncronize your products (or any custom post type) with Bizoo platform
Author: Alex Spataru
Author URI: https://alexspataru.com
Text Domain: nexus
Domain Path: /languages
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



/* 
 * Flush rewrite on plugin activation
 */ 
function bizoo_feed_activate () {
	
	bizoo_feed_rewrite_rules();		// First, create the rules
	flush_rewrite_rules();			// Then, update them
	
}
register_activation_hook(__FILE__, 'bizoo_feed_activate', 1);

/* 
 * Flush rewrite on plugin deactivation
 */ 
function bizoo_feed_deactivate () {
	
	flush_rewrite_rules();
	
}
register_deactivation_hook(__FILE__, 'bizoo_feed_deactivate', 1);

/* 
 * Translate the plugin
 */ 
function bizoo_feed_translate() {
    load_plugin_textdomain( 'nexus', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'bizoo_feed_translate' );
