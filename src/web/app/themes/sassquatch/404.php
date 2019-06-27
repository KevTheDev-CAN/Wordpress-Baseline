<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<main id="main-content">
		<section id="page-content">
			<div class="container">
				<h1>Oops! The page you were looking for could not be found.</h1>
				<?php the_content(); ?>
			</div>
		</section>
	</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
