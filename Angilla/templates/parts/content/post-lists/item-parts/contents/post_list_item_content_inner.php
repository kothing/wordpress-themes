<?php
/**
 * The template for displaying the inner content in a post list element
 *
 * In WP loop
 *
 */
?>
<div class="tc-content-inner <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?> >
  <?php
      if ( angi_fn_get_property( 'content_template' ) ) {
          //render the $content_template;
          angi_fn_render_template( angi_fn_get_property( 'content_template' ), angi_fn_get_property( 'content_args' ) );
      }
      else { ?>
          <div class="angi-wp-the-content">
              <?php angi_fn_echo( 'content' ); ?>
          </div>
      <?php }
      angi_fn_link_pages();
  ?>
</div>