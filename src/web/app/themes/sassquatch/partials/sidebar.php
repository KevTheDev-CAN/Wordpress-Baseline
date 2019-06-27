<div class="grid__col--md-4 sidebar">
  <?php if(sassquatch_custom_sidebar_menu()) : ?>
    <div class="card">
      <?php echo sassquatch_custom_sidebar_menu(); ?>
    </div>
  <?php endif; ?>
  <?php if (is_active_sidebar('sidebar_widgets')) : ?>
    <?php dynamic_sidebar('sidebar_widgets'); ?>
  <?php endif; ?>
</div>