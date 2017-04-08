<?php 

/* 
 * Helpers page
 */ 

/* Keep your sh*t secure, always! */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* 
 * Enqueue styles
 */
function bizoo_feed_admin_enqueues($hook) {
	
	$pages = array(
		'tools_page_bizoo-data-feed',
	);
	
	/* Special admin pages only */
	if( in_array($hook, $pages) ) {

		wp_enqueue_style( 'bizoo_feed-superhero', bizoo_feed_settings('url') . 'assets/css/bootstrap-superhero.css' );
		wp_enqueue_style( 'bizoo_feed-custom', bizoo_feed_settings('url') . 'assets/css/custom-admin.css' );

	}
	
}
add_action( 'admin_enqueue_scripts', 'bizoo_feed_admin_enqueues' );