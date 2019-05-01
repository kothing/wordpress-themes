<?php
if ( ! class_exists( 'ANGI_controller_header' ) ) :
  class ANGI_controller_header extends ANGI_controllers {
    static $instance;

    function angi_fn_display_view_head() {
      return true;
    }

    function angi_fn_display_view_topbar_wrapper() {
      return 'none' !== esc_attr( angi_fn_opt( 'tc_header_show_topbar' ) );
    }

    function angi_fn_display_view_topbar_social_block() {
      return angi_fn_has_social_links() && 'none' !== esc_attr( angi_fn_opt( 'tc_header_show_socials' ) );
    }

    function angi_fn_display_view_branding_tagline() {
      return  '' != get_bloginfo( 'description' ) && in_array( esc_attr( angi_fn_opt( 'tc_header_desktop_tagline' ) ), array( 'brand_below', 'brand_next' ) );
    }

    function angi_fn_display_view_branding_tagline_below() {
      return  '' != get_bloginfo( 'description' ) && 'brand_below' == esc_attr( angi_fn_opt( 'tc_header_desktop_tagline' ) );
    }

    function angi_fn_display_view_branding_tagline_aside() {
      return  '' != get_bloginfo( 'description' ) && 'brand_next' == esc_attr( angi_fn_opt( 'tc_header_desktop_tagline' ) );
    }

    function angi_fn_display_view_topbar_tagline() {
      return '' != get_bloginfo( 'description' ) && 'topbar' == esc_attr( angi_fn_opt( 'tc_header_desktop_tagline' ) );
    }

    function angi_fn_display_view_mobile_tagline() {
      return  '' != get_bloginfo( 'description' ) && 1 == esc_attr( angi_fn_opt( 'tc_header_mobile_tagline' ) );
    }



    function angi_fn_display_view_title_alone() {
      return !$this -> angi_fn_display_view_logo_wrapper();
    }


    function angi_fn_display_view_title_next_logo() {
      return $this -> angi_fn_display_view_logo_wrapper() && esc_attr( angi_fn_opt( 'tc_title_next_logo' ) );
    }


    function angi_fn_display_view_logo_wrapper() {
      //display the logo wrapper
      return $this -> angi_fn_display_view_logo();
    }


    function angi_fn_display_view_logo() {
      $_logo_atts = angi_fn_get_logo_atts();
      return ! empty( $_logo_atts );
    }



    //when the 'main' navbar menu is allowed?
    //1) menu allowed
    //and
    //2) menu type is not aside (sidenav)
    function angi_fn_display_view_navbar_primary_menu() {
      return $this -> angi_fn_display_view_menu() && 'aside' != esc_attr( angi_fn_opt( 'tc_menu_style' ) ) && ( has_nav_menu( 'main' ) || angi_fn_isprevdem() );
    }

    //when the 'secondary' navbar menu is allowed?
    //1) menu allowed
    //and
    //2) menu type is aside (sidenav)
    function angi_fn_display_view_navbar_secondary_menu() {
      return $this -> angi_fn_display_view_menu() && angi_fn_is_secondary_menu_enabled();
    }

    //when the top navbar menu is allowed?
    //1) topbar is displayed in desktops
    //and
    //2) menu allowed
    function angi_fn_display_view_topbar_menu() {
      return in_array( esc_attr( angi_fn_opt( 'tc_header_show_topbar' ) ), array( 'desktop', 'desktop_mobile' ) ) && $this -> angi_fn_display_view_menu() && has_nav_menu( 'topbar' );
    }

    //when the sidenav menu is allowed?
    //1) menu allowed
    //and
    //2) menu style is aside
    function angi_fn_display_view_sidenav() {
      return $this -> angi_fn_display_view_menu() && 'aside' == esc_attr( angi_fn_opt( 'tc_menu_style' ) ) && has_nav_menu( 'main' );
    }

    //to improve
    function angi_fn_display_view_mobile_menu() {
      return ! angi_fn_opt('tc_hide_all_menus');
    }

    function angi_fn_display_view_menu() {
      return ! angi_fn_opt('tc_hide_all_menus');
    }

    //when the 'sidevan menu button' is allowed?
    //1) menu button allowed
    //2) menu style is aside ( sidenav)
    //==
    //angi_fn_display_view_sidenav
    function angi_fn_display_view_sidenav_menu_button() {
      return $this -> angi_fn_display_view_sidenav();
    }
    function angi_fn_display_view_sidenav_navbar_menu_button() {
      return $this -> angi_fn_display_view_sidenav();
    }

    //when the 'mobile menu button' is allowed?
    //1) menu button allowed
    // or
    //2) mobile search in menu allowed
    function angi_fn_display_view_mobile_menu_button() {
      return ! angi_fn_opt('tc_hide_all_menus');
    }


    //when the 'menu button' is allowed?
    //1) menu allowed
    function angi_fn_display_view_menu_button() {
      return $this -> angi_fn_display_view_menu();
    }



    /* Header wc cart */
    function angi_fn_display_view_desktop_primary_wc_cart() {
      //in plugins compat we use this hook to enable wc cart options when WooCommerce is enabled
      if ( ! apply_filters( 'angi_woocommerce_options_enabled_controller', false )  )
        return false;

      return 'navbar' == angi_fn_opt( 'tc_header_desktop_wc_cart' );
    }


    function angi_fn_display_view_desktop_topbar_wc_cart() {
      //in plugins compat we use this hook to enable wc cart options when WooCommerce is enabled
      if ( ! apply_filters( 'angi_woocommerce_options_enabled_controller', false )  )
        return false;

      return 'topbar' == angi_fn_opt( 'tc_header_desktop_wc_cart' );
    }

    function angi_fn_display_view_mobile_wc_cart() {
      //in plugins compat we use this hook to enable wc cart options when WooCommerce is enabled
      if ( ! apply_filters( 'angi_woocommerce_options_enabled_controller', false )  )
        return false;

      return angi_fn_opt( 'tc_header_mobile_wc_cart' );
    }


    /* Header search */
    function angi_fn_display_view_desktop_primary_search() {
      return 'navbar' == angi_fn_opt( 'tc_header_desktop_search' );
    }


    function angi_fn_display_view_desktop_topbar_search() {
      return 'topbar' == angi_fn_opt( 'tc_header_desktop_search' );
    }


    function angi_fn_display_view_mobile_navbar_search() {
      return 'navbar' == angi_fn_opt( 'tc_header_mobile_search' );
    }

    function angi_fn_display_view_mobile_menu_search() {
      return 'menu' == angi_fn_opt( 'tc_header_mobile_search' );
    }


    /*
    * Display primary_nav_utils only if one of the below are possible
    * - primary nav search in desktops
    * - primary woocommerce cart in desktops
    * - sidenav menut button in the primary navbar
    */
    function angi_fn_display_view_primary_nav_utils() {
      return $this->angi_fn_display_view_desktop_primary_search() || $this->angi_fn_display_view_desktop_primary_wc_cart() || $this->angi_fn_display_view_sidenav_navbar_menu_button();
    }

    /*
    * Display primary_nav_utils only if one of the below are possible
    * - primary nav search in desktops
    * - primary woocommerce cart in desktops
    * -
    */
    function angi_fn_display_view_topbar_nav_utils() {
      return $this->angi_fn_display_view_desktop_topbar_search() || $this->angi_fn_display_view_desktop_topbar_wc_cart() ;
    }

  }//end of class
endif;