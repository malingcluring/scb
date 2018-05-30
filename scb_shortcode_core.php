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


<div class="container">

    <div class="row">

        <div class="col-md-9">
            <div class="panel panel-default text-center">
                <?php $loop = new WP_Query( array( 'post_type' => 'items', 'posts_per_page' => 5 ) ); ?>                        

                        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <?php the_title();?>
                            <?php if(has_post_thumbnail() ) { the_post_thumbnail(); } ?>
                            <?php the_content();?>
                        <?php endwhile; ?>

                <?php wp_reset_query(); ?>                      
            </div>
        </div>

    </div>

</div>

//============ post by category (alternative)
<?php

	$category_name = (get_query_var('category_name')) ? get_query_var('category_name') : 'uncategorized';
	$posts_per_page = 3;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'category_name' => $category_name,
		'posts_per_page' => $posts_per_page,
		'paged' => $paged,
	);
	
	$arr_posts = new WP_Query($args);
	
	if($arr_posts->have_posts()) :
		while($arr_posts->have_posts()) :
			$arr_posts->the_post(); ?>
			<div id="post_id<?php the_ID(); ?>" <?php post_class(); ?> >
				<?php
				if(has_post_thumbnail()) : the_post_thumbnail();
				endif; ?>
				<h1 class="the-entry-title"><?php the_title(); ?></h1>
				<div class="the-entry-content"><?php the_excerpt(); ?></div>
			</div>
<?php
		endwhile;
		WP_pagenavi(array('query' => $arr_posts));
	endif;
?>



// ================================================ CLIENTS LOGO THUMBNAIL
<ul id="logo_thumb">
<?php
	
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'category_name' => 'client',
	);
	
	$arr_posts = new WP_Query($args);
	
	if($arr_posts->have_posts()) {
		while($arr_posts->have_posts()) {
			$arr_posts->the_post();
			if(has_post_thumbnail()) {
				?>
				<li class="client-logo-thumb">
                    <a href="<?php the_permalink(); ?>" class="thumb"><?php the_post_thumbnail(); ?></a>
                </li>
				<?php
				
			}
		}
	}
?>
</ul>

<h2 class="expertness">our experts</h2>
<div id="card" class="col-sm-5">
	<div id="profile_intro" class="display-posts">
		
	</div>
</div>

<?php
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
		
		return '<div id="profile" class="col-sm-7"><div class="display-posts">' .$display. '</div></div>';
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
							. '<div class="post-content">'
							. 	'<h3><a href="' .get_permalink(). '">' .get_the_title(). '</a></h3>'
							. 	'<p>' .do_shortcode(get_the_content(), $content). '</p>'
							. '</div></div>';
			}
		}
		wp_reset_postdata();
		
		return '<div id="card" class="col-sm-5"><div class="display-posts">' .$display. '</div></div>';
	}
	add_shordcode('about-profiles', 'scb_about_profiles');
	function scb_about_profiles(){
		return '<h2 class="expertise">Our Experts</h2>' .do_shortcode('[profile_card]'.'[profile]');
	}

?>

<!-- SCB MODEL SERVICE CULTURE PAGE STRUCTURE-->
<div id="service_culture_model" class="col-sm-12">
    <div class="row">
        <div class="col-sm-4">
			<div class="scb-model">
				<h4>how to build corporate</h4>
				<img src="" alt="">
			</div>
		</div>
		<div class="col-sm-8">
			
		</div>
    </div>
</div>
<?php
// =================== SCB MODEL SERVICE CULTURE PAGE
add_shortcode('model', 'scb_model');
function scb_model() {
	$args = array(
		'post_type' => 'post',
		'category_name' => 'service-culture-model',
		'posts_per_page' => 1
	);
	
	$query = new WP_Query($args);
	
	$content = '';
	if($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
			$content .= '<div class="col-sm-4">
							<div class="scb-model">
								<h3><a href="' .get_permalink(). '">' .get_the_title(). '</a></h3>
								<h4>how to build corporate</h4>
								<div class="model-thumb">' .the_post_thumbnail();. '</div>
							</div>
						</div>
						<div class="col-sm-8">';
		}
	}
	
	return $content;
}

add_shortcode('service_culture_model', 'scb_service_culture_model');
function scb_service_culture_model() {
	return '<div id="service_culture_model" class="col-sm-12">
				<div class="row">
					' .do_shortcode('[model]'.'[image-carousel category="corporate-culture" orderby="page" order="ASC"]'). '</div>
				</div>
			</div>';
}

?>

