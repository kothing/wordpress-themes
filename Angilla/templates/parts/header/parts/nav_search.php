<?php
/**
 * The template for displaying the header search form item button
 */
?>
<li class="nav__search <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <a href="#" class="search-toggle_btn icn-search <?php angi_fn_echo('search_toggle_class'); ?>" <?php angi_fn_echo('search_toggle_attributes'); ?> aria-expanded="false"><span class="sr-only">Search</span></a>
  <?php if ( angi_fn_get_property( 'has_dropdown' ) ) : ?>
    <ul class="dropdown-menu angi-dropdown-menu">
      <?php
        angi_fn_render_template( 'header/parts/search_form', array(
          'model_args' => array(
            'element_tag'     => 'li',
            'element_class'   => angi_fn_get_property( 'search_form_container_class' )
          )
        ) );
      ?>
    </ul>
  <?php endif ?>
</li>
