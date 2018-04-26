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






//========================================
// Tab Nav Bootstrap
function scb_tabx($atts, $content){
	$tabid='';
	$tabclass = $atts['class'];
	$tabmode = ' nav-tabs';
	$tablia = ''; $tabCont = '';
	
	if($atts['id'] !=''){
		$tabid = ' id="'.$atts['id'].'"'; 
	}
	
	if($atts['mode'] !=''){
		$tabmode = ' nav-'.$atts['mode']; 
	}
	
	$menu = explode('[/tab_content]', $content);
	$tabTitle='';
	
	for($i=0; $i<count($menu)-1;$i++){
		$tabTitle = explode(':',explode(']',$menu[$i])[0])[1];
		add_shortcode('content:'.str_replace(' ','',$tabTitle), 'tabMenuCont_sc');
	}
	
	for($i=0; $i<count($menu)-1;$i++){
		$tabTitle = explode(':',explode(']',$menu[$i])[0])[1];
		$tabSCName = explode(']',$menu[$i])[0];
		$renscName = '[tab_content:'.str_replace(' ','',$tabTitle);
		$scName = str_replace($tabSCName, $renscName ,$menu[$i]);
		$tablia .= '<li role="presentation" '.($i==0?'class="active"':'').'><a href="#dv'.$attrs['id'].'_'.$i.'" data-toggle="tab" role="tab" aria-expanded="true">'.$tabTitle.'</a></li>';
		
		$tabCont .= '<div id="dv'.$attrs['id'].'_'.$i.'" class="tab-pane '.($i==0?'active':'').'" >'.do_shortcode(trim($scName)).'</div>';
	}
	
	$retVal = '<div'.$tabid.'><ul class="nav'.$tabmode.'">'.$tablia.'</ul><div class="tab-content">'.$tabCont.'</div></div>';
	return $retVal;
}
add_shortcode('tabx', 'scb_tabx');

//--------------------NAV TAB
/*--------------------------------------------------------------------------------------
		*
		* bs_tabs
		*
		* @author Filip Stefansson
		* @since 1.0
		* Modified by TwItCh twitch@designweapon.com
		* Now acts a whole nav/tab/pill shortcode solution!
		*-------------------------------------------------------------------------------------*/
	add_shortcode('tabs', 'scb_tabs')
	function scb_tabs( $atts, $content = null ) {
		if( isset( $GLOBALS['tabs_count'] ) )
			$GLOBALS['tabs_count']++;
		else
			$GLOBALS['tabs_count'] = 0;
		$GLOBALS['tabs_default_count'] = 0;
		$atts = apply_filters('bs_tabs_atts',$atts);
		$atts = shortcode_atts( array(
				"type"    => false,
				"xclass"  => false,
				"data"    => false,
				"name"    => false,
		), $atts );
		$ul_class  = 'nav';
		$ul_class .= ( $atts['type'] )     ? ' nav-' . $atts['type'] : ' nav-tabs';
		$ul_class .= ( $atts['xclass'] )   ? ' ' . $atts['xclass'] : '';
		$div_class = 'tab-content';
		// If user defines name of group, use that for ID for tab history purposes
		if(isset($atts['name'])) {
			$id = $atts['name'];
		} else {
			$id = 'custom-tabs-' . $GLOBALS['tabs_count'];
		}
		$data_props = $this->parse_data_attributes( $atts['data'] );
		$atts_map = bs_attribute_map( $content );
		// Extract the tab titles for use in the tab widget.
		if ( $atts_map ) {
			$tabs = array();
			$GLOBALS['tabs_default_active'] = true;
			foreach( $atts_map as $check ) {
					if( !empty($check["tab"]["active"]) ) {
							$GLOBALS['tabs_default_active'] = false;
					}
			}
			$i = 0;
			foreach( $atts_map as $tab ) {
				$class  ='';
				$class .= ( !empty($tab["tab"]["active"]) || ($GLOBALS['tabs_default_active'] && $i == 0) ) ? 'active' : '';
				$class .= ( !empty($tab["tab"]["xclass"]) ) ? ' ' . esc_attr($tab["tab"]["xclass"]) : '';
				if(!isset($tab["tab"]["link"])) {
					$tab_id = 'custom-tab-' . $GLOBALS['tabs_count'] . '-' . md5( $tab["tab"]["title"] );
				} else {
					$tab_id = $tab["tab"]["link"];
				}
				$tabs[] = sprintf(
					'<li%s><a href="#%s" data-toggle="tab" >%s</a></li>',
					( !empty($class) ) ? ' class="' . $class . '"' : '',
					sanitize_html_class($tab_id),
					$tab["tab"]["title"]
				);
				$i++;
			}
		}
		$output = sprintf(
			'<ul class="%s" id="%s"%s>%s</ul><div class="%s">%s</div>',
			esc_attr( $ul_class ),
			sanitize_html_class( $id ),
			( $data_props ) ? ' ' . $data_props : '',
			( $tabs )  ? implode( $tabs ) : '',
			sanitize_html_class( $div_class ),
			do_shortcode( $content )
		);
		return apply_filters('bs_tabs', $output);
	}
	/*--------------------------------------------------------------------------------------
		*
		* bs_tab
		*
		* @author Filip Stefansson
		* @since 1.0
		*
		*-------------------------------------------------------------------------------------*/
	add_shortcode('tab', 'scb_tab')
	function scb_tab( $atts, $content = null ) {
		$atts = shortcode_atts( array(
				'title'   => false,
				'active'  => false,
				'fade'    => false,
				'xclass'  => false,
				'data'    => false,
				'link'    => false
		), $atts );
		if( $GLOBALS['tabs_default_active'] && $GLOBALS['tabs_default_count'] == 0 ) {
				$atts['active'] = true;
		}
		$GLOBALS['tabs_default_count']++;
		$class  = 'tab-pane';
		$class .= ( $atts['fade']   == 'true' )                            ? ' fade' : '';
		$class .= ( $atts['active'] == 'true' )                            ? ' active' : '';
		$class .= ( $atts['active'] == 'true' && $atts['fade'] == 'true' ) ? ' in' : '';
		$class .= ( $atts['xclass'] )                                      ? ' ' . $atts['xclass'] : '';
		if(!isset($atts['link']) || $atts['link'] == NULL) {
			$id = 'custom-tab-' . $GLOBALS['tabs_count'] . '-' . md5( $atts['title'] );
		} else {
			$id = $atts['link'];
		}
		$data_props = $this->parse_data_attributes( $atts['data'] );
		return sprintf(
			'<div id="%s" class="%s"%s>%s</div>',
			sanitize_html_class($id),
			esc_attr( trim($class) ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
//================= TAB	

	
	
	
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