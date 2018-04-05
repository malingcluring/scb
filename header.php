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
	
	<header id="musthead" class="main-banner">
		<div id="carousel" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#carousel" data-slide-to="0" class="active"></li>
				<li data-target="#carousel" data-slide-to="1"></li>
				<li data-target="#carousel" data-slide-to="2"></li>
			</ol>
			
			<?php
				function url() {
					$url_dir = wp_upload_dir();
					$url_dir = $url_dir['baseurl'];
					return preg_replace('/^https?:/', '', $url_dir);
				};
				
			?>
			
			<div class="carousel-inner" role="listbox">
				<div class="item active">
					<img src="<?php echo (url(). '/2018/04/slide-1.png'); ?>" alt="">
					<div class="carousel-caption">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Mauris sollicitudin, mi et viverra auctor, quam orci posuere metus, quis commodo nisl magna porttitor nisi.
						Nulla condimentum nisi nec elementum condimentum.
						Phasellus velit eros, ornare sed tellus at, sollicitudin euismod purus.
					</div>
				</div>
				<div class="item">
					<img src="<?php echo (url(). '/2018/04/slide-2.png'); ?>" alt="">
					<div class="carousel-caption">
						In ac neque vitae purus faucibus commodo sed et mi.
						Vivamus malesuada laoreet placerat.
						Aliquam a porttitor velit.
						Morbi a aliquet est.
						Praesent gravida elit vitae arcu pretium, in dapibus ligula ultrices.
					</div>
				</div>
				<div class="item">
					<img src="<?php echo (url(). '/2018/04/slide-3.png'); ?>" alt="">
					<div class="carousel-caption">
						Maecenas congue, mauris at suscipit vulputate, magna felis lobortis dui, vitae tempor magna quam id enim.
						Vestibulum sagittis tortor non nisi cursus accumsan.
						Cras vitae ligula pulvinar, sollicitudin dui ac, ultricies lectus.
					</div>
				</div>
			</div>
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</header>
	
	
	
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
	
	
	
	