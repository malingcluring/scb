<?php get_header(); ?>
<div class="maps">
	
</div>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
<!--post thumbnails-->
<div class="bg-img-float">
	<div class="wrapper">
		<?php the_post_thumbnail(); ?>
	</div>
</div>

<!--news page-->
<div class="news-main-image">
	<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		} 
	?>
</div>

<!--blog content-->
<div class="entry-content">
	<h1 class="the-title">
		<?php echo str_replace(' ', '<span>', get_the_title()); ?>
	</h1>
	<?php the_content(); ?>
</div>
<?php
	endwhile;
	else : _e('sorry, no post match');
	?>
	
<?php endif; ?>

<!--====================================== CUSTOM POST ==========================-->
<div id="custom_post" class="container">
	<div class="row">
		<h2 class="custom-post-title-section">TESTIMONIALS</h2>
	</div>

    <div class="row">
        <div id="custom_video_testimony">
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
        </div>
    </div>
	
	<div class="row">
		<div id="custom_text_testimony" class="carousel slide" data-ride="carousel">
			<ul class="carousel-indicators">
			<?php 
				$loop = new WP_Query( array(
					'post_type' => 'post',
					'category_name' => 'text-testimony',
					'posts_per_page' => 3
				) );
				
				$isActive;
				$countr = 0;
				$indicators = '';
				
				while ( $loop->have_posts() ) : $loop->the_post();
					$isActive = ($countr==0?'active':'');
					$indicators.='<li data-target="#custom_text_testimony" data-slide-to="' .$countr. '" data-val="' .$countr. '" class="' .$isActive. '"></li>';
					$countr +=1;
				endwhile;
				
				$indicators.='</ul><div class="carousel-inner" role="listbox">';
				echo $indicators;
				$countr = 0;
				
				while ( $loop->have_posts() ) : $loop->the_post();
					$isActive = ($countr==0?'active':'');
					echo '<div class="item '.$isActive.'">';
						echo '<div class="user-img">';
							if(has_post_thumbnail() ) { the_post_thumbnail(); }
						echo '</div>';
						echo '<div class="carousel-caption">';
							echo '<blockquote>';
							the_content();
							echo '</blockquote>';
							echo '<h4>';
								the_title();
							echo '</h4>';
						echo '</div>';
					echo '</div>';
					$countr +=1;
				endwhile;
			?>
			
			<?php wp_reset_query(); ?>
			</div>
		</div>
		
	</div>
	
	<!--gallery-->
	 <div class="row">
        <div id="custom_gallery">
			<?php
				$id = 599;
				$p = get_page($id);
				echo apply_filters('the_content', $p->post_content);
				?>
        </div>
    </div>
</div>

<?php get_footer(); ?>