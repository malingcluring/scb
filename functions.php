<?php


// ------------------------------------------------------------------ REGISTER SCRIPTS ------------------------------------------------------------------
// Register Style
function scb_styles() {

	wp_register_style( 'bootstrap', get_template_directory_uri(). '/vendor/css/bootstrap.min.css', false, false );
	wp_enqueue_style( 'bootstrap' );
	
	$dependencies = array('bootstrap');
	
	wp_register_style('fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', $dependencies, false);
	wp_enqueue_style('fa');
	
	wp_register_style( 'lightbox', get_template_directory_uri(). '/vendor/css/jquery.fancybox.min.css', false, false );
	wp_enqueue_style( 'lightbox' );

	wp_register_style( 'custom', get_template_directory_uri(). '/scb_theme.css', $dependencies, false );
	wp_enqueue_style( 'custom' );
	
	wp_register_style('roboto', 'https://fonts.googleapis.com/css?family=Hammersmith+One|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab');
	wp_enqueue_style('roboto');
	
	wp_enqueue_style( 'style', get_stylesheet_uri() );

}
add_action( 'wp_enqueue_scripts', 'scb_styles' );

// Register Script
function scb_scripts() {
	
	wp_deregister_script('jquery');

	wp_register_script( 'jquery', get_template_directory_uri() . '/vendor/js/jquery-3.3.1.min.js', false, false, false );
	wp_enqueue_script( 'jquery' );

	wp_register_script( 'bootstrap', get_template_directory_uri() . '/vendor/js/bootstrap.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'bootstrap' );
	
	wp_register_script( 'lightbox', get_template_directory_uri() . '/vendor/js/jquery.fancybox.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'lightbox' );

	wp_register_script( 'scb_script', get_template_directory_uri() . '/scb_script.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'scb_script' );

}
add_action( 'wp_enqueue_scripts', 'scb_scripts' );
//-------------------------------------------------------------------------------------------------------------------------------------------------------






// ------------------------------------------------------------------ REGISTER SIDEBAR WIDGET ------------------------------------------------------------------
add_action('widgets_init', 'scb_sidebar_widget');
function scb_sidebar_widget() {
//	$pages = get_pages();
//    foreach ( $pages as $page ) {
		register_sidebar(array(
			'id'            => 'primary-' . $page->ID,
			'name'          => __( 'Primary Sidebar' ),
			'description'   => __( 'Widget for page: ' . $page->post_title ),
			'before_widget' => '<div id="%1$s" class="col-sm-4 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
	//}
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------






//--------------------------------------------------------------------REQUIRED FILE---------------------------------------------------------------------
// Register Custom Navigation Walker
require_once get_template_directory() . '/wp-bootstrap-navwalker.php';


//shortcode
require_once get_template_directory(). '/scb_shortcode.php';
//-------------------------------------------------------------------------------------------------------------------------------------------------------






//--------------------------------------------------------------------CUSTOM THEME---------------------------------------------------------------------
if(!function_exists('scbtheme_setup')) :

	function scbtheme_setup(){
		
		//register menu
		register_nav_menus(array(
			'header'	=> __('Header Menu', 'scbtheme'),
			'footer'	=> __('Footer Menu', 'scbtheme')
		));
		
		//title TAG
		add_theme_support( 'title-tag' );
		
		//post thumbnail
		add_theme_support('post-thumbnails', array('page', 'post'));
	}
	
	
endif;
add_action('after_setup_theme', 'scbtheme_setup');
//-------------------------------------------------------------------------------------------------------------------------------------------------------


//---------------------------------------------------------ADD / REMOVE FILTER---------------------------------------------------------

//REMOVE WH
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}

// add video embed responsive filter
function bootstrap_wrap_oembed( $html ){
  $html = preg_replace( '/(width|height)="\d*"\s/', "", $html ); // Strip width and height #1
  return'<div class="embed-responsive embed-responsive-16by9">'.$html.'</div>'; // Wrap in div element and return #3 and #4
}
add_filter( 'embed_oembed_html','bootstrap_wrap_oembed',10,1);




//---------------------------------------------------------REMOVE PLUGIN---------------------------------------------------------

//REMOVE EMOJICONS
function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

add_filter( 'emoji_svg_url', '__return_false' );

//---------------------------------------------------------


//---------------------------------------------------------
//Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post -> post_type . '-' . $post -> post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
//--------------------------------------------------------



/////=====================================================================================////










?>