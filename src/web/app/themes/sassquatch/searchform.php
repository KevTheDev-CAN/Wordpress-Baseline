<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="form--search" action="/">
  <div class="form__group form__group--inline-submit">
  	<i class="fa fa-search form__icon" aria-hidden="true"></i>
    <label for="<?php echo $unique_id; ?>" class="webaim-hidden"><?php echo __('Search by keyword', 'sassquatch'); ?></label>
    <input type="search" id="<?php echo $unique_id; ?>" class="form__input search-field" placeholder="<?php echo __('Search SIRC.ca', 'sassquatch'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit button button--primary"><?php echo __('Search', 'roots'); ?></button>
  </div>
</form>