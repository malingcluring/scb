<?php

// SHORTCODES test

// PAGE LINK SHORTCODE
function page_link_sc($atts) {
    $output = '';
    
    $atts = shortcode_atts(array(
        'ids' => '',
    ), $atts );
    
    $ids = array_map('interval', explode(',', $atts['ids']));
    
    if($ids) {
        $output .= '<div class="page-link">';
        
        foreach($ids as $id) {
            $tyle = has_post_thumbnail($id) ? 'style="background-image: url(' .wp_get_attachment_image_url(get_post_thumbnail($id), '_page_link'). ');"' : '';
            
            $output .= '<a href="' . get_permalink( $id ) . '"' . $style . '><span class="page-link-title">' . get_the_title( $id ) . '</span></a>';
        }
        $output .= '</div>';
    }
    
    return $output;
}
add_shortcode( 'page-link', 'page_link_sc' );

// SHORTCAKE
function page_link_sc_ui() {
    if(!function_exists('shortcode_ui_register_for_shortcode'))
        return;
    
    shortcode_ui_register_for_shortcode(
        'page-link',
        array(
            'label' => 'Page Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Page Link',
                    'attr' => 'ids',
                    'type' => 'post_select',
                    'query' => array('post_type' => 'post'),
                    'multiple' => true,
                )
            )
        )
    );
}
add_action('init', 'page_link_sc_ui');

function page_link_image_size() {
    add_image_size('page_link', 470, 300, true);
}
add_action('after_setup_theme', 'page_link_images_ize');


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

// ---------------------------------------------------------------------------------------------------------------- SHORTCAKE ----------------------------------------------------------------------------------------------------------------
add_action('init', function() {

// UI FOR POST SELECT SHORTCODE
	shortcode_ui_register_for_shortcode(
		'post_select',
		array(
			'label' => 'POST SELECT',
			'listItemImage' => 'dashicons-format-aside',
			'attrs' => array(
				array(
					'label' => 'select post',
					'attr' => 'post',
					'type' => 'post_select',
					'query' => array('post_type' => 'post'),
					'multiple'=> true
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// END UI FOR POST SELECT SHORTCODE

// ---------------------------------------------------------------------------------------------------------------- SET UI FOR LIGHTBOX SHORTCODE
	shortcode_ui_register_for_shortcode(
		'lightbox',
		array(
			'label' => 'Lightbox',
			'listItemImage' => 'dashicons-video-alt3',
			'attrs' => array(
				array(
					'label' => 'Add Video ID',
					'atts' => 'vdid',
					'type' => 'text',
				),
				array(
					'label' => 'Add ID',
					'atts' => 'id',
					'type' => 'text',
				),
				array(
					'label' => 'Add class',
					'atts' => 'class',
					'type' => 'text',
				),
				array(
					'label' => 'Add Caption',
					'atts' => 'caption',
					'type' => 'text',
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR LIGHTBOX SHORTCODE
	

	
	
	
// ---------------------------------------------------------------------------------------------------------------- SET UI FOR POST CATEGORY
	shortcode_ui_register_for_shortcode(
		'post',
		array(
			'label' => 'Choose Post Category',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'Post Category',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
				array(
					'label' => 'ID',
					'attr' => 'post_id',
					'type' => 'text',
				),
				array(
					'label' => 'Post Count',
					'attr' => 'count',
					'type' => 'number',
				),
				array(
					'label' => 'Post Title',
					'attr' => 'title',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR POST CATEGORY
	
	


// ---------------------------------------------------------------------------------------------------------------- SET UI FOR TESTIMONY CATEGORY SHORTCODE
	shortcode_ui_register_for_shortcode(
		'testimony',
		array(
			'label' => 'Choose Text Testimony',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'Post Category',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
				array(
					'label' => 'ID',
					'attr' => 'post_id',
					'type' => 'text',
				),
				array(
					'label' => 'Post Title',
					'attr' => 'title',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR TESTIMONY CATEGORY SHORTCODE
	
	
	
	
// ---------------------------------------------------------------------------------------------------------------- SET UI FOR BACKGROUND SHORTCODE
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
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR BACKGROUND SHORTCODE
	
	
	
	
// ---------------------------------------------------------------------------------------------------------------- SET UI FOR CAROUSEL PLUGIN SHORTCODE
	shortcode_ui_register_for_shortcode(
		'image-carousel',
		array(
			'label' => 'Carousel',
			'listItemImage' => 'dashicons-format-gallery',
			'attrs' => array(
				array(
					'label' => 'Carousel Category',
					'atts' => 'category',
					'type' => 'text'
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR CAROUSEL PLUGIN SHORTCODE




// ---------------------------------------------------------------------------------------------------------------- SET UI FOR VIDEO SHORTCODE
	shortcode_ui_register_for_shortcode(
		'video',
		array(
			'label' => 'Add Video',
			'listItemImage' => 'dashicons-video-alt3',
			'attrs' => array(
				array(
					'label' => 'video URL',
					'attr' => 'src',
					'type' => 'url',
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR VIDEO SHORTCODE

});
// ---------------------------------------------------------------------------------------------------------------- END SHORTCAKE ----------------------------------------------------------------------------------------------------------------

?>