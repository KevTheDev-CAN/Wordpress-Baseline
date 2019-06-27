<?php get_header(); ?>

  <?php
    $current_page = get_query_var('paged');
    $current_page = max( 1, $current_page );

    $total_rows = max( 0, $wp_query->found_posts );
    $total_pages = ceil( $total_rows / 10 );

    $pagination = paginate_links([
      'current' => $current_page,
      'total' => $total_pages,
      'show_all' => false,
      'end_size' => 1,
      'mid_size' => 2,
      'prev_next' => true,
      'prev_text' => html_entity_decode('<i class="far fa-angle-left"></i><span class="webaim-hidden">') . __('Previous', 'sassquatch') . html_entity_decode('</span>'),
      'next_text' => html_entity_decode('<i class="far fa-angle-right"></i><span class="webaim-hidden">') . __('Next', 'sassquatch') . html_entity_decode('</span>'),
      'type' => 'plain',
      'add_args' => false,
      'add_fragment' => '',
      'before_page_number' => '<span class="webaim-hidden">' . __('Results Page', 'sassquatch') . '</span> ',
      'after_page_number'  => ''
    ]);
  ?>
  <main id="main-content">
    <section id="page-content">
      <div class="container">
        <h1 class="text--centered"><?php echo __('Search Results', 'sassquatch'); ?></h1>
        <?php if ( have_posts() ) : ?>
          <div class="results">
            <?php while ( have_posts() ) : the_post(); ?>    
              <div class="result">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php the_excerpt(); ?></p>
                <?php wp_reset_postdata(); ?>
              </div>
              <hr>
            <?php endwhile; ?>
          </div>  
          <div class="pagination">
            <?php if($current_page > 1) : ?>
              <a href="/page/1/<?php if(isset($_GET['s'])) : echo '?s=' . $_GET['s']; endif; ?>" class="pagination__newest"><?php echo __('Newest', 'sassquatch'); ?></a>
            <?php endif; ?>
            <?= $pagination ?>
            <?php if($current_page < $total_pages) : ?>
              <a href="/page/<?php echo $total_pages; ?>/<?php if(isset($_GET['s'])) : echo '?s=' . $_GET['s']; endif; ?>" class="pagination__oldest"><?php echo __('Oldest', 'sassquatch'); ?></a>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'sassquatch' ); ?></p>
        <?php endif; ?>
      </div>
    </section>
  </main>

<?php get_footer(); ?>
