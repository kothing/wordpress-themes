<?php
/**
 * The template for displaying a menu ( both main and secondary in navbar or/and the sidenav one)
 */
?>
<div class="nav__menu-wrapper <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
<?php
  wp_nav_menu( array(
    'theme_location'  => angi_fn_get_property( 'theme_location' ),
    'container'       => null,
    'menu_class'      => angi_fn_get_property( 'menu_class' ),
    'fallback_cb'     => angi_fn_get_property( 'fallback_cb' ),
    'walker'          => angi_fn_get_property( 'walker' ),
    'menu_id'         => angi_fn_get_property( 'menu_id' ),
    'link_before'     => '<span class="nav__title">',
    'link_after'      => '</span>',
    'dropdown_on'     => angi_fn_get_property( 'dropdown_on' )
  ) );
?>
</div>