<?php
/**
 * The template for displaying the sidenav wrapper.
 * Contains:
 * Sidenav Button
 * Sidenav Menu
 */
?>
<div id="tc-sn" class="tc-sn side-nav__container d-none d-lg-block" <?php angi_fn_echo('element_attributes') ?>>
    <nav class="tc-sn side-nav__nav" <?php angi_fn_echo('element_attributes') ?>>
      <div class="tc-sn-inner">
        <?php
          if ( angi_fn_is_registered_or_possible('sidenav_menu_button') ) {
            angi_fn_render_template( 'header/parts/menu_button', array(
              'model_args' => array(
                'data_attributes' => 'data-toggle="sidenav" aria-expanded="false"',
                'element_tag'     => 'div'
              )
            ) );
          }
          if ( angi_fn_is_registered_or_possible('sidenav_menu') ) {
            angi_fn_render_template( 'header/parts/menu', array(
              'model_id'   => 'sidenav_menu',
            ));
          };
        ?>
      </div><!-- /.tc-sn-inner  -->
    </nav>
</div>