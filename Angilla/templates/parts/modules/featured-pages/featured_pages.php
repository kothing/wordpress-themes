<?php
/**
 * The template for displaying the featured pages wrapper
 */
?>
<div class="container marketing <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <?php
    do_action( '__before_fp' );
    while ( $featured_page = angi_fn_get_property( 'featured_page' ) ) {
      angi_fn_render_template(
        'modules/featured-pages/featured_page',
        array( 'model_args' => $featured_page )
      );
    }
    do_action( '__after_fp' );
  ?>
</div>
