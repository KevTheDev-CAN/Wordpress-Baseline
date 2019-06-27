<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<title>
	<?php if(is_front_page()): ?>
		<?php wp_title(''); ?>
	<?php elseif(is_404()) : ?>
		404 - <?php echo get_bloginfo('name'); ?>
	<?php else: ?>
		<?php wp_title(''); ?>
	<?php endif;?>
</title>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="profile" href="http://gmpg.org/xfn/11">

<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon.ico">

<!-- Touch Icons -->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-16x16.png" sizes="16x16">
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/mstile-150x150.png" sizes="150x150">
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/android-chrome-512x512.png" sizes="512x512">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/icons/site.webmanifest">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<a class="skip-link" href="#main-content"><?php echo __('Skip to main content', 'sassquatch'); ?></a>
	
	<header class="header">
		<div class="header__top">
			<div class="container">
				<a href="/" class="header__brand">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/sassquatch-icon.svg" alt="">
				</a>
				<nav class="nav nav--primary">
					<ul class="nav__menu">
						<?php sassquatch_custom_menu('header') ?>
					</ul>
				</nav>
			</div>
		</div>
		<div class="header__bottom">
			<div class="container">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header>

