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

/* 
 * Add the query var
 */
function bizoo_feed_xml_query ($vars) {
	
	$vars[] = 'bizoo_feed_xml';
	return $vars;

}
add_filter('query_vars', 'bizoo_feed_xml_query', 1);


/* 
 * Create the rewrite rules
 */
function bizoo_feed_rewrite_rules () {
	
	add_rewrite_rule('bizoo\.xml$', 'index.php?bizoo_feed_xml', 'top');
	
}
add_action('init', 'bizoo_feed_rewrite_rules', 1);

/* 
 * Gets sitemap items
 */
function bizoo_feed_sitemap_content () {
	
	 global $wp_query;

	if (isset($wp_query->query_vars['bizoo_feed_xml'])) {
		
		$wp_query->is_404 = false;

		header('Content-type: application/xml; charset=utf-8');
		
		// Get the options
		$options	= get_option( 'bizoo_feed_settings' );

		/* 
		 * Create the query
		 */
		$bizoo_args						= array(); 
		$bizoo_args['post_status']		= array( 'publish' );

		// Query post type
		$bizoo_args['post_type']		= array( 'post' );
		if(isset($options['post_type']) && !empty($options['post_type']))
			$bizoo_args['post_type']	= array( $options['post_type'] );
		
		// Query post limit
		$bizoo_args['posts_per_page']	= '999999999999';
		if(isset($options['post_limit']) && !empty($options['post_limit']))
			$bizoo_args['posts_per_page']	= $options['post_limit'];
		
		// Query sort type
		$bizoo_args['order']	= 'ASC';
		if(isset($options['order_type']) && !empty($options['order_type']))
			$bizoo_args['order']	= $options['order_type'];
		
		// Query sort by
		$bizoo_args['orderby']	= 'id';
		if(isset($options['order_criteria']) && !empty($options['order_criteria']))
			$bizoo_args['orderby']	= $options['order_criteria'];
		
		// The Query
		$bizoo_q = new WP_Query( $bizoo_args );

		/* 
		 * The Loop
		 */
		if ( $bizoo_q->have_posts() ) {
			
			// Wrapper start
			$html = '<?xml version="1.0"?><products>';
			
			// Loop the products
			while ( $bizoo_q->have_posts() ) {
				$bizoo_q->the_post();
				
				/* 
				 * Get post info
				 */
				
				// Product wrapper start
				$html .=  "\n\t" . '<product>';
				
				$productID = get_the_ID();
				
				// Sanitize the name
				$title = str_replace('"', '', get_the_title());
				$title = str_replace('&#8220;', '"', $title);
				$title = str_replace('&#8221;', '"', $title);
				$title = str_replace('&#038;', '&', $title);
				
				// Categories
				$categoriesList = array();
				if(taxonomy_exists('produse'))
					$categoriesList = wp_get_post_terms($productID, 'produse', array("fields" => "names"));
				else{
					
					$categories = get_the_category();
					foreach($categories as $cat){
						
						$categoriesList[] = $cat->name;
						
					}
					
					
				}
					
				
				// Tags
				$tags		= get_the_tags();
				$taglist	= '';
				if(is_array($tags)){
					
					foreach($tags as $tag){
					
					$taglist .= $tag->name . ', ';
						
					}
					$taglist = trim($taglist, ', ');
					
				}
				
				// Item picture
				$itemPic = '';
				if ( has_post_thumbnail() ){
	
					$src 	 	= wp_get_attachment_image_src( get_post_thumbnail_id( $productID ), 'full' );
					
					if(!empty($src[0])){
						
						$itemPic = $src[0];
					
					}
					
				}
				
				/* 
				 * Create the output
				 */
				
				// Output the ID
				$html .= "\n\t\t" . '<id>' . $productID . '</id>' ;
				
				// Output the name
				$html .= "\n\t\t" . '<name><![CDATA[' . $title . ']]></name>' ;
				
				// Output the category
				$html .= "\n\t\t" . '<category><![CDATA[' . implode(', ', $categoriesList) . ']]></category>' ;
				
				// Output the model
				$html .= "\n\t\t" . '<model><![CDATA[]]></model>' ;
				
				// Output the tags
				$html .= "\n\t\t" . '<keywords><![CDATA[' . $taglist . ']]></keywords>' ;
				
				// Output the price
				$html .= "\n\t\t" . '<price>0.00</price>' ;
				
				// Output the availability
				$html .= "\n\t\t" . '<available>1</available>' ;
				
				// Output the existence of a shop
				$html .= "\n\t\t" . '<canBeOrderedOnline>0</canBeOrderedOnline>' ;
				
				// Output the informations
				$html .= "\n\t\t" . '<details><![CDATA[' . get_the_content() . ']]></details>' ;
				
				// Output the featured image
				$html .= "\n\t\t" . '<pictures><picture><![CDATA[' . $itemPic . ']]></picture></pictures>' ;
				
				// Output the currency
				$html .= "\n\t\t" . '<currency>USD</currency>' ;
				
				// Product wrapper end
				$html .= "\n\t" . '</product>';

			}
			
			$html .= '</products>';
			
			echo $html;
			
		} 

		// Restore original Post Data
		wp_reset_postdata();

		exit;
	}
		
}
add_filter('template_redirect', 'bizoo_feed_sitemap_content', 1);

