<?php get_header(); ?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
<!--post thumbnails-->
<div class="bg-img-float">
	<div class="wrapper">
		<?php the_post_thumbnail(); ?>
	</div>
</div>


<!--blog content-->
<div class="entry-content">

<div class="container">
	<div class="row">
		<div class="the-title"><?php the_title(); ?></div>
	</div>
</div>
<?php the_content(); ?>
</div>
<?php
	endwhile;
	else : _e('sorry, no post match');
	?>
	
<?php endif; ?>

<?php get_footer(); ?>