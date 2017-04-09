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
 * Please remember to sanitize the data before inseting it into database
 */ 

if(isset($_POST['bizoo_save_changes'])){
	
	/* 
	 * Custom post type
	 */ 
	if(isset($_POST['bizoo_post_type']) && !empty($_POST['bizoo_post_type'])){
		
		// Get the list of avaliable post types
		
		$postTypes = get_post_types(array('public' => true, '_builtin' => false), 'objects', 'or');
		
		$sanitizedPostTypes = array(); 
		
		foreach($postTypes as $postType){
			
			$sanitizedPostTypes[] = $postType->name;
			
		}
		
		// If we have a match, go ahead
		if( in_array($_POST['bizoo_post_type'], $sanitizedPostTypes) ) {
			
			echo $_POST['bizoo_post_type'] . '<br>';
			
		}
		
	}
	
	/* 
	 * Posts limit
	 */ 
	if(isset($_POST['bizoo_post_limit']) && is_numeric($_POST['bizoo_post_limit'])){
		
		echo $_POST['bizoo_post_limit'] . '<br>';
		
	}
	
	/* 
	 * Order type
	 */ 
	if(isset($_POST['bizoo_post_order_type']) && $_POST['bizoo_post_order_type'] == 'ASC' ||  $_POST['bizoo_post_order_type'] == 'DESC' ){
		
		echo $_POST['bizoo_post_order_type'] . '<br>';
		
	}
	
	/* 
	 * Order criteria
	 */ 
	$orderCriteria = array('none', 'rand', 'id', 'date', 'modified', 'title', 'name', 'parent', 'comment_count');
	
	if(isset($_POST['bizoo_post_order_criteria']) && in_array($_POST['bizoo_post_order_criteria'], $orderCriteria)){
		
		echo $_POST['bizoo_post_order_criteria'] . '<br>'; 
		
		
	}

}



/* 
 * Admin page content
 */ 
function bizoo_feed_admin_page() { 

?>

<div class="wrap bizoo-wrap">

	<!-- Page info -->
	<div class="bs-callout bs-callout-info" id="callout-help-text-accessibility">
	
		<!-- Debug -->
		<div class="hiddden">
			<pre>
				<?php var_dump($_POST); ?>
			</pre>
		</div>
		
		<!-- Page title -->
		<h4><?php echo _x('Bizoo Feed Settings', 'Admin page content', 'nexus'); ?></h4> <br>
		
		<!-- Form start -->
		<form class="form-vertical bizoo-form" action="" method="post">
	
				<!-- Select custom post type -->
				<div class="form-group">

					<label for="bizoo_post_type" class="col-lg-6 control-label">
						<?php echo _x('Please select the post type you would like to include in the Data Feed.', 'Admin page content', 'nexus'); ?>
					</label>
					
					<div class="col-lg-6">
						<select class="form-control" id="bizoo_post_type" name="bizoo_post_type">
							<option value="" selected><?php echo _x('Please choose something', 'Admin page content', 'nexus'); ?></option>
							<?php 
							
								/* 
								 * Get all post types
								 */ 
								 
								$postArgs = array(
									'public' => true,
									'_builtin' => false,
								);
								
								$post_types = get_post_types($postArgs, 'objects', 'or');
								
								foreach($post_types as $type){
									
									echo '<option value="' . $type->name . '">' . $type->label . '</option>';
									
								}

							?>
						</select>
					</div>
					
				</div>
				
				<!-- Select limit -->
				<div class="form-group">

					<label for="bizoo_post_limit" class="col-lg-6 control-label">
						<?php echo _x('Optional - How many posts to include? Leave blank to include them all.', 'Admin page content', 'nexus'); ?>
					</label>
					
					<div class="col-lg-6">
						<input type="number" class="form-control" id="bizoo_post_limit" name="bizoo_post_limit" placeholder="<?php echo _x('All', 'Admin page content', 'nexus'); ?>">
					</div>
					
				</div>
				
				<!-- Select Order type -->
				<div class="form-group">

					<label for="bizoo_post_order_type" class="col-lg-6 control-label">
						<?php echo _x('Optional - Order type', 'Admin page content', 'nexus'); ?>
					</label>
					
					<div class="col-lg-6">
					
						<select class="form-control" id="bizoo_post_order_type" name="bizoo_post_order_type">
							<option value="" selected><?php echo _x('Please choose something', 'Admin page content', 'nexus'); ?></option>
							<option value="ASC"><?php echo _x('Ascending', 'Admin page content', 'nexus'); ?></option>
							<option value="DESC"><?php echo _x('Descending', 'Admin page content', 'nexus'); ?></option>
						</select>
						
					</div>
					
				</div>

				<!-- Select Order criteria -->
				<div class="form-group">

					<label for="bizoo_post_order_criteria" class="col-lg-6 control-label">
						<?php echo _x('Optional - Order criteria', 'Admin page content', 'nexus'); ?>
					</label>
					
					<div class="col-lg-6">
						
						<select class="form-control" id="bizoo_post_order_criteria" name="bizoo_post_order_criteria">
							<option value="" selected><?php echo _x('Please choose something', 'Admin page content', 'nexus'); ?></option>
							<option value="none"><?php echo _x('None', 'Admin page content', 'nexus'); ?></option>
							<option value="rand"><?php echo _x('Random', 'Admin page content', 'nexus'); ?></option>
							<option value="id"><?php echo _x('ID', 'Admin page content', 'nexus'); ?></option>
							<option value="date"><?php echo _x('Date inserted', 'Admin page content', 'nexus'); ?></option>
							<option value="modified"><?php echo _x('Date modified', 'Admin page content', 'nexus'); ?></option>
							<option value="title"><?php echo _x('Title', 'Admin page content', 'nexus'); ?></option>
							<option value="name"><?php echo _x('Slug', 'Admin page content', 'nexus'); ?></option>
							<option value="parent"><?php echo _x('Parent ID', 'Admin page content', 'nexus'); ?></option>
							<option value="comment_count"><?php echo _x('Comment Count', 'Admin page content', 'nexus'); ?></option>
						</select>
						
					</div>
					
				</div>

				<div class="clear">&nbsp;</div>
				
				<!-- Submit / Reset -->
				<div class="col-md-9 col-md-offset-3 text-right">
					<button type="reset" class="btn btn-primary"><?php echo _x('Reset', 'Admin page content', 'nexus'); ?></button>
					<button type="submit" id="bizoo_save_changes" name="bizoo_save_changes" class="btn btn-success ml-5"><?php echo _x('Save changes', 'Admin page content', 'nexus'); ?></button>
				</div>
				
				<div class="clear">&nbsp;</div>
				
		</form>
		
		<div class="clear">&nbsp;</div>
		


		
		
		
	</div>
</div>

<?php } // End Wrapper
