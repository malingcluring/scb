<footer>
	<div class="footer-feature">
        <div class="container">
            <div class="row">
				<div class="col-sm-4">
					<!--subscribed-->
					<div class="subscribe-news">
						<h2 for="subscribe">Subscribe Newsletter</h2>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit.
							Quisque dapibus vulputate metus, vitae imperdiet ex.
							Donec consectetur tincidunt neque, id ultricies leo.
							Fusce sed nisi et massa lobortis vestibulum.
						</p>
						<form id="subscribe_form" action="" class="form-inline">
							<div class="form-group">
								<div class="input-group">
									<input id="subscribe" type="text" class="form-control" placeholder="Enter your email..">
									<span class="input-group-btn">
										<input type="submit" class="btn btn-danger" value="Subscribe">
									</span>
								</div>
							</div>
						</form>
						<div class="tx-msg"></div>
					</div>
				</div>
				<div class="col-sm-4">
					<!---->
					<div class="map-location">
						<div class="map">
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3098032638068!2d106.76153731538157!3d-6.353925995402027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f79470fbf061%3A0x16db80805011ad07!2sPondok+Cabe+Golf+%26+Country+Club!5e0!3m2!1sen!2sid!4v1524024796248" frameborder="0" style="border:0" allowfullscreen></iframe>
								</div>
								<div class="location">
										<div class="desc-card">
								<address>
									<h2>
										<span class="icon">
											<i class="fa fa-address-card" aria-hidden="true"></i>
										</span>
										Contact Us
									</h2>
									<span class="icon">
										<i class="fa fa-road" aria-hidden="true"></i>
									</span>
									<strong>SCB</strong><br>
									
									1234 Example Street<br>
									Antartica, Example 0987<br>
									<span class="icon">
										<i class="fa fa-phone" aria-hidden="true"></i>
									</span>
									<abbr title="Phone">P:</abbr> (123) 456-7890
								</address>
					
								<address>
									<span class="icon">
										<i class="fa fa-globe" aria-hidden="true"></i>
									</span>
									<strong>Home Page</strong><br>
									<span class="icon">
										<i class="fa fa-envelope" aria-hidden="true"></i>
									</span>
									<a href="mailto:#">exam.ple@example.com</a>
								</address>
							</div>
						</div>
					</div>
					<!---->
				</div>
				<div class="col-sm-4">
					<div class="socials">
						<ul class="list-inline">
							<li>
								<a href="facebook.com" class="social facebook">
									<span class="icon"><i class="fa fa-facebook"></i></span> 
									Facebook
								</a>
							</li>
							<li>
								<a href="instagram.com" class="social instagram">
									<span class="icon"><i class="fa fa-instagram"></i></span>
									Instagram
								</a>
							</li>
							<li>
								<a href="youtube.com" class="social youtube">
									<span class="icon"><i class="fa fa-youtube"></i></span>
									Youtube
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
        </div>
    </div>
    
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