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
<h1 class="the-title">
	<?php
		//the_title();
		//$title2 = the_title();
		echo str_replace(' ', '<span>', get_the_title());
		/*
		$title2 =  get_the_title();
		$xStr = preg_split('/ +/', $title2);
		$cStr2 = count($xStr);
		$result2 = "";
		if($cStr2 > 1){
			for($i=0; $i<$cStr2-1;$i++){
				$result2 .= $xStr[$i]." ";
			}
			$result2 .= "<span>".$xStr[$cStr2-1];
		} else if ($cStr2 == 0) {
			$result2 = $title2;
		} else {
			$result2 = $xStr[0]."<span>".$xStr[1];
		}
		echo $result2;
		*/
	?>
</h1>
<?php the_content(); ?>
</div>
<?php
	endwhile;
	else : _e('sorry, no post match');
	?>
	
<?php endif; ?>

<?php get_footer(); ?>