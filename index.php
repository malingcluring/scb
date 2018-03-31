<?php get_header(); ?>

<?php

if( have_posts() ) : while( have_posts() ) : the_post();

	the_content();
	
	endwhile;
else : _e('sorry, no post match');
endif;

?>

<?php get_footer(); ?>