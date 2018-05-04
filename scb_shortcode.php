<?php


// ---------------------------------------------------------------------------------------------------------------- GENERAL FUNCTION
//------------------------- get_category()
	$categories = get_categories( $args );
	$arrcategories=array();
	foreach ( $categories as $category ) :
		$value = $category->term_id;
		$option_label = $category->name;
		$arrcategories[$value] = $option_label;
	endforeach;
	
	
	
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
// ---------------------------------------------------------------------------------------------------------------- END GENERAL FUNCTION






// ---------------------------------------------------------------------------------------------------------------- SHORTCODE ----------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------- CONTAINER SHORTCODE
	add_shortcode( 'column', 'scb_column' );
	function scb_column(  $atts , $content , $tag ) {
		$childs = explode('[/child]',$content);
		for($i=0;$i<count($childs);$i++){
			add_shortcode('child:'.$i, 'scb_col' );
		}
		
		$template = '';
		$id = $atts['id'];
		$width = 12/$atts['count'];
		for($i=0;$i<$atts['count'];$i++){
			$template .= '<div class="col-sm-'.$width.'">' . do_shortcode(trim($childs[$i])) .'</div>';
		}
		return '<div id="'.$id.'" class="container">
					<div class="row">'.
			$template
			.'</div>
				</div>';
	}
	function scb_col(  $atts , $content , $tag ) {
		return $content;
	}
// ---------------------------------------------------------------------------------------------------------------- END CONTAINER SHORTCODE


// ---------------------------------------------------------------------------------------------------------------- POST SHORTCODE
	add_shortcode('post', 'scb_post');
	function scb_post($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
			'count' => '',
			'title' => '',
		), $atts, 'post');
		
		$post_id = $atts['post_id'];
		$category = $atts['category'];
		$count = $atts['count'];
		$title = $atts['title'];
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'posts_per_page' 	=> $count,
			'cat'         		=> $category,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'paged' 			=> get_query_var('paged'),
			'nopaging' 			=> true,
			'post_parent' 		=> $parent,
		);
	
		$display = '';
		$temp = '';
		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();
				
				$display .= '<div class="post-display-item">'
							. '<div class="img-feature">' .get_the_post_thumbnail($id, 'thumbnail'). '</div>'
							. '<div class="post-content">'
							. 	'<h3>' .get_the_title(). '</h3>'
							. 	'<p>' .wp_trim_words(preg_replace("/<img[^>]+\>/i", '', get_the_content()), 12, '...'). ' <a href="' .get_permalink(). '" class="btn btn-default btn-block">more</a></p>'
							. '</div></div>';
			}
		}
		wp_reset_postdata();
		$temp = '<div class="container"><div class="row"><h2>' .$title. '</h2>' .$display. '</div></div>';
		
		return '<div id="' .$post_id. '" class="display-posts">' .$temp. '</div>';
	}
	// ful post
	add_shortcode('post_full', 'scb_post_full');
	function scb_post_full($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
		), $atts, 'post_full');
		
		$post_id = $atts['post_id'];
		$category = $atts['category'];
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'cat'         		=> $category,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'paged' 			=> get_query_var('paged'),
			'nopaging' 			=> true,
			'post_parent' 		=> $parent,
		);
	
		$display = '';
		$temp = '';
		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();
				
				$display .= '<div class="post-display-item">'
							. '<div class="post-content">'
							. 	'<h3><a href="' .get_permalink(). '">' .get_the_title(). '</a></h3>'
							. 	'<p>' .do_shortcode(get_the_content(), $content). '</p>'
							. '</div></div>';
			}
		}
		wp_reset_postdata();
		
		return '<div id="' .$post_id. '" class="display-posts">' .$display. '</div>';
	}
	
	// thumbnail post only
	add_shortcode('post_thumb', 'scb_post_thumb');
	function scb_post_thumb($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
			'title' => false,
		), $atts, 'post_thumb');
		
		$post_id = ($atts['post_id']) ? ' id="' .$atts['post_id']. '"' : '';
		$category = $atts['category'];
		$title = ($atts['title']) ? '<h2>' .$atts['title']. '</h2>' : '';
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'cat'         		=> $category,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'paged' 			=> get_query_var('paged'),
			'nopaging' 			=> true,
			'post_parent' 		=> $parent,
		);
	
		$display = '';
		$temp = '';
		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();
				
				$display .= '<div class="post-display-item">'
							. '<div class="post-content">'
							. 	'<div class="img-feature">' .get_the_post_thumbnail($id, 'thumbnail'). '</div>'
							. 	'<p class="consultant-name"><a href="' .get_permalink(). '">' .get_the_title(). '</a></p>'
							. '</div></div>';
			}
		}
		wp_reset_postdata();
		
		return '<div ' .$post_id. ' class="display-posts">' .$title.$display. '</div>';
	}
