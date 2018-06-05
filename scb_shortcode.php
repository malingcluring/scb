<?php


// ================================================================================================================== GENERAL FUNCTION
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
// ================================================================================================================== END GENERAL FUNCTION






// ================================================================================================================== SHORTCODE
// ================================================================================================================== CONTAINER SHORTCODE
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
// ================================================================================================================== END OF CONTAINER SHORTCODE






// ================================================================================================================== POST SHORTCODE
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
							. 	'<p>' .wp_trim_words(preg_replace("/<img[^>]+\>/i", '', get_the_content()), 14, '...'). ' <a href="' .get_permalink(). '" class="btn btn-default btn-block">more</a></p>'
							. '</div></div>';
			}
		}
		wp_reset_postdata();
		$temp = '<div class="container"><div class="row"><h2>' .$title. '</h2>' .$display. '</div></div>';
		
		return '<div id="' .$post_id. '" class="display-posts">' .$temp. '</div>';
	}
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
// ================================================================================================================== END OF POST SHORTCODE






// ================================================================================================================== POST FULL SHORTCODE
	add_shortcode('post_full', 'scb_post_full');
	function scb_post_full($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
		), $atts, 'post');
		
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
	shortcode_ui_register_for_shortcode(
		'post_full',
		array(
			'label' => 'Full Post Story',
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
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ================================================================================================================== END POST FULL SHORTCODE
// ================================================================================================================== END POST SHORTCODE





// ================================================================================================================== TESTIMONY SHORTCODE
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
// ================================================================================================================== END OF TESTIMONY SHORTCODE






// ================================================================================================================== BG IMG SHORTCODE
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
// ================================================================================================================== END OF BG IMG SHORTCODE
	
	



// ================================================================================================================== LIGHTBOX SHORTCODE
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
// ================================================================================================================== END OF LIGHTBOX SHORTCODE





// ================================================================================================================== POST SELECT SHORTCODE
add_shortcode('post_select', 'scb_post_select');
function scb_post_select($atts) {
	
	return '<p>' .$content. '</p>';
}
// ================================================================================================================== END OF POST SELECT SHORTCODE






// ================================================================================================================== CARD SHORTCODE
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
// ================================================================================================================== END CARD SHORTCODE






// ================================================================================================================== CONTAINER SHORTCODE 
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
// ================================================================================================================== END OF CONTAINER SHORTCODE





// ================================================================================================================== FONT AWESOME SHORTCODE
add_shortcode('icon', 'scb_icon');
function scb_icon($atts, $content=null) {
	
	$atts = shortcode_atts(array(
		'name' => '',
		'text' => '',
		'link' => '',
	), $atts);
	
	return '<a href="' .$atts['link']. '" target="_blank"><span class="icon">'
			. 	'<i class="fa fa-' .$atts['name']. '"></i>'
			. '</span>'
			. '<span>' .$atts['text']. '</span></a>';
}
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
// ================================================================================================================== END OF FONT AWESOME SHORTCODE





// ================================================================================================================== EDITED CAROUSEL
// ================================================================================================================== CAROUSEL
add_shortcode( 'carousel_testimony', 'scb_carousel_testimony' );
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
// ================================================================================================================== END OF CAROUSEL
// ================================================================================================================== END OF EDITED CAROUSEL





//  ================================================================================================================== SELECT POST NEW SHORTCODE
add_shortcode('post_cat', 'scb_post_cat');
function scb_post_cat($attr){
	$atts = array(
		'post' => '',
	);
	
	$attr['post'] = array_map(
		function( $post_id ) {
			return get_the_title( $post_id );
		},
		array_filter( array_map( 'absint', explode( ',', $attr['post'] ) ) )
	);
}
	shortcode_ui_register_for_shortcode(
		'post_cat',
		array(
			'label' => 'Post Category',
			'listItemImage' => 'dashicons-editor-quote',
			'attrs' => array(
				array(
					'label' => 'Select Post',
					'atts' => 'post',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'post' ),
					'multiple' => true,
				),
			),
			'post_type' => array( 'post', 'page' ),
		)
	);
// ================================================================================================================== add_action( 'register_shortcode_ui', 'shortcode_ui_dev_advanced_example' );
//  ================================================================================================================== END OF SELECT POST NEW SHORTCODE






// ================================================================================================================== TIMELINE SHORTCODE
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
								   //. '<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>'
								   . '<h4>'.get_the_title().'</h4>'
								   . '<div class="tl-story">' .wp_trim_words(get_the_content(), 20, '...'). '</div>'
								   . '</div></div>';
			}
		}
		wp_reset_query();
		return '<div id="timeline" class="col-sm-12"><h2>Our Achievement</h2> ' .$return_string. '</div>';
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

