<?php
/**
 * The template for displaying the theme's slider (wrapper)
 *
 */
?>
<div id="angilla-slider-<?php angi_fn_echo( 'id' ) ?>" class="section-slider <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="angi-slider-holder">
<?php
    if ( angi_fn_get_property( 'has_loader' ) ) : ?>
        <div id="angi-slider-loader-wrapper-<?php angi_fn_echo( 'id' ) ?>" class="angi-slider-loader-wrapper">
            <div class="angi-img-gif-loader"></div>
<?php
            angi_fn_echo( 'pure_css_loader' )
?>
        </div>
<?php
    endif;
    do_action( '__before_carousel_inner' );
  ?>
  <div class="<?php angi_fn_echo( 'inner_class' ) ?>" <?php angi_fn_echo( 'inner_attrs' ) ?> >
<?php
        while ( (bool) $the_slide = angi_fn_get_property( 'the_slide' ) )
          angi_fn_render_template( 'modules/slider/slide', array( 'model_args' => array( 'the_slide' => $the_slide ) ) )
?>
  </div><!-- /.carousel-inner -->
<?php
    do_action( '__after_carousel_inner' );
    if ( angi_fn_get_property( 'has_slider_edit_link' ) ) {
      echo angi_fn_edit_button( array(
        'echo' => false,
        'class' => 'slider-btn-edit inverse',
        'link'  => angi_fn_is_customizing() ? angi_fn_get_customizer_focus_link( array( 'wot' => 'control', 'id' => 'tc_theme_options[tc_front_slider]' ) ) : angi_fn_get_property( 'slider_edit_link' ),
        'text'  => angi_fn_get_property( 'slider_edit_link_text' ),
        'visible_when_customizing' => true
      ) );
    }

    if ( angi_fn_get_property( 'has_controls' ) ) {
      angi_fn_carousel_nav();
    }
?>
  </div>
</div><!-- /#angilla-slider -->