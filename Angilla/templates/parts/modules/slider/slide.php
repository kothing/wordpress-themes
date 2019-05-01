<?php
/**
 * The template for displaying a single slide item
 *
 */
?>
<div class="carousel-cell item <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <?php if ( angi_fn_get_property( 'link_whole_slide' ) ) : ?>
    <a class="tc-slide-link bg-link" href="<?php angi_fn_echo( 'link_url' ) ?>" target="<?php angi_fn_echo( 'link_target' ) ?>" title="<?php _e( 'Go to', 'angilla' ) ?>"></a>
  <?php endif ?>
  <div class="angi-filter <?php angi_fn_echo( 'img_wrapper_class' ) ?>">
    <?php
        do_action('__before_all_slides_background__');
          angi_fn_echo( 'slide_background' );
        do_action('__after_all_slides_background__');
    ?>
  </div> <!-- .carousel-image -->

  <?php

if ( angi_fn_get_property( 'has_caption' ) ) :

  do_action('__before_all_slides_caption__');

  ?>
  <div class="carousel-caption slider-text">
    <?php if ( angi_fn_get_property( 'title' ) ): ?>
    <!-- TITLE -->
      <h2 class="angi-title display-1 thick very-big" <?php angi_fn_echo( 'color_style' ) ?>><?php angi_fn_echo( 'title' ) ?></h2>
    <?php endif; ?>
    <?php if ( angi_fn_get_property( 'subtitle' ) ) : ?>
    <!-- TEXT -->
      <h3 class="angi-subtitle semi-bold" <?php angi_fn_echo( 'color_style' ) ?>><?php angi_fn_echo( 'subtitle' ) ?></h3>
    <?php endif; ?>
    <!-- BUTTON -->
    <?php if ( angi_fn_get_property( 'button_text' ) ): ?>
      <div class="angi-cta-wrapper">
        <a class="angi-cta btn btn-skin-h-dark caps" href="<?php angi_fn_echo( 'button_link' ) ?>" target="<?php angi_fn_echo( 'link_target' ) ?>"><?php angi_fn_echo( 'button_text' ) ?></a>
      </div>
    <?php endif; ?>
  </div>
  <?php

  do_action('__after_all_slides_caption__');
  /* endif caption*/
endif;

  /* edit link */
  if ( (bool) $edit_url = angi_fn_get_property( 'edit_url' ) ) {
    angi_fn_edit_button( array( 'class' => 'slide-btn-edit inverse', 'link'  => $edit_url ) );
  }

?>
</div><!-- /.item -->