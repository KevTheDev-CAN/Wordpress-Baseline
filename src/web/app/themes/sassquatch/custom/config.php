<?php

function sassquatch_setup() {
  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'post-thumbnails' );

  set_post_thumbnail_size( 390, 200);

  add_image_size( 'sassquatch-featured-image', 2000, 1200, true );

  add_image_size( 'sassqutch-thumbnail-avatar', 100, 100, true );

  register_nav_menus( array(
    'header'    => __('Header Menu', 'sassquatch' ),
    'header-utility' => __('Header Utility Menu', 'sassquatch'),
    'footer' => __('Footer Menu', 'sassquatch'),
    'footer-utility' => __('Footer Utility Menu', 'sassquatch'),
  ) );

  /*
   * Switch default core markup for search form, comment form, and comments
   * to output valid HTML5.
   */
  add_theme_support( 'html5', array(
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ) );

  /*
   * Enable support for Post Formats.
   *
   * See: https://codex.wordpress.org/Post_Formats
   */
  add_theme_support( 'post-formats', array(
    'aside',
    'image',
    'video',
    'quote',
    'link',
    'gallery',
    'audio',
    'superscript',
    'subscript'
  ) );

  // Add theme support for Custom Logo.
  add_theme_support( 'custom-logo', array(
    'width'       => 250,
    'height'      => 250,
    'flex-width'  => true,
  ) );

  // Add theme support for selective refresh for widgets.
  add_theme_support( 'customize-selective-refresh-widgets' );

  // add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );

}
add_action( 'after_setup_theme', 'sassquatch_setup' );

function add_theme_assets() {
  wp_enqueue_style( 'theme', get_template_directory_uri() . '/assets/styles/min/sassquatch.min.css', false, '1.1', 'all');
  wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/fontawesome.min.css', false, '1.1', 'all');
  wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/scripts/min/sassquatch.min.js', array (), 1.1, true);
}
add_action( 'wp_enqueue_scripts', 'add_theme_assets' );

function post_remove () { 
  remove_menu_page('edit.php');
  remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'post_remove');