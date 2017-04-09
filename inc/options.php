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
 * Add the settings section
 */ 
add_action( 'admin_init', 'bizoo_feed_settings_init' );
function bizoo_feed_settings_init() { 

	// Register the settings 
	register_setting( 'bizoo_feed', 'bizoo_feed_settings' );

	// Add settings section
	add_settings_section(
		'bizoo_feed_section', 
		_x('Bizoo Feed Settings', 'Admin page content', 'nexus'), 
		'bizoo_feed_section_callback', 
		'bizoo_feed'
	);

	/* 
	 * Register Field 1 - Post type
	 */ 
	$label1		 =  '<label for="bizoo_feed_settings[post_type]" class="control-label">';
	$label1		.=  _x('Please select the post type you would like to include in the Data Feed.', 'Admin page content', 'nexus');
	$label1		.=  '</label>';
	
	add_settings_field( 
		'post_type', 
		$label1, 
		'bizoo_feed_post_type_render', 
		'bizoo_feed', 
		'bizoo_feed_section' 
	);

	/* 
	 * Register Field 2 - Select limit
	 */ 
	$label2		 =  '<label for="bizoo_feed_settings[post_limit]" class="control-label">';
	$label2		.=  _x('Optional - How many posts to include? Leave blank to include them all.', 'Admin page content', 'nexus');
	$label2		.=  '</label>';
	
	add_settings_field( 
		'post_limit', 
		$label2, 
		'bizoo_feed_post_limit_render', 
		'bizoo_feed', 
		'bizoo_feed_section' 
	);

	/* 
	 * Register Field 3 - Order type
	 */ 
	$label3		 =  '<label for="bizoo_feed_settings[order_type]" class="control-label">';
	$label3		.=  _x('Optional - Order type', 'Admin page content', 'nexus');
	$label3		.=  '</label>';
	
	add_settings_field( 
		'order_type', 
		$label3, 
		'bizoo_feed_order_type_render', 
		'bizoo_feed', 
		'bizoo_feed_section' 
	);

	/* 
	 * Register Field 4 - Order criteria
	 */ 
	$label4		 =  '<label for="bizoo_feed_settings[order_criteria]" class="control-label">';
	$label4		.=  _x('Optional - Order criteria', 'Admin page content', 'nexus');
	$label4		.=  '</label>';
	
	add_settings_field( 
		'order_criteria', 
		$label4, 
		'bizoo_feed_order_criteria_render', 
		'bizoo_feed', 
		'bizoo_feed_section' 
	);

}

// Page content callback
function bizoo_feed_section_callback() {

}

// Callback Field 1 - Post type
function bizoo_feed_post_type_render(  ) { 

	// Get all post types
	$post_types = get_post_types(array('public' => true,'_builtin' => false,), 'objects', 'or');

	// Wrapper start
	$html		= '<div class="form-group">';
	
	// Get options
	$options	= get_option( 'bizoo_feed_settings' );

	// Seect start
	$html		.=  '<div class=""><select class="form-control" id="bizoo_feed_settings[post_type]" name="bizoo_feed_settings[post_type]">';
	$html		.=  '<option value="" ' . ($options['post_type'] == '' ? 'selected' : '') . '>' . _x('Please choose something', 'Admin page content', 'nexus') . '</option>';
	
	// Loop trought options
	foreach($post_types as $type){
		
		// Get current field
		$selected = '';
		if($options['post_type'] == $type->name)
			$selected = 'selected';
									
		$html	.= '<option value="' . $type->name . '" ' . $selected . '>' . $type->label . '</option>';
		
	}
	
	$html		.=  '</select></div>';
	
	// Wrapper end
	$html		.=  '</div>';
	
	echo $html;
	

}

// Callback Field 2 - Select limit
function bizoo_feed_post_limit_render() { 

	// Get options
	$options	= get_option( 'bizoo_feed_settings' );

	// Wrapper 
	$html = '<input type="number" name="bizoo_feed_settings[post_limit]" value="' .  $options['post_limit'] . '">';
	
	echo $html;
	
}

// Callback Field 3 - Order type
function bizoo_feed_order_type_render() { 

	// Wrapper start
	$html		= '<div class="form-group">';
	
	// Get options
	$options	= get_option( 'bizoo_feed_settings' );

	// Seect start
	$html		.=  '<div class=""><select class="form-control" id="bizoo_feed_settings[order_type]" name="bizoo_feed_settings[order_type]">';
	
	// Option 1 - nothing
	$html		.=  '<option value="" ' . ($options['order_type'] == '' ? 'selected' : '') . '>' . _x('Please choose something', 'Admin page content', 'nexus') . '</option>';
	
	// Option 2 - Ascending
	$html		.=  '<option value="ASC" ' . ($options['order_type'] == 'ASC' ? 'selected' : '') . '>' . _x('Ascending', 'Admin page content', 'nexus') . '</option>';
	
	// Option 3 - Descending
	$html		.=  '<option value="DESC" ' . ($options['order_type'] == 'DESC' ? 'selected' : '') . '>' . _x('Descending', 'Admin page content', 'nexus') . '</option>';
	

	$html		.=  '</select></div>';
	
	// Wrapper end
	$html		.=  '</div>';
	
	echo $html;
	
}

// Callback Field 4 - Order criteria
function bizoo_feed_order_criteria_render() { 

	// Store all the possibilities
	$orderType = array(		
		'none'				=> _x('None', 'Admin page content', 'nexus'),
		'rand'				=> _x('Random', 'Admin page content', 'nexus'),
		'id' 				=> _x('ID', 'Admin page content', 'nexus'),
		'date'				=> _x('Date inserted', 'Admin page content', 'nexus'),
		'modified'			=> _x('Date modified', 'Admin page content', 'nexus'),
		'title'				=> _x('Title', 'Admin page content', 'nexus'),
		'parent'			=> _x('Parent ID', 'Admin page content', 'nexus'),
		'comment_count'		=> _x('Comment Count', 'Admin page content', 'nexus'),
	);

	// Wrapper start
	$html		= '<div class="form-group">';
	
	// Get options
	$options	= get_option( 'bizoo_feed_settings' );

	// Seect start
	$html		.=  '<div class=""><select class="form-control" id="bizoo_feed_settings[order_criteria]" name="bizoo_feed_settings[order_criteria]">';
	
	// Option 1 - nothing
	$html		.=  '<option value="" ' . ($options['order_criteria'] == '' ? 'selected' : '') . '>' . _x('Please choose something', 'Admin page content', 'nexus') . '</option>';
	
	// Other options
	foreach($orderType as $value=>$name){
		
		$html		.=  '<option value="' . $value .'" ' . ($options['order_criteria'] == $value ? 'selected' : '') . '>' . $name . '</option>';
		
	}

	$html		.=  '</select></div>';
	
	// Wrapper end
	$html		.=  '</div>';
	
	echo $html;
	
}

/* 
 * Admin page content
 */ 
function bizoo_feed_admin_page() { 

?>

<div class="wrap bizoo-wrap">

	<!-- Page info -->
	<div class="bs-callout bs-callout-info" id="callout-help-text-accessibility">
		
		<!-- Form start -->
		<form class="form-vertical bizoo-form" action="options.php" method="post">

				<?php 
								
					settings_fields( 'bizoo_feed' );
					do_settings_sections( 'bizoo_feed' );
					submit_button();

				?>
				
				<div class="clear">&nbsp;</div>

		</form>
		
		<div class="clear">&nbsp;</div>

	</div>
</div>

<?php } // End Wrapper

