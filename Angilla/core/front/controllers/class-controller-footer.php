<?php
if ( ! class_exists( 'ANGI_controller_footer' ) ) :
  class ANGI_controller_footer extends ANGI_controllers {
    static $instance;

    function angi_fn_display_view_footer_push () {
      return esc_attr( angi_fn_opt( 'tc_sticky_footer') ) || angi_fn_is_customizing();
    }

    function angi_fn_display_view_footer_social_block() {
      return ( 1 == esc_attr( angi_fn_opt( "tc_social_in_footer" ) ) ) &&
        ( angi_fn_is_customize_preview_frame() || angi_fn_has_social_links() );
    }

    function angi_fn_display_view_btt_arrow() {
      return esc_attr( angi_fn_opt( 'tc_show_back_to_top' ) );
    }



    function angi_fn_display_view_footer_horizontal_widgets() {
      if ( 'none' == angi_fn_opt( 'tc_footer_horizontal_widgets' ) ) {
        return false;
      }


      if ( is_active_sidebar( 'footer_horizontal' ) ) {
        $to_display = true;
      }else {
        //If not widgets still display in preview (will display placeholders) when not prevdem
        $to_display = angi_fn_is_customize_preview_frame() && !angi_fn_isprevdem();
      }

      return apply_filters( 'angi_has_footer_horizontal_widgets', $to_display );
    }



    function angi_fn_display_view_footer_widgets() {
      $footer_widgets = apply_filters( 'angi_footer_widgets', ANGI_init::$instance -> footer_widgets );
      foreach ( $footer_widgets as $key => $area ) {
        if ( is_active_sidebar( $key ) ) {
          return apply_filters( 'angi_has_footer_widgets', true );
        }
      }

      //If not widgets still display in preview (will display placeholders) when not prevdem
      $to_display = angi_fn_is_customize_preview_frame() && !angi_fn_isprevdem();
      return apply_filters( 'angi_has_footer_widgets', $to_display );
    }

  }//end of class
endif;
