<?php get_header(); ?>

<!--clients-->
<div class="container">
	<div class="clients">
		<ul id="logo_thumb" class="list-inline">
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
	</div>
</div>


<!--maps contact us page only-->
<div class="maps"></div>



<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
<!--post thumbnails-->
<div class="bg-img-float">
	<div class="wrapper">
		<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail();
			} 
		?>
	</div>
</div>

<!--news page-->
<div class="news-main-image">
	<?php
		//if ( has_post_thumbnail() ) {
			//the_post_thumbnail();
		//} 
	?>
</div>

<!--blog content-->
<div class="entry-content">
	<h1 class="the-title"><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>
<?php
	endwhile;
	else : _e('sorry, no post match');
	?>
	
<?php endif; ?>



<!-- ====================================== CUSTOM NEWS POST ====================================== -->
<div class="container">
    <div class="row">
		<!--news page-->
		<div class="news-post-list">
			<h2 class="section-title">News Events</h2>
			<div class="col-md-offset-2 col-md-8 col-sm-12">
				<div class="news-list">
					<?php
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args = array(
						'post_type' => 'post',
						'category_name' => 'news',
						'post_per_page' => 10,
						'paged' => $paged,
						'post_parent' => $parent
					);
					$news_query = new wp_query($args);
					if($news_query->have_posts()) {
						while($news_query->have_posts()) {
							$news_query->the_post();
							?>
							<div class="news-content">
								<div class="news-thumbnail">
									<?php
									if(has_post_thumbnail()) {
										the_post_thumbnail();
									}
									?>
								</div>
								<div class="news-detail">
									<h4 class="news-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
									<div class="news-short">
										<?php
										echo wp_trim_words(preg_replace("/<img[^>]+\>/i", '', get_the_content()), 40, '...');
										?>
									</div>
									<a href="' .get_permalink(). '" class="btn btn-default">more <i class="fa fa-right-arrow"></i></a>
								</div>
							</div>
							<?php
						}
						wp_reset_postdata();
						posts_nav_link();
						//previous_posts_link();
					}
					?>
				</div>
			</div>
		</div>
		<!--end of news page-->
	</div>
</div>



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
					<?php the_content(); ?>
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