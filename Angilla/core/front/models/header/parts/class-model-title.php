<?php
class ANGI_title_model_class extends ANGI_Model {

    public $title_class;

    /**
    * @override
    */
    function __construct( $model = array() ) {
        parent::__construct( $model );

        $this -> title_class         = 1 == angi_fn_opt( 'tc_header_title_underline' ) ? ' angi-underline' : '';
        $this -> element_class       = apply_filters( 'angi_logo_class', '' );
    }


    /*
    * Custom CSS
    */
    function angi_fn_user_options_style_cb( $_css ) {
        //title shrink
        if ( 0 != esc_attr( angi_fn_opt( 'tc_sticky_header') ) && 0 != esc_attr( angi_fn_opt( 'tc_sticky_shrink_title_logo') ) ) {
            $_css = sprintf("%s%s", $_css,
              "
              .sticky-enabled .angi-shrink-on .navbar-brand-sitename {
                font-size: 0.8em;
                opacity: 0.8;
              }");
        }
        return $_css;
    }

}