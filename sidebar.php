<?php

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

?>

<div id="sidebar-primary" class="sidebar">
    <div class="container">
        <div class="row">
            <?php dynamic_sidebar('primary'); ?>
        </div>
        
    </div>
</div>