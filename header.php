<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<?php wp_head(); ?>
			
	
</head>

<body <?php body_class(); ?> >
	
	<div class="main-menu">
		<nav class="navbar navbar-fixed navbar-custom" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#header_menu">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand">
						<img src="<?php echo get_bloginfo('template_url') ?>/images/scb-logo.png" alt="scb-logo">
					</a>
				</div>
				<?php
					wp_nav_menu( array(
						'theme_location'    => 'header',
						'depth'             => 3,
						'container'         => 'div',
						'container_class'   => 'collapse navbar-collapse',
						'container_id'      => 'header_menu',
						'menu_class'        => 'nav navbar-nav navbar-right',
						'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
						'walker'            => new WP_Bootstrap_Navwalker()
					) );
				?>
				
			</div>
		</nav>
	</div>
	<header id="musthead">
		
	</header>
	
	
	
	