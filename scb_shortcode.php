<?php

// ---------------------------------------------------------------------------------------------------------------- POST
add_shortcode( 'post', 'scb_post' );
function scb_post() {
	$args = array(
		'post_type' 		=> 'post' ,
		'orderby' 			=> 'date' ,
		'order' 			=> 'DESC' ,
		'posts_per_page' 	=> 6,
		'cat'         		=> '3',
		'paged' 			=> get_query_var( 'paged' ),
		'post_parent' 		=> $parent
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




// ---------------------------------------------------------------------------------------------------------------- CAROUSEL
add_shortcode( 'carousel', 'scb_carousel' );
function scb_carousel( $atts, $content = null ) {
	if( isset( $GLOBALS[ 'count' ] ) )
		$GLOBALS[ 'count' ]++;
	else
		$GLOBALS[ 'count' ] = 0;
	$GLOBALS[ 'default_count' ] = 0;
		
	
	$atts = shortcode_atts(array(
		'id'	=> 'carousel_id',
		'class' => '',
		'url'	=> ''
	), $atts );
	
	
}

?>