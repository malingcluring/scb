<?php

// ---------------------------------------------------------------------------------------------------------------- POST
	add_shortcode('post', 'scb_post');
	function scb_post($atts) {
		$cat = $atts['cat'];
		$args = array(
			'post_type' 		=> 'post' ,
			'orderby' 			=> 'date' ,
			'order' 			=> 'DESC' ,
			'posts_per_page' 	=> 6,
			'cat'         		=> $cat,
			'paged' 			=> get_query_var('paged'),
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
	
	$categories = get_categories( $args );
	$arrcategories=array();
	foreach ( $categories as $category ) :
		$value = $category->term_id;
		$option_label = $category->name;
		//array_push($arrcategories, $value => $option_label );
		$arrcategories[$value] = $option_label;
	endforeach;
	
	shortcode_ui_register_for_shortcode(
		'post',
		array(
			'label' => 'Choose Post Category',
			'listItemImage' => 'dashicons-filter',
			'attrs' => array(
				array(
					'label' => 'Post Category',
					'attr' => 'cat',
					'type' => 'select',
					'options' => $arrcategories
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
	




// ---------------------------------------------------------------------------------------------------------------- BG-IMG
add_shortcode('background', 'scb_background');
function scb_background($atts, $content = null) {
	$atts = shortcode_atts(array(
		'url' => '',
		'id' => ''
	), $atts, 'background');
	$id = ($atts['id']) ? 'id="' .$atts['id']. '"' : '';
	$url = $atts['url'];
	$result = '<div ' .$id. ' class="bg-img-float">'
				. '<img src="' .$url. '" class="img-float">'
				. '</div>';
	return $result;	
}

?>