// ---------------------------------------------------------------------------------------------------------------- END POST SHORTCODE


// ---------------------------------------------------------------------------------------------------------------- TESTIMONY SHORTCODE
	add_shortcode('testimony', 'scb_testimony');
	function scb_testimony($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
			'title' => '',
		), $atts, 'testimony');
		
		$post_id = $atts['post_id'];
		$category = $atts['category'];
		$title = $atts['title'];
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'cat'         		=> $category,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'paged' 			=> get_query_var('paged'),
			'nopaging' 			=> true,
			'post_parent' 		=> $parent,
		);
	
		$display = '';
		$temp = '';
		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();
				
				
				$display .=  '<div class="col-sm-4"><blockquote class="post-content">'
							. 	'<p>' .preg_replace("/<img[^>]+\>/i", '', get_the_content()). ' <a href="' .get_permalink(). '" class="btn btn-default btn-block">more</a></p>'
							. '</blockquote></div>';
			}
		}
		wp_reset_postdata();
		$temp = '<div class="container"><div class="row"><h2>' .$title. '</h2>' .$display. '</div></div>';
		
		return '<div id="' .$post_id. '" class="display-posts">' .$temp. '</div>';
	}
// ---------------------------------------------------------------------------------------------------------------- END TESTIMONY SHORTCODE


// ---------------------------------------------------------------------------------------------------------------- BG IMG SHORTCODE
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
// ---------------------------------------------------------------------------------------------------------------- END BG IMG SHORTCODE
	
	
// ---------------------------------------------------------------------------------------------------------------- REWRITE IMAGE GALLERY
// ---------------------------------------------------------------------------------------------------------------- REMOVE ORIGINAL BUILD GALLERY SHORTCODER
//remove_shortcode('gallery', 'gallery_shortcode');

