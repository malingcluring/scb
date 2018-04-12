<?php get_header(); ?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
		<div class="bg-img-float">
			<div class="wrapper">
				<?php the_post_thumbnail(); ?>
			</div>
		</div>

<div class="entry-content">
<?php
	the_content();	
	endwhile;
	else : _e('sorry, no post match');
	?>
</div>
<?php endif; ?>

<?php get_footer(); ?>