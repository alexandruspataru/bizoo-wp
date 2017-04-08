<?php 

/* 
 * Options page
 */ 

/* Keep your sh*t secure, always! */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* 
 * Register the page and add it to menu
 */ 
add_action('admin_menu', 'bizoo_feed_admin_menu');
function bizoo_feed_admin_menu() {
	
	// Store translations
	$labels						= array();
	$labels['page_title']		= _x('Bizoo Data Feed Tool', 'Admin page title', 'nexus');
	$labels['menu_title']		= _x('Bizoo Feed', 'Admin menu', 'nexus');
	
	// Add page to admin menu
    add_submenu_page(
        'tools.php',									// parent_slug
        $labels['page_title'],							// page_title
        $labels['menu_title'],							// menu_title
        'manage_options',								// capability
        'bizoo-data-feed',								// menu_slug
        'bizoo_feed_admin_page'							// callback function
	);
	
}

/* 
 * Admin page content
 */ 
function bizoo_feed_admin_page() { 

?>

<div class="wrap">

	<!-- Page title -->
	<h2><?php echo _x('Bizoo Feed Settings', 'Admin page content', 'nexus'); ?></h2>
	
</div>
	
<?php /* Admin page content end */

}
