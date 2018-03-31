<?php

//custom post sc
// Add Shortcode
function scb_post_shortcode() {
	$args = array(
		'post_type' => 'post' ,
		'orderby' => 'date' ,
		'order' => 'DESC' ,
		'posts_per_page' => 6,
		'cat'         => '3',
		'paged' => get_query_var('paged'),
		'post_parent' => $parent
	);
	
	
	

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ){
		while( $query->have_posts() ){
			$query->the_post();
			$string .= '<div>' . get_the_content() . '</div>';
		}
	}
	wp_reset_postdata();
	return $string;

}
add_shortcode( 'scb-post', 'scb_post_shortcode' );

?>