// ---------------------------------------------------------------------------------------------------------------- REPLACE WITH SCB GALLERY SHORTCODE
//add_shortcode('gallery', 'scb_gallery');
//function scb_gallery($attr) {
//    $post = get_post();
//
//    static $instance = 0;
//    $instance++;
//
//    if (!empty($attr['ids'])) {
//        if (empty($attr['orderby'])) {
//            $attr['orderby'] = 'post__in';
//        }
//        $attr['include'] = $attr['ids'];
//    }
//
//    $output = apply_filters('post_gallery', '', $attr);
//
//    if ($output != '') {
//        return $output;
//    }
//
//    if (isset($attr['orderby'])) {
//        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
//        if (!$attr['orderby']) {
//            unset($attr['orderby']);
//        }
//    }
//
//    extract(shortcode_atts(array(
//        'order' => 'ASC',
//        'orderby' => 'menu_order ID',
//        'id' => $post->ID,
//        'itemtag' => '',
//        'icontag' => '',
//        'captiontag' => '',
//        'columns' => 3,
//        'size' => 'thumbnail',
//        'include' => '',
//        'link' => '',
//        'exclude' => ''
//                    ), $attr));
//
//    $id = intval($id);
//
//    if ($order === 'RAND') {
//        $orderby = 'none';
//    }
//
//    if (!empty($include)) {
//        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
//
//        $attachments = array();
//        foreach ($_attachments as $key => $val) {
//            $attachments[$val->ID] = $_attachments[$key];
//        }
//    } elseif (!empty($exclude)) {
//        $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
//    } else {
//        $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
//    }
//
//    if (empty($attachments)) {
//        return '';
//    }
//
//    if (is_feed()) {
//        $output = "\n";
//        foreach ($attachments as $att_id => $attachment) {
//            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
//        }
//        return $output;
//    }
//
//    //Bootstrap Output Begins Here
//    //Bootstrap needs a unique carousel id to work properly. Because I'm only using one gallery per post and showing them on an archive page, this uses the $post->ID to allow for multiple galleries on the same page.
//
//    $output .= '<div id="carousel-' . $post->ID . '" class="carousel slide" data-ride="carousel">'; 
//    $output .= '<!-- Indicators -->';
//    $output .= '<ol class="carousel-indicators">';
//
//    //Automatically generate the correct number of slide indicators and set the first one to have be class="active".
//    $indicatorcount = 0;
//    foreach ($attachments as $id => $attachment) {
//        if ($indicatorcount == 1) {
//            $output .= '<li data-target="#carousel-' . $post->ID . '" data-slide-to="' . $indicatorcount . '" class="active"></li>';
//        } else {
//            $output .= '<li data-target="#carousel-' . $post->ID . '" data-slide-to="' . $indicatorcount . '"></li>';
//        }
//        $indicatorcount++;
//    }
//
//    $output .= '</ol>';
//    $output .= '<!-- Wrapper for slides -->';
//    $output .= '<div class="carousel-inner">';
//    $i = 0;
//
//    //Begin counting slides to set the first one as the active class
//    $slidecount = 1;
//    foreach ($attachments as $id => $attachment) {
//        $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
//
//        if ($slidecount == 1) {
//            $output .= '<div class="item active">';
//        } else {
//            $output .= '<div class="item">';
//        }
//
//        $image_src_url = wp_get_attachment_image_src($id, $size);
//        $output .= '<img src="' . $image_src_url[0] . '">';
//        $output .= '    </div>';
//
//
//        if (trim($attachment->post_excerpt)) {
//            $output .= '<div class="caption">' . wptexturize($attachment->post_excerpt) . '</div>';
//        }
//
//        $slidecount++;
//    }
//
//    $output .= '</div>';
//    $output .= '<!-- Controls -->';
//    $output .= '<a class="left carousel-control" href="#carousel-' . $post->ID . '" data-slide="prev">';
//    $output .= '<span class="glyphicon glyphicon-chevron-left"></span>';
//    $output .= '</a>';
//    $output .= '<a class="right carousel-control" href="#carousel-' . $post->ID . '" data-slide="next">';
//    $output .= '<span class="glyphicon glyphicon-chevron-right"></span>';
//    $output .= '</a>';
//    $output .= '</div>';
//    $output .= '</dl>';
//    $output .= '</div>';
//
//    return $output;
//	
//}
// ---------------------------------------------------------------------------------------------------------------- END SCB GALLERY


