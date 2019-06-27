<?php

function sassquatch_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'sassquatch_mime_types' );


function sassquatch_svg_enqueue_scripts( $hook ) {
	wp_enqueue_style( 'sassquatch-svg-style', get_theme_file_uri( '/assets/styles/svg.css' ) );
	wp_enqueue_script( 'sassquatch-svg-script', get_theme_file_uri( '/assets/scripts/libraries/svg.js' ), array('jquery') );
	wp_localize_script( 'sassquatch-svg-script', 'script_vars',
		array( 'AJAXurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'admin_enqueue_scripts', 'sassquatch_svg_enqueue_scripts' );


function sassquatch_get_attachment_url_media_library() {

	$url          = '';
	$attachmentID = isset( $_REQUEST['attachmentID'] ) ? $_REQUEST['attachmentID'] : '';
	if ( $attachmentID ) {
		$url = wp_get_attachment_url( $attachmentID );
	}

	echo $url;

	die();
}

add_action( 'wp_ajax_svg_get_attachment_url', 'sassquatch_get_attachment_url_media_library' );