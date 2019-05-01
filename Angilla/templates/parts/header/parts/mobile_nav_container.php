<div class="mobile-nav__container <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
   <nav class="mobile-nav__nav flex-column angi-collapse" id="mobile-nav">
      <div class="mobile-nav__inner <?php angi_fn_echo( 'inner_elements_class' ) ?>">
      <?php
        if ( angi_fn_is_registered_or_possible( 'mobile_menu_search' ) ) {
          angi_fn_render_template( 'header/parts/search_form', array(
            'model_id'   =>  'mobile_menu_search',
            'model_args' => array(
              'element_tag'          => 'div',
            )
          ) );
        }
        if ( angi_fn_is_registered_or_possible('mobile_menu') ) {
          angi_fn_render_template( 'header/parts/menu', array(
            'model_id'   =>  'mobile_menu',
          ) );
        };
      ?>
      </div>
  </nav>
</div>