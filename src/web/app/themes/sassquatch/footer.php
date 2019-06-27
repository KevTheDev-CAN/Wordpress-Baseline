
  <footer class="footer">
		<div class="container">
			<a href="/" class="footer__brand">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/sassquatch-icon.svg" alt="">
			</a>
			<nav class="nav nav--footer">
				<ul class="nav__menu">
					<?php sassquatch_custom_menu('footer') ?>
				</ul>
			</nav>
		</div>
	</footer>

	<?php wp_footer(); ?>

</body>
</html>
