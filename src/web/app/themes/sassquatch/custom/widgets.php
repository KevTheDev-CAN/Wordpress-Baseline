<?php 

/**
 * Register our sidebars and widgetized areas.
 */
function sassquatch_widgets_init() {
	register_sidebar( array(
		'name'          => 'Sidebar Widgets',
		'id'            => 'sidebar_widgets',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
}
add_action('widgets_init', 'sassquatch_widgets_init');