// ================================================================================================================== END OF TIMELINE SHORTCODE






// ================================================================================================================== POST THUMBNAIL ONLY SHORTCODE
	add_shortcode('post_thumb', 'scb_post_thumb');
	function scb_post_thumb($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
			'title' => false,
		), $atts, 'post');
		
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

	// pot thumb UI shortcake
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
// ================================================================================================================== END OF POST THUMBNAIL ONLY SHORTCODE






// ================================================================================================================== NEWS SCORTCODE
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
				
				$news .= '<div class="news-content">
							<div class="news-thumbnail">' .get_the_post_thumbnail($id, 'thumbnail'). '</div>
							<div class="news-detail">
								<h4 class="news-title">
									<a href="' .get_permalink(). '">' .get_the_title(). '</a>
								</h4>
								<div class="news-short">' .do_shortcode(preg_replace("/<img[^>]+\>/i", '', get_the_content()), $content). '</div>
								<a href="' .get_permalink(). '" class="btn btn-default">more <i class="fa fa-right-arrow"></i></a>
							</div>
						</div>';
			}
		}
		wp_reset_postdata();
		
		return '<h2 class="section-title">News Events</h2>
						<div class="col-md-offset-2 col-md-8 col-sm-12">
							<div class="news-list">'
								.$news.
							'</div>
                        </div>';
	}
	
	shortcode_ui_register_for_shortcode(
		'post_news',
		array(
			'label' => 'ADD POST LIST',
			'listItemImage' => 'dashicons-media-default',
			'attrs' => array(
				array(
					'label' => 'Choose Post Category',
					'attr' => 'cat',
					'type' => 'select',
					'options' => $arrcategories,
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ================================================================================================================== END OF NEWS SCORTCODE




 
// ================================================================================================================== PAGE INTRO SECTION
	add_shortcode('intro', 'scb_intro');
	function scb_intro($atts, $content=null){
		$atts = shortcode_atts(array(
			'category' => '',
			'post_id' => '',
		), $atts, 'intro');
		
		$post_id = $atts['post_id'];
		$category = $atts['category'];
		
		$args = array(
			'post_type' 		=> 'post',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'cat'         		=> $category,
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
		
		return '<div id="intro" class="col-sm-12"><div class="row"><div class="col-sm-6"><div id="' .$post_id. '" class="display-posts">' .$display. '</div></div></div></div>';
	}
	shortcode_ui_register_for_shortcode(
		'intro',
		array(
			'label' => 'ADD PAGE INTRO',
			'listItemImage' => 'dashicons-format-quote',
			'attrs' => array(
				array(
					'label' => 'Add Intro ID',
					'attr' => 'post_id',
					'type' => 'text',
				),
				array(
					'label' => 'Choose Intro Category Page',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
			),
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ================================================================================================================== END OF PAGE INTRO SECTION






// ================================================================================================================== NEW SHORTCODE FOR ABOUT US PAGE
	add_shortcode('profile', 'scb_profile');
	function scb_profile($content=null){
		$args = array(
			'post_type' 		=> 'post',
			'category_name'		=> 'profile',
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
		
		return '<div id="profile" class="col-sm-7"><div id="profile_1" class="display-posts">' .$display. '</div></div>';
	}

	add_shortcode('profile_card', 'scb_profile_card');
	function scb_profile_card($content=null){
		$args = array(
			'post_type' 		=> 'post',
			'category_name'		=> 'profile-card',
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
							. 	'<a href="' .get_permalink(). '"><h3>' .get_the_title(). '</h3></a>'
							. 	'<div class="profile-card-content">' .do_shortcode(get_the_content(), $content). '</div>'
							. '</div>';
			}
		}
		wp_reset_postdata();
		return '<div id="profile_intro" class="col-sm-5">' .$display. '</div>';
	}
	
	add_shortcode('about_profiles', 'scb_about_profiles');
	function scb_about_profiles(){
		return '<div id="about_profiles" class="col-sm-12"><div class="row"><h2 class="expertise text-center">Our Experts</h2>' .do_shortcode('[profile_card]'.'[profile]'). '</div></div>';
	}
	shortcode_ui_register_for_shortcode(
		'about_profiles',
		array(
			'label' => 'ADD ABOUT PROFILE INFO',
			'listItemImage' => 'dashicons-admin-users',
			'post_type' => array( 'post', 'page' ),
		)		
	);
// ================================================================================================================== END OF NEW SHORTCODE FOR ABOUT US PAGE






// ================================================================================================================== SHORTCODE PAGE CONTACT US
	// -------------------------------------------- GOOGLE MAPS SHORTCODE section
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
	
	// -------------------------------------------- CONTACT DETAIL SHORTCODE section
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
// ================================================================================================================== END OF SHORTCODE PAGE CONTACT US






// ================================================================================================================== SCB MODEL CORPORATE CULTURE PAGE
	add_shortcode('model', 'scb_model');
	function scb_model() {
		$args = array(
			'post_type' => 'post',
			'category_name' => 'corporate-culture-model',
			'posts_per_page' => 1
		);
		
		$query = new WP_Query($args);
		
		$content = '';
		if($query->have_posts()) {
			while($query->have_posts()) {
				$query->the_post();
				$content .= '<div class="col-sm-4 scb-model">
								<h3><a href="' .get_permalink(). '">' .get_the_title(). '</a></h3>
								<h4>how to build corporate</h4>
								<div class="model-thumb">' .get_the_post_thumbnail(). '</div>
							</div>
							<div class="scb-carousel col-sm-8">';
			}
		}
		
		return $content;
	}
	
	add_shortcode('corporate_culture_model', 'scb_corporate_culture_model');
	function scb_corporate_culture_model() {
		return '<div class="col-sm-12">
					<div class="row">
						' .do_shortcode('[model]'.'[image-carousel category="corporate-culture" orderby="page" order="ASC"]'). '</div>
					</div>
				</div>';
	}
	shortcode_ui_register_for_shortcode(
		'corporate_culture_model',
		array(
			'label' => 'SCB MODEL',
			'listItemImage' => 'dashicons-admin-settings',
			'post_type' => array( 'post', 'page' ),
		)
	);
// ================================================================================================================== END OF SCB MODEL CORPORATE CULTURE PAGE






// ================================================================================================================== TOP CONSULTANT SHORTCODE
	add_shortcode('top_consultant', 'scb_top_consultant');
	function scb_top_consultant($atts, $content) {
		$atts = shortcode_atts(array(
			'id' => '',
			'title' => '',
			'category' => ''
		), $atts, 'top_consultant');

		$id = ($atts['id']) ? ' id="' .$atts['id']. '"' : '';
		$title= ($atts['title']) ? '<h2 class="section-title">' .$atts['title']. '</h2>' : '';
		$category = $atts['category'];

		$post_args = array(
			'post_type' => 'post',
			'cat' => $category
		);

		$query = new wp_query($post_args);

		$post_content = '<div id="top_consultant" class="col-sm-12 top-consultant">
							' .$title. '
							<div ' .$id. ' class="display-posts">';

		if($query->have_posts()) {
			while($query->have_posts()) {
				$query->the_post();
				$post_content .= '<div class="post-display-item">
									<div class="post-content">
										<div class="img-feature">' .get_the_post_thumbnail(). '</div>
										<h5 class="consultant-name"><a href="' .get_permalink(). '">' .get_the_title(). '</a></h5>
									</div>
								</div>';
			}
		}

		return $post_content. '</div></div>';
	}
	shortcode_ui_register_for_shortcode(
		'top_consultant',
		array(
			'label' => 'TOP CONSULTANT',
			'listItemImage' => 'dashicons-edit',
			'attrs' => array(
				array(
					'label' => 'Enter an ID',
					'description' => 'ID is required',
					'attr' => 'id',
					'type' => 'text',
				),
				array(
					'label' => 'Add Section Title',
					'attr' => 'title',
					'type' => 'text',
				),
				array(
					'label' => 'Choose Post Category',
					'attr' => 'category',
					'type' => 'select',
					'options' => $arrcategories,
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ================================================================================================================== END OF TOP CONSULTANT SHORTCODE





// ================================================================================================================== EVENT SCHEDULE SHORTCODE FOR POST AND PAGE-SCHEDULE
// ============================================== ADD EVENT
	add_shortcode('event_schedule', 'scb_event_schedule');
	function scb_event_schedule($atts, $content=null, $tag) {
		$atts = shortcode_atts(array(
			'attachment' => '',
			'title' => '',
			'tagline' => 'Tagline',
			'desc' => 'Event Description',
			'venue_name' => 'Venue',
			'event_date' => 'Date Time',
			'event_url' => '',
			'wa_name' => 'Jayasaf',
			'wa_number' => '+62 896 2485 9082',
			'instagram_name' => 'Great.speaker',
			'instagram_url' => 'https://www.instagram.com/great.speaker/'
		), $atts, 'event_schedule');

		$attachment = $atts['attachment'];
		$title = ($atts['title']) ? '<h3 class="title">' .$atts['title']. '</h3>' : '';
		$tagline = ($atts['tagline']) ? '<div class="tagline">' .$atts['tagline']. '</div>' : '';
		$desc = ($atts['desc']) ? '<div class="description">' .$atts['desc']. '</div>' : '';
		$venue_name = $atts['venue_name'];
		$event_date = $atts['event_date'];
		$event_url = ($atts['event_url']) ? '<div class="event-url">' .$atts['event_url']. '</div>' : '';
		$wa_name = $atts['wa_name'];
		$wa_number = $atts['wa_number'];
		$inst_name = $atts['instagram_name'];
		$inst_url = $atts['instagram_url'];

		$images =& get_children( array (
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'post_mime_type' => 'image'
		));
		$images = wp_get_attachment_image( $attachment, 'medium' );

		$schedule_display = '<div class="event-schedule">
								<div class="event-banner">' .$images. '</div>
								<div class="event-description">
									' .$title.$tagline.$desc. '
								</div>
								<div class="event-venue">
									<div class="venue-name">' .$venue_name. '</div>
									<div class="venue-date-time">' .$event_date. '</div>
									' .$event_url. '
								</div>
								<div class="socials">
									<div class="whatsapp social">
										<span class="icon">
											<i class="fa fa-whatsapp"></i>
										</span>
										<span class="contact-person">
											<span class="name">' .$wa_name. '</span>
											<span class="phone">' .$wa_number. '</span>
										</span>
									</div>
									<div class="instagram social">
										<span class="icon">
											<i class="fa fa-instagram"></i>
										</span>
										<span class="contact-person">
											<span class="name">' .$inst_name. '</span>
											<span class="inst-url">' .$inst_url. '</span>
										</span>
									</div>
								</div>
							</div>';

		return $schedule_display;
	}
	shortcode_ui_register_for_shortcode(
		'event_schedule',
		array(
			'label' => 'ADD EVENT SCHEDULE',
			'listItemImage' => 'dashicons-pressthis',
			'attrs' => array(
				array(
					'label'       => 'Add Event Banner',
					'attr'        => 'attachment',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => esc_html__( 'Select Image' ),
					'frameTitle'  => esc_html__( 'Select Image' ),
				),
				array(
					'label' => 'Enter Event Title',
					'description' => 'Required',
					'attr' => 'title',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Event Venue',
					'description' => 'Required',
					'attr' => 'venue_name',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Event Date Time',
					'description' => 'Required',
					'attr' => 'event_date',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Event/Venue URL',
					'description' => 'Required',
					'attr' => 'event_url',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Event Tagline',
					'description' => 'or leave blank if event not have tagline',
					'attr' => 'tagline',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Event Description',
					'description' => 'or leave blank if event not have description',
					'attr' => 'desc',
					'type' => 'textarea',
				),
				array(
					'label' => 'Enter Person In Charge',
					'description' => 'Leave it blank for default PIC (Whatsapp name: Jayasaf)',
					'attr' => 'wa_name',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Contact for Person In Charge',
					'description' => 'Leave it blank for default PIC (Whatsapp number: +62 896 2485 9082)',
					'attr' => 'wa_number',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Instagram Account',
					'description' => 'Leave it blank for default Instagram account (great.speaker)',
					'attr' => 'instagram_name',
					'type' => 'text',
				),
				array(
					'label' => 'Enter Instagram URL',
					'description' => 'Leave it blank for default Instagram URL (https://www.instagram.com/great.speaker/)',
					'attr' => 'instagram_url',
					'type' => 'text',
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ============================================== END OF ADD EVENT

// ============================================== ADD EVENT CARD ON SCHEDULE PAGE
	add_shortcode('event_card', 'scb_event_card');
	function scb_event_card($atts, $content=null, $tag) {
		$atts = shortcode_atts(array(
			'post' => '',
			'id' => ''
		), $atts, 'event_card');
		$post = $atts['post'];

		$query_args = array(
			//'p' => $post
			'cat' => $post,
			'orderby' => 'date',
			'order' => 'ASC',
		);

		$query = new WP_Query( $query_args );
		while($query->have_posts()) {
			$query->the_post();
			$content = get_the_content();
			$link = get_the_permalink();
			$card .='<a href="' .$link. '">' .$content. '</a>';
		}
		
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);

		return '<div class="event-schedule-card">
					<div id="' .$atts['id']. '" class="card-item">
						<div class="month">' .$atts['id']. '</div>'
						.do_shortcode($card, $content).
					'</div>
				</div>';
	}
	shortcode_ui_register_for_shortcode(
		'event_card',
		array(
			'label' => 'ADD EVENT CARD',
			'listItemImage' => 'dashicons-exerpt-view',
			'attrs' => array(
				array(
					'label' => 'Choose Month Event',
					'attr' => 'post',
					'type' => 'select',
					'options' => $arrcategories,
				),
				array(
					'label' => 'Card Month ID',
					'attr' => 'id',
					'type' => 'text',
				),
			),
			'post_type' => array('post', 'page'),
		)
	);
// ============================================== END OF ADD EVENT CARD ON SCHEDULE PAGE
// ================================================================================================================== EVENT SCHEDULE SHORTCODE FOR POST AND PAGE-SCHEDULE





// ================================================================================================================== ALL CLIENT SHORTCODE
add_shortcode('client', 'scb_client');
function scb_client($content=null){
	$args = array(
		'post_type' 		=> 'post',
		'category_name'		=> 'client',
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
			
			$display .= '<li>' .get_the_title(). '</li>';
		}
	}
	wp_reset_postdata();
	
	return '<div id="client_all"><ul class="list-unstyled">' .$display. '</ul></div>';
}
shortcode_ui_register_for_shortcode(
	'client',
	array(
		'label' => 'ADD CLIENT',
		'listItemImage' => 'dashicons-exerpt-view',
		'post_type' => array('post', 'page'),
	)
);
// ================================================================================================================== END OF ALL CLIENT SHORTCODE






// ================================================================================================================== OUTBOUND CARD SHORTCODE
add_shortcode('outbound_card', 'scb_outbound_card');
function scb_outbound_card($atts) {
	$atts = shortcode_atts(array(
		'attachment' => '',
		'id' => '',
		'title' => '',
		'subtitle' => '',
		'definition' => '',
		'objective' => ''
	), $atts, 'outbound_card');
	
	$id = $atts['id'];
	$title = $atts['title'];
	$subtitle = ($atts['subtitle']) ? '<br /><small>' .$atts['subtitle']. '</small>' : '&nbsp;';
	$definition = $atts['definition'];
	$objective = $atts['objective'];
	
	$attachment = $atts['attachment'];
	$images =& get_children( array (
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'post_mime_type' => 'image'
		));
	$images = wp_get_attachment_image( $attachment, 'medium' );
	$section = array('_definition', '_objective');
	$definitionID = $id.$section[0];
	$objectiveID = $id.$section[1];
	
	return '<div class="outbound-box">
				' .$images. '
				<div id="' .$id. '" class="outbound-card">
					<h4>
						' .$title. '
						' .$subtitle. '
					</h4>
					<div class="tab-content">
						<div id="' .$definitionID. '" class="tab-pane fade in active">
							' .$definition. '
						</div>
						<div id="' .$objectiveID. '" class="tab-pane fade">
							' .$objective. '
						</div>
					</div>
					<ul class="nav nav-pills nav-justified">
						<li class="active"><a data-toggle="pill" href="#' .$definitionID. '">Definition</a></li>
						<li><a data-toggle="pill" href="#' .$objectiveID. '">Objective</a></li>
					</ul>
				</div>
			</div>';
}
shortcode_ui_register_for_shortcode(
	'outbound_card',
	array(
		'label' => 'ADD OUTBOUND CARD',
		'listItemImage' => 'dashicons-menu',
		'attrs' => array(
			array(
				'label'       => 'Add Event Banner',
				'attr'        => 'attachment',
				'type'        => 'attachment',
				'libraryType' => array( 'image' ),
				'addButton'   => esc_html__( 'Select Image' ),
				'frameTitle'  => esc_html__( 'Select Image' ),
			),
			array(
				'label' => 'Enter ID',
				'description' => 'ID is required',
				'attr' => 'id',
				'type' => 'text',
			),
			array(
				'label' => 'Enter Title',
				'description' => 'Title is required',
				'attr' => 'title',
				'type' => 'text',
			),
			array(
				'label' => 'Enter Subtitle',
				'attr' => 'subtitle',
				'type' => 'text',
			),
			array(
				'label' => 'Enter Definition',
				'description' => 'Definition is required',
				'attr' => 'definition',
				'type' => 'textarea',
			),
			array(
				'label' => 'Enter Objective',
				'description' => 'Objective is required',
				'attr' => 'objective',
				'type' => 'textarea',
			),
		),
		'post_type' => array('post', 'page'),
	)
);
// ================================================================================================================== END OF OUTBOUND CARD SHORTCODE






// ================================================================================================================== VIDEO TESTIMONY SCHORTCODE
add_shortcode('video_testimony', 'scb_video_testimony');
function scb_video_testimony($atts, $content=null){
	$atts = shortcode_atts(array(
		'category' => '',
		'post_id' => '',
		'title' => '',
	), $atts, 'video_testimony');
	
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
	$query = new WP_Query( $args );
	if( $query->have_posts() ){
		while( $query->have_posts() ){
			$query->the_post();
			$display .=  get_the_content();
		}
	}
	wp_reset_postdata();
	
	return '<div id="' .$post_id. '" class="video-testimony"><h2>' .$title. '</h2>' .$display. '</div>';
}

shortcode_ui_register_for_shortcode(
	'video_testimony',
	array(
		'label' => 'ADD VIDEO TESTIMONY',
		'listItemImage' => 'dashicons-video-alt3',
		'attrs' => array(
			array(
				'label' => 'Choose Post Category',
				'attr' => 'category',
				'type' => 'select',
				'options' => $arrcategories
			),
			array(
				'label' => 'Enter ID',
				'attr' => 'post_id',
				'type' => 'text'
			),
			array(
				'label' => 'Enter Section Title',
				'attr' => 'title',
				'type' => 'text'
			)
		),
		'post_type' => array('post', 'page')
	)
);
// ================================================================================================================== VIDEO TESTIMONY SCHORTCODE






// ================================================================================================================== END OF SHORTCODE
?>