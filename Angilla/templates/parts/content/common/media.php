<?php
/**
 * The template for displaying the thumbnails in post lists (alternate layout) contexts
 *
 * In WP loop
 *
 */
?>
<section class="tc-thumbnail entry-media__holder <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="entry-media__wrapper angi__r-i <?php angi_fn_echo('inner_wrapper_class') ?>">
  <?php
  if ( angi_fn_get_property( 'media_template' ) ):
    if ( angi_fn_get_property( 'has_permalink' ) ) : ?>
      <a class="<?php angi_fn_echo( 'link_class' ) ?>" rel="bookmark" title="<?php the_title_attribute( array( 'before' => __('Permalink to:&nbsp;', 'angilla') ) ) ?>" href="<?php the_permalink() ?>"></a>
  <?php
    endif; //bg-link

      //render the $media_template;
      angi_fn_render_template( angi_fn_get_property( 'media_template' ), angi_fn_get_property( 'media_args' ) );

    elseif ( 'format-icon' == angi_fn_get_property( 'media' ) ):
  ?>
      <div class="post-type__icon">
        <a class="bg-icon-link icn-format" rel="bookmark" title="<?php the_title_attribute( array( 'before' => __('Permalink to ', 'angilla') ) ) ?>" href="<?php the_permalink() ?>"></a>
      </div>

  <?php
  endif ?>
  </div>
</section>