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
	
	
	//------------------------- get all category
	$categories = get_categories( $args );
	$arrcategories=array();
	foreach ( $categories as $category ) :
		$value = $category->term_id;
		$option_label = $category->name;
		//array_push($arrcategories, $value => $option_label );
		$arrcategories[$value] = $option_label;
	endforeach;
	
	
	//-------------------------- set UI for post category shortcode	
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

//------------------------- get all media gallery
	$query_images_args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => - 1,
	);
	
	$query_images = new WP_Query( $query_images_args );
	
	$images = array();
	foreach ( $query_images->posts as $image ) {
		$images[] = wp_get_attachment_url( $image->ID );
	}

//-------------------------- set UI for background shortcode
	shortcode_ui_register_for_shortcode(
		'background',
		array(
			'label' => 'BACKGROUND PICTURE',
			'listItemImage' => 'dashicons-format-image',
			'attrs' => array(
				array(
					'label' => 'Select an image',
					'atts' => 'url',
					'type' => 'select',
					'options' => $images
				),
				array(
					'label' => 'Add ID',
					'attr' => 'id',
					'type' => 'text'
				),
			),
			'post_type' => array( 'post', 'page' ),
		)
	);
	
	
//------------------------------------------------------------------------------------------------------------------------------------CAROUSEL
	add_shortcode( 'carousel', 'scb_carousel' );
	
	function scb_carousel(  $paramatts , $content , $tag ) {
		preg_match_all( '@src="([^"]+)"@' , $content, $match );
	
		$atts = array(
			'carousel_class'        => 'carousel slide carousel-fade',
			'carousel_data-ride'    => 'carousel',
			'indicators_class'      => 'carousel-indicators',
			'carousel_inner'        => 'carousel-inner',
			'listbox'               => 'listbox',
			'carousel_item'         => 'item fullscreen',
		);
	
		$indicators = '';
		$slide = '';
		for($i=0; $i<count($match[1]); $i++) {
			$active = '';
			$data_slide = ($i + 1);
			if($i==0) $active = 'active';
			$indicators .= '<li                                        
								data-target="#'.$paramatts['carousel_id'].'"
								data-slide-to="'.$i.'" data-val="'.$data_slide.'" class="'.$active.'"></li>';
			$slide .= '<div                                               
							class="'.$atts['carousel_item'].' '. $active.'"
							style="background-image: url('.$match[1][$i].')"><div class="carousel-caption"><p>caption</p></div></div>
							';
		}
	
		return '<div 
						id="'.$paramatts['carousel_id'].'"
						class="'.$atts['carousel_class'].'"
						data-ride="'.$atts['carousel_data-ride'].'">
							<ul class="'.$atts['indicators_class'].'">'.$indicators.'</ul>
							<div class="'.$atts['carousel_inner'].'" role="'.$atts['listbox'].'">'.$slide.'</div>   
					</div>';
	}
	

?>