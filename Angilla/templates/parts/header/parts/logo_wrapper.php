<?php
/**
 * The template for displaying the logo wrapper
 */
?>
<div class="navbar-brand col-auto <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <a class="navbar-brand-sitelogo" href="<?php echo esc_url( home_url( '/' ) ) ?>"  aria-label="<?php bloginfo( 'name' ) ?> | <?php echo get_bloginfo( 'description', 'display' ) ?>" >
    <?php
      if ( angi_fn_is_registered_or_possible('logo') )
        angi_fn_render_template( 'header/parts/logo' );
    ?>
  </a>
</div>
