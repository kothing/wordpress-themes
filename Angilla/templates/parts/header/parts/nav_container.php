<?php
/**
 * The template for displaying the logo wrapper
 */
?>
<div class="primary-nav__container <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="primary-nav__wrapper flex-lg-row align-items-center justify-content-end">
     <?php if ( angi_fn_is_registered_or_possible( 'navbar_primary_menu' ) || angi_fn_is_registered_or_possible( 'navbar_secondary_menu' ) ) { ?>
         <nav class="primary-nav__nav col" id="primary-nav">
          <?php
              angi_fn_render_template( 'header/parts/menu', array(
                'model_id'   =>  angi_fn_is_registered_or_possible( 'navbar_primary_menu' ) ? 'navbar_primary_menu' : 'navbar_secondary_menu',
              ) );
          ?>
        </nav>
    <?php }
      else {
        angi_fn_print_add_menu_button();
      }

      if ( angi_fn_is_registered_or_possible( 'primary_nav_utils' ) ) angi_fn_render_template( 'header/parts/primary_nav_utils' )
    ?>
  </div>
</div>
