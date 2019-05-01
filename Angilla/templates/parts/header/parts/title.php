<?php
/**
 * The template for displaying the site title (with its wrapper)
 */
?>
<h1 class="navbar-brand col-auto <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <a class="navbar-brand-sitename <?php angi_fn_echo( 'title_class' ) ?>" href="<?php echo esc_url( home_url( '/' ) ) ?>">
    <span><?php bloginfo( 'name' ) ?></span>
  </a>
</h1>

