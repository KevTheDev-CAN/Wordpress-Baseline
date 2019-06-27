<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<main id="main-content">
		<section id="page-content">
			<div class="container">
				<div class="grid">
					<div class="<?php if(sassquatch_custom_sidebar_menu() || is_active_sidebar('sidebar_widgets')) : ?>grid__col--md-8<?php else : ?>grid__col--md-12<?php endif; ?>">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
					</div>
					<?php if(sassquatch_custom_sidebar_menu() || is_active_sidebar('sidebar_widgets')) : ?>
						<?php get_template_part('/partials/sidebar'); ?>
					<?php endif; ?>
			</div>
		</section>
	</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
