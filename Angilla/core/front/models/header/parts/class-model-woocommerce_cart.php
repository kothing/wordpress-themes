<?php
class ANGI_woocommerce_cart_model_class extends ANGI_Model {

    public $defaults = array( 'display_widget' => true );

    public $wc_cart_url;
    public $wc_cart_count_html;
    public $wc_cart_link_attributes;
    public $display_widget;

    private static $_woocart_filter_added;
    private static $_woocart_style_printed;

    public function __construct( $model ) {
        parent::__construct( $model);

        //This filter should be added once only.
        //There might be various instances of this object but we don't want it to be added more than once
        if ( empty( self::$_woocart_filter_added ) ) {
            self::$_woocart_filter_added = true;
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'angi_fn_woocommerce_add_to_cart_fragment' ) );
        }

        // WC_Cart::get_cart_url is <strong>deprecated</strong> since version 2.5! Use wc_get_cart_url instead.
        //
        if ( function_exists( 'wc_get_cart_url' ) ) {
            $this->wc_cart_url = esc_url( wc_get_cart_url() );
        } else if ( function_exists( 'WC' ) ) {
            $this->wc_cart_url = esc_url( WC()->cart->get_cart_url() );
        }

    }


    /*
    * Fired just before the view is rendered
    * @hook: pre_rendering_view_{$this -> id}, 9999
    */
    public function angi_fn_setup_late_properties() {

        //display_widget
        if ( $this->display_widget ) {
            $display_widget = function_exists( 'angi_fn_wc_is_checkout_cart' ) ? ! angi_fn_wc_is_checkout_cart() : true;
        } else {
            $display_widget =  false;
        }

        $wc_cart_count_html      = $this->angi_fn__get_wc_cart_count_html();
        $wc_cart_link_attributes = $display_widget ? 'data-toggle="angi-dropdown"' : '';

        $this->angi_fn_update( compact( 'display_widget', 'wc_cart_count_html', 'wc_cart_link_attributes' ) );
    }


    // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
    function angi_fn_woocommerce_add_to_cart_fragment( $fragments ) {
        $fragments['sup.angi-wc-count'] = $this -> angi_fn__get_wc_cart_count_html();

        return $fragments;
    }


    function angi_fn__get_wc_cart_count_html() {
        if ( ! function_exists( 'WC' ) )
            return;

        $WC          = WC();
        $_cart_count = $WC->cart->get_cart_contents_count();
        return sprintf( '<sup class="count angi-wc-count">%1$s</sup>', $_cart_count ? $_cart_count : '' );
    }


    /**
    * @hook angi_fn_user_options_style
    */
    function angi_fn_user_options_style_cb( $_css ) {
        //This css should be added once only.
        //There might be various instances of this object but we don't want it to be added more than once
        if ( empty( self::$_woocart_style_printed ) ) {
            self::$_woocart_style_printed = true;

            return sprintf( "%s\n%s",
                  $_css,
                  ".sticky-enabled .angi-wccart-off .primary-nav__woocart { display: none; }
                  .logo-center .primary-nav__woocart .dropdown-menu,
                  .logo-left .primary-nav__woocart .dropdown-menu{ right: 0; left: auto; }/*open left*/
            ");
        }

        return $_css;
    }/*end rendering the cart icon in the header */

}//end class