// ---------------------------------------------------------------------------------------------------------------- LIGHTBOX SHORTCODE
	add_shortcode('lightbox', 'scb_lightbox');
	function scb_lightbox($atts, $content=null) {
		
		$atts = shortcode_atts(array(
			'ids' => false,
			'xclass' => false,
			'vdid' => 'test',
			'caption' => '',
		), $atts);
		
		$result = '';
		$id = ($atts['ids']) ? 'id="' .$atts['ids']. '"' : '';
		$class = ($atts['xclass']) ? $atts['xclass'] : '';
		$vidid = 'https://www.youtube.com/watch?v=' .$atts['vdid'];
		$vThumb = 'http://img.youtube.com/vi/' .$atts['vdid']. '/mqdefault.jpg';
		$caption = $atts['caption'];
		
		$result = '<div ' .$id. ' class="lightbox-gallery' .$class. '">'
					. 	'<a data-fancybox="gallery" href="' .$vidid. '" class="video fancybox.iframe" data-caption="' .$caption. '" data-width="800" data-height="600">'
					. 		'<img src="' .$vThumb. '">'
					. 	'</a>'
					. '</div>';
		
		return $result;
	}
	
	// shortcake lightbox
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
					'atts' => 'ids',
					'type' => 'text',
				),
				array(
					'label' => 'Add class',
					'atts' => 'xclass',
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
// ---------------------------------------------------------------------------------------------------------------- END LIGHTBOX SHORTCODE

// POST SELECT SHORTCODE
add_shortcode('post_select', 'scb_post_select');
function scb_post_select($atts) {
	
	return '<p>' .$content. '</p>';
}
// END POST SELECT SHORTCODE


// CARD SHORTCODE


add_shortcode('card', 'scb_card');
function card($atts, $content=null) {
	$atts = shortcode_atts(array(
		'ids' => '',
		'class' => '',
		'attachment' => '',
		'profil_name' => '',
	), $atts);
	$ids = ($atts['ids']) ? 'id="' .$atts['ids']. '"' : '';
	return '<div' .$ids. 'class="card ' .$atts['class']. '">'
			. $atts['attachment']
			. '<div class="card-container">'
			. '<h4><b>John Doe</b></h4> '
			. '<p>Architect & Engineer</p> '
			. '</div>'
			. '</div>';
}
// END CARD SHORTCODE

// container shortcode
add_shortcode('container', 'scb_container');
function scb_container($atts, $content=null) {
	$atts = shortcode_atts(array(
		'ids' => false,
	), $atts);
	$id = ($atts['ids']) ? 'id="' .$atts['ids']. '"' : '';
	return '<div ' .$id. '" class="container"><div class="row">' .do_shortcode($content). '</div></div>';
}
add_shortcode('colspan', 'scb_colspan');
function scb_colspan($atts, $content=null) {
	$atts = shortcode_atts(array(
		'ids' => false,
		'size' => '',
		'xclass' => '',
	), $atts);
	$id = ($atts['ids']) ? 'id="' .$atts['ids']. '"' : '';
	return '<div ' .$id. ' class="' .$atts['xclass']. ' col-sm-' .$atts['size']. '">' .do_shortcode($content). '</div>';
}
// container shortcode


// Font Awesome
add_shortcode('icon', 'scb_icon');
function scb_icon($atts, $content=null) {
	
	$atts = shortcode_atts(array(
		'name' => '',
		'text' => false,
		'link' => false,
	), $atts);
	$text = ($atts['text']) ? '<span>' .$atts['text']. '</span></a>' : '';
	$display = ($atts['link']) ? '<a href="' .$atts['link']. '" target="_blank"><span class="icon"><i class="fa fa-' .$atts['name']. '"></i></span>' .$text. '</a>' : '<span class="icon"><i class="fa fa-' .$atts['name']. '"></i></span>' .$text ;
	
	return $display;
}
// end FA



// edited carousel

// Carousel
function scb_carousel_testimony(  $atts , $content , $tag ) {
	
	$atts = shortcode_atts(array(
		'id' => '',
		'class' => '',
	), $atts);
    
	$indicators = '';
	$slide = '';
	$descCont = explode('[desc', $content);
	for($i=0; $i<count($descCont)-1; $i++) {
		$carCaps = explode('[desc:'.($i +1).']', $content);
		$carCapsTxt = explode('[/desc]',$carCaps[1]);
		$active = '';
		
		$data_slide = ($i + 1);
		if($i==0) $active = 'active';
		
		$indicators .= '<li data-target="#'.$atts['id'].'" data-slide-to="'.$i.'" data-val="'.$data_slide.'" class="'.$active.'"></li>';
		$slide .= '<div class="item '. $active.'"><div class="carousel-caption">'.$carCapsTxt[0].'</div></div>';
	}

	return '<div id="'.$atts['id'].'" class="carousel slide ' .$atts['class']. '" data-ride="carousel">'
			. '<ul class="carousel-indicators">'.$indicators.'</ul>'
			. '<div class="carousel-inner" role="listbox">'.$slide.'</div>'
			. '</div>';
}
add_shortcode( 'carousel_testimony', 'scb_carousel_testimony' );

// end edited carousel
// ---------------------------------------------------------------------------------------------------------------- END SHORTCODE
	

	
	
	
	
	
// SHORTCAKE
// ---------------------------------------------------------------------------------------------------------------- SET UI FOR POST CATEGORY
	// ---------------------------------------------------------------------------------------------------------------- SET UI FOR FULL POST CATEGORY
	shortcode_ui_register_for_shortcode(
		'post_full',
		array(
			'label' => 'POST FULL',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'Post Full',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
				array(
					'label' => 'ID',
					'attr' => 'post_id',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
	
	// ---------------------------------------------------------------------------------------------------------------- SET UI FOR THUMBNAIL POST ONLY
	shortcode_ui_register_for_shortcode(
		'post_thumb',
		array(
			'label' => 'POST THUMBNAIL ONLY',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'TITLE',
					'attr' => 'title',
					'type' => 'text',
				),
				array(
					'label' => 'Post Thumbnail',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
				array(
					'label' => 'ID',
					'attr' => 'post_id',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
	
	// ---------------------------------------------------------------------------------------------------------------- SET UI FOR POST CATEGORY
	shortcode_ui_register_for_shortcode(
		'post',
		array(
			'label' => 'TEXT POST WITH THUMBNAIL',
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
// ---------------------------------------------------------------------------------------------------------------- END SET UI FOR POST CATEGORY



// ---------------------------------------------------------------------------------------------------------------- FONT AWESOME
	shortcode_ui_register_for_shortcode(
		'icon',
		array(
			'label' => 'ADD ICON',
			'listItemImage' => 'dashicons-star-filled',
			'attrs' => array(
				array(
					'label' => 'Icon Name',
					'atts' => 'name',
					'type' => 'text',
				),
				array(
					'label' => 'Text Content',
					'atts' => 'text',
					'type' => 'text',
				),
				array(
					'label' => 'URL',
					'atts' => 'link',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
				
// ---------------------------------------------------------------------------------------------------------------- END FONT AWESOME



// TIMELINE SHORTCODE
add_shortcode('timeline', 'scb_timeline');
function scb_timeline($attr, $content=null) {
	$atts = shortcode_atts(array(
		'category' => '',
	), $atts, 'timeline');
	
	$category = $attr['cat'];
	
	$args = array(
		'post_type' 		=> 'post',
		'orderby' 			=> 'date',
		'order' 			=> 'DESC',
		'cat'         		=> $category,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'paged' 			=> get_query_var('paged'),
		'nopaging' 			=> true,
		'post_parent' 		=> $parent,
	);
	$return_string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ){
		while( $query->have_posts() ){
			$query->the_post();
			$return_string .= '<div class="tl-container"><div class="tl-content">'
							   . '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>'
							   . '<div class="tl-story">' .wp_trim_words(get_the_content(), 20, '...'). '</div>'
							   . '</div></div>';
		}
	}
	wp_reset_query();
	return '<h2>Our Achievement</h2> ' .$return_string;
}
shortcode_ui_register_for_shortcode(
	'timeline',
	array(
		'label' => 'TIMELINE',
		'listItemImage' => 'dashicons-media-default',
		'attrs' => array(
			array(
				'label' => 'Add The Timeline',
				'attr' => 'cat',
				'type' => 'select',
				'options' => $arrcategories,
			),
		),
		'post_type' => array( 'post', 'page' ),
	)		
);




// =========================== ROW
add_shortcode('row', 'scb_row');
function scb_row($atts, $content=null) {
	$ats = shortcode_atts(array(
		'id' => false,
		'class' => false,
	), $atts, 'row');
	
	$id= ($atts['id']) ? ' id="' .$atts['id']. '"' : '';
	
	return '<div' .$id. ' class="row ' .$atts['class']. '">' .do_shortcode($content). '</div>';
}


// -------------------------------- NEWS SCORTCODE
	add_shortcode('post_news', 'scb_post_news');
	function scb_post_news($atts, $content=null){
		$atts = shortcode_atts(array(
			'cat' => ''
		), $atts, 'post_news');
		
		$category = $atts['cat'];
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'cat'         		=> $category,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'paged' 			=> get_query_var('paged'),
			'nopaging' 			=> true,
			'post_parent' 		=> $parent,
		);
	
		$news = '';
		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();
				
				$news .= '<div class="news-content">'
						. '<h3><a href="' .get_permalink(). '">' .get_the_title(). '</a></h3>'
						.do_shortcode(get_the_content(), $content).'</div>';
			}
		}
		wp_reset_postdata();
		
		return $news;
	}
	
	shortcode_ui_register_for_shortcode(
		'post_news',
		array(
			'label' => 'NEWS',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'Add THE HOTTEST NEWS',
					'attr' => 'cat',
					'type' => 'select',
					'options' => $arrcategories,
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
	
	
	
// ----------------------------------------------------------------- SHORTCODE PAGE CONTACT US
// -------------------------------------------- GOOGLE MAPS
	add_shortcode('google_map', 'scb_google_map');
	function scb_google_map($atts) {
		//$atts = shortcode_atts(array(
		//	'lat' => '',
		//	'lng' => ''
		//), $atts, 'google_map');
		return '<iframe id="google_map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3098032638068!2d106.76153731538157!3d-6.353925995402027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f79470fbf061%3A0x16db80805011ad07!2sPondok+Cabe+Golf+%26+Country+Club!5e0!3m2!1sen!2sid!4v1524024796248" frameborder="0" style="border:0" allowfullscreen></iframe>';
	}
	shortcode_ui_register_for_shortcode(
		'google_map',
		array(
			'label' => 'GOOGLE MAPS',
			'listItemImage' => 'dashicons-location',
			'post_type' => array( 'post', 'page' ),
		)		
	);
	
// -------------------------------------------- CONTACT DETAIL
	add_shortcode('contact', 'scb_contact');
	function scb_contact($atts, $content=null) {
		$atts = shortcode_atts(array(
			'name' => '',
			'building' => '',
			'addrs' => '',
			'email' => '',
			'phone' => ''
		), $atts, 'contact');
		
		$name = $atts['name'];
		$building = ($atts['building']) ? $atts['building']. ' Building <br />' : '';
		$addrs = ($atts['addrs']) ? $atts['addrs']. '<br />' : '';
		$email = $atts['email'];
		$phone = $atts['phone'];
		
		
		return '<iframe id="google_map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3098032638068!2d106.76153731538157!3d-6.353925995402027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f79470fbf061%3A0x16db80805011ad07!2sPondok+Cabe+Golf+%26+Country+Club!5e0!3m2!1sen!2sid!4v1524024796248" frameborder="0" style="border:0" allowfullscreen></iframe>
				 <div class="contact-info">
					<div class="addrs">
						<address>
							<strong>' .$name. '</strong><br />'
							.$building.$addrs.
							'Indonesia <br />
							<span class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></span> ' .$email. '<br />
							<span class="icon"><i class="fa fa-phone" aria-hidden="true"></i></span>' .$phone.
						'</address>
					</div>
					<div class="socials">
						<ul class="socials list-inline">
							<li>
								<a href="facebook.com" class="social facebook">
									<span class="icon"><i class="fa fa-facebook"></i></span> 
									Facebook
								</a>
							</li>
							<li>
								<a href="instagram.com" class="social instagram">
									<span class="icon"><i class="fa fa-instagram"></i></span>
									Instagram
								</a>
							</li>
							<li>
								<a href="youtube.com" class="social youtube">
									<span class="icon"><i class="fa fa-youtube"></i></span>
									Youtube
								</a>
							</li>
						</ul>
					</div>
				</div>';
	}
	shortcode_ui_register_for_shortcode(
		'contact',
		array(
			'label' => 'ADD CONTACT INFO',
			'listItemImage' => 'dashicons-location',
			'attrs' => array(
				array(
					'label' => 'Corporate Name',
					'attr' => 'name',
					'type' => 'text',
				),
				array(
					'label' => 'Building Name',
					'attr' => 'building',
					'type' => 'text',
				),
				array(
					'label' => 'Complete Address',
					'description' => 'Street, city, state, postal code, etc',
					'attr' => 'addrs',
					'type' => 'textarea',
				),
				array(
					'label' => 'Email',
					'description' => 'your.email@homepage.com',
					'attr' => 'email',
					'type' => 'email',
				),
				array(
					'label' => 'Phone',
					'attr' => 'phone',
					'type' => 'text',
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
?>