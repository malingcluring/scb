<footer>
    <?php get_sidebar(); ?>
    
    <div class="footer-menu">
        <div class="container">
            <div class="row">
                <?php
                    $menu_args = array(
                        'theme_location' 	=> 'footer',
                        'menu_class'		=> 'footer-menu-list list-inline',
                        'menu_id'        	=> 'footer-menu',
                        'echo'            	=> true
                    ); 
                ?>
                <?php wp_nav_menu($menu_args);?>
				<div class="footer-logo">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/scb-logo.png" alt="<?php echo get_bloginfo('template_url') ?>/images/scb-logo.png">
                </div>
            </div>
        </div>
    </div>
    
</footer>
<?php wp_footer(); ?>
	
</body>

</html>