<!-- ================================= POST THUMBNAIL SHORTCODE ================================= -->
<div id="top_consultant" class="col-sm-12 top-consultant">
	<h2 class="section-title">1section_title</h2>
    <div id="{$id}" class="display-posts">
        
    </div>
</div>

<?php
add_shortcode('top_consultant', 'scb_top_consultant');
function scb_top_consultant($atts, $content) {
	$atts = shotcode_atts(array(
		'id' => '',
		'title' => '',
		'category' => ''
	), $atts, 'top_consultant');
	
	$id = ($atts['id']) ? ' id="' .$atts['id']. '"' : '';
	$title= ($atts['title']) ? '<h2 class="section-title">' .$atts['title']. '</h2>' : '';
	$category = $atts['category'];
	
	$post_args = array(
		'post_type' => 'post',
		'cat' => ''
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
									<div class="img-feature">' .the_post_thumbnail(). '</div>
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
		'label' => 'Add TOP CONSULTANT',
		'listItemImage' => 'fa-clipboard',
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


?>

// ==================  custom post shortcode
<?php
				$loop = new WP_Query( array(
					'post_type' => 'post',
					'category_name' => 'video-testimony',
					'posts_per_page' => 3
				) );
			?>                        

				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php the_content();?>
				<?php endwhile; ?>

			<?php wp_reset_query(); ?>
			
<?php

add_shortcode('fixed_custom_post', 'scb_fixed_custom_post');
function scb_fixed_custom_post($atts, $content=null, $tag) {
	$atts = shortcode_atts(array(
		'category_name' => '',
		'id' => '',
		'post_number' => ''
	), $atts, 'fixed_custom_post');
	$category = $atts['category_name'];
	$id = ($atts['id']) ? 'id="' .$atts['id']. '"' : '';
	$number = $atts['post_number'];
	
	$post_args = array(
		'post_type' => 'post',
		'posts_per_page' => $number,
		'category_name' => $category
	);
	$query = new wp_query($post_args);
	
	if($query->have_posts()) {
		$content = '';
		while($query-> hape_posts()) {
			$content .= the_content();
		}
	}
	
	return '<div ' .$id. 'class="testimony-custom-post">' .$content. '</div>';
}

?>


<!--// ============== event schedule=================-->
<div class="event-schedule">
	<div class="event-title">
		<h1>Event Title 1</h1>
	</div>
	<div class="event-description">
		<div class="tagline"></div>
		<div class="description"></div>	
	</div>
	<div class="event-venue">
		<div class="venue-name"></div>
		<div class="venue-date-time"></div>
		<div class="event-url"></div>
	</div>
	<div class="socials">
		<div class="whatsapp">
            <span class="icon">
                <i class="fa fa-whatsapp"></i>
            </span>
			<span class="contact-person">
				<span class="name"></span>
                <span class="phone"></span>
			</span>
        </div>
		<div class="instagram">
            <span class="icon">
                <i class="fa fa-instagram"></i>
            </span>
            <span class="contact-person">
				<span class="name"></span>
                <span class="phone"></span>
			</span>
        </div>
	</div>
</div>

<?php

add_shortcode('event_schedule', 'scb_event_schedule');
function scb_event_schedule($atts, $content=null, $tag) {
	$atts = shortcode_atts(array(
		'title' => 'Event Title',
		'tagline' => 'Tagline',
		'desc' => 'Event Description',
		'venue_name' => 'Venue',
		'event_date' => 'Date Time',
		'event_url' => 'Event URL',
		'wa_name' => 'Jayasaf',
		'wa_number' => '+62 896 2485 9082',
		'instagram_name' => 'Great.speaker',
		'instagram_url' = 'https://www.instagram.com/great.speaker/'
	), $atts, 'event_schedule');
	
	$title = $atts['title'];
	$tagline = ($atts['tagline']) ? '<div class="description">' .$atts['tagline']. '</div>' : '';
	$desc = ($atts['desc']) ? '<div class="tagline">' .$atts['desc']. '</div>' : '';
	$venue_name = $atts['venue_name'];
	$event_date = $atts['event_date'];
	$event_url = $atts['event_url'];
	$wa_name = $atts['wa_name'];
	$wa_number = $atts['wa_number'];
	$inst_name = $atts['instagram_name'];
	$inst_url = $atts['instagram_url'];
	
	$schedule_display = '<div class="event-schedule">
							<div class="event-title">
								<h1>' .$title. '</h1>
							</div>
							<div class="event-description">
								' .$tagline.$desc. '
							</div>
							<div class="event-venue">
								<div class="venue-name">' .$venue_name. '</div>
								<div class="venue-date-time">' .$event_date. '</div>
								<div class="event-url">' .$event_url. '</div>
							</div>
							<div class="socials">
								<div class="whatsapp">
									<span class="icon">
										<i class="fa fa-whatsapp"></i>
									</span>
									<span class="contact-person">
										<span class="name">' .$wa_name. '</span>
										<span class="phone">' .$wa_number. '</span>
									</span>
								</div>
								<div class="instagram">
									<span class="icon">
										<i class="fa fa-instagram"></i>
									</span>
									<span class="contact-person">
										<span class="name">' .$inst_name. '</span>
										<span class="phone">' .$inst_url. '</span>
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
		'listItemImage' => 'fa-clipboard',
		'attrs' => array(
			array(
				'label' => 'Enter Event Title',
				'description' => 'ID is required',
				'attr' => 'title',
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
				'label' => 'Enter Event Venue',
				'description' => 'Venue is required',
				'attr' => 'venue_name',
				'type' => 'text',
			),
			array(
				'label' => 'Enter Event Date Time',
				'description' => 'Event Date Time is required',
				'attr' => 'event_date',
				'type' => 'text',
			),
			array(
				'label' => 'Enter Event/Venue URL',
				'description' => 'Event/Venue URL is required',
				'attr' => 'event_url',
				'type' => 'text',
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

?>
<!--=========================== schedule card ==============================-->
<div class="schedule-card card-month">
	<div class="schedule-list month">
		<h2 class="month-label"></h2>
		<div class="date-time"></div>
        <div class="event-name"></div>
	</div>
</div>







 <!--================================ news list ================================= -->
 <div class="news-list">
    <h2 class="section-title">News Events</h2>
	<div class="news-content">
		<div class="news-thumbnail">.get_the_post_thumbnail().</div>
		<div class="news-detail">
			<h4 class="news-title">
				<a href="' .get_permalink(). '">' .get_the_title(). '</a>
			</h4>
			<div class="news-short">
				.do_shortcode(get_the_content(), $content).
			</div>
			<a href="' .get_permalink(). '" class="btn btn-default">more</a>
		</div>
	 </div>
 </div>

 <!--================================ outbound list ================================= -->
<div id="section1">
	<h4>
		Dynamic Pills <br />
		<small>Lorem ipsum dolor sit amet, consectetur </small>
	</h4>
	<ul class="nav nav-pills">
		<li class="active"><a data-toggle="pill" href="#menu1">Home</a></li>
		<li><a data-toggle="pill" href="#menu">Menu 1</a></li>
	</ul>
	<div class="tab-content">
		<div id="menu1" class="tab-pane fade in active">
			<h3>HOME</h3>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
		</div>
		<div id="menu2" class="tab-pane fade">
			<h3>Menu 1</h3>
			<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>
	</div>
</div>

<?php

add_shortcode('outbound_card', 'scb_outbound_card');
function scb_outbound_card($atts, $content=null) {
	$atts = shortcode_atts(array(
		'id' => '',
		'title' => '',
		'subtitle' => '',
		'definition' => '',
		'objective' => ''
	), $atts, 'outbound_card');
	
	$id = $atts['id'];
	$title = $atts['title'];
	$subtitle = ($atts['subtitle']) ? '<br /><small>' .$atts['subtitle']. '</small>' : '';
	$definition = $atts['definition'];
	$objective = $atts['objective'];
	
	return '<div id="outbound">
				<div id="' .$id. '" class="outbound-card">
					<h4>
						' .$title. '
						' .$subtitle. '
					</h4>
					<div class="tab-content">
						<div id="' .$id. '_definition" class="tab-pane fade in active">
							' .$definition. '
						</div>
						<div id="' .$id. '_objective" class="tab-pane fade">
							' .$objective. '
						</div>
					</div>
					<ul class="nav nav-pills">
						<li class="active"><a data-toggle="pill" href="#' .$id. '_definition">Definition</a></li>
						<li><a data-toggle="pill" href="#' .$id. '_objective">Objective</a></li>
					</ul>
				</div>
			</div>';
	
}
shortcode_ui_register_for_shortcode(
	'outbound_card',
	array(
		'label' => 'ADD OUTBOUND CARD',
		'listItemImage' => 'fa-clipboard',
		'attrs' => array(
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
?>




<!--lightbox ke-2-->
<?php

	add_shortcode('video_testimony', 'scb_video_testimony');
	function scb_testimony($atts, $content=null){
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
		$temp = '';
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
					'attr' => 'category'
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

?>



<ul class="list-inline text-center">
	li>div.
</ul>
