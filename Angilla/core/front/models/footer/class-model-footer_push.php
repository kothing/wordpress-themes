<?php
class ANGI_footer_push_model_class extends ANGI_Model {

  function angi_fn_body_class( $_classes ) {
    //this module can be instantiated in the customizer also when the relative option is disabled
    //as it's transported via postMessage. The body class above is hence handled in the preview js
    //to allow the js to perform the push if needed.
    if ( esc_attr( angi_fn_opt( 'tc_sticky_footer') ) )
      $_classes[] = 'angi-sticky-footer';
    return $_classes;
  }

  /*
  * Callback of angi_fn_user_options_style hook
  * @return css string
  *
  * @package Angilla
  * @since Angilla 1.0.0
  */
  function angi_fn_user_options_style_cb( $_css ){
    $_css = sprintf("%s\n%s",
      $_css,
        "#angi-push-footer { display: none; visibility: hidden; }
        .angi-sticky-footer #angi-push-footer.sticky-footer-enabled { display: block; }
        "
      );
    return $_css;